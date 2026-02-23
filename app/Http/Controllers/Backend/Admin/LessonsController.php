<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Test;
use App\Models\CourseContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreLessonsRequest;
use App\Http\Requests\Admin\UpdateLessonsRequest;
use App\Services\MediaUploadService;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class LessonsController extends Controller
{
    protected MediaUploadService $mediaUploadService;

    public function __construct(MediaUploadService $mediaUploadService)
    {
        $this->mediaUploadService = $mediaUploadService;
    }

    /**
     * Display a listing of Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('lesson_access')) {
            return abort(401);
        }
        
        $courses = Course::has('category')
            ->ofTeacher()
            ->orderBy("sort_order", "asc")
            ->pluck('title', 'id')
            ->prepend('Please select', '');
            
        $contents = [];

        if ($request->course_id) {
            $contents = CourseContent::where("course_id", $request->course_id)
                ->orderBy("sort_order", "asc")
                ->get();
        }
        
        return view('backend.lessons.index', compact('courses', 'contents'));
    }

    /**
     * Display a listing of Lessons via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $lessons = Lesson::whereIn('course_id', Course::ofTeacher()->pluck('id'));

        if ($request->course_id != "") {
            $lessons = $lessons->where('course_id', (int)$request->course_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if ($request->show_deleted == 1) {
            if (!Gate::allows('lesson_delete')) {
                return abort(401);
            }
            $lessons = Lesson::query()
                ->with('course')
                ->orderBy('created_at', 'desc')
                ->onlyTrashed()
                ->get();
        }

        $rels = [];
        if ($request->content_id) {
            foreach ($lessons as $ls) {
                if ($ls->content_id == $request->content_id) {
                    $rels[] = $ls;
                }
            }
        } else {
            $rels = $lessons;
        }

        $has_view = auth()->user()->can('lesson_view');
        $has_edit = auth()->user()->can('lesson_edit');
        $has_delete = auth()->user()->can('lesson_delete');

        return DataTables::of($rels)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')
                        ->with(['route_label' => 'admin.lessons', 'label' => 'lesson', 'value' => $q->id]);
                }
                
                if ($has_edit) {
                    $view .= view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.lessons.edit', ['lesson' => $q->id])])
                        ->render();
                }

                if ($has_delete) {
                    $view .= view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.lessons.destroy', ['lesson' => $q->id])])
                        ->render();
                }

                if (auth()->user()->can('test_view') && $q->test) {
                    $view .= '<a href="' . route('admin.tests.index', ['lesson_id' => $q->id]) . '" class="btn btn-success btn-block mb-1">' . trans('labels.backend.tests.title') . '</a>';
                }

                return $view;
            })
            ->editColumn('course', function ($q) {
                return $q->course ? $q->course->title : 'N/A';
            })
            ->editColumn('sort_order', function ($q) {
                return '<input type="number" name="sort_order" value="' . $q->position . '" class="form-control sort_order" data-id="' . $q->id . '" style="width: 80px;">';
            })
            ->editColumn('lesson_image', function ($q) {
                return $q->lesson_image 
                    ? '<img height="50px" src="' . asset('storage/uploads/' . $q->lesson_image) . '">' 
                    : 'N/A';
            })
            ->editColumn('free_lesson', function ($q) {
                return $q->free_lesson == 1 ? "Yes" : "No";
            })
            ->editColumn('published', function ($q) {
                return $q->published == 1 ? "Yes" : "No";
            })
            ->rawColumns(['lesson_image', 'actions', 'sort_order'])
            ->make();
    }

    /**
     * Show the form for creating new Lesson.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }
        
        $courses = Course::has('category')
            ->ofTeacher()
            ->orderBy("sort_order", "asc")
            ->get()
            ->pluck('title', 'id')
            ->prepend('Please select', '');
            
        $contents = [];

        if ($request->course_id) {
            $contents = CourseContent::where("course_id", $request->course_id)->get();
        }
        
        return view('backend.lessons.create', compact('courses', 'contents'));
    }

    /**
     * Store a newly created Lesson in storage.
     *
     * @param  \App\Http\Requests\StoreLessonsRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLessonsRequest $request)
    {
        if (!Gate::allows('lesson_create')) {
            return abort(401);
        }

        // Create lesson
        $position = Lesson::where('course_id', $request->course_id)->max('position') + 1;
        $lesson = Lesson::create($request->except('downloadable_files', 'lesson_image') + ['position' => $position]);

        // Handle lesson image upload
        if ($request->hasFile('lesson_image')) {
            $lesson->lesson_image = $this->uploadImage($request->file('lesson_image'));
            $lesson->save();
        }

        // Handle video media
        $this->handleVideoMedia($request, $lesson);

        // Handle PDF upload
        if ($request->hasFile('add_pdf')) {
            $this->mediaUploadService->uploadPDF($request->file('add_pdf'), Lesson::class, $lesson->id, $lesson->title . ' - PDF');
        }

        // Handle Audio upload
        if ($request->hasFile('add_audio')) {
            $this->mediaUploadService->uploadAudio($request->file('add_audio'), Lesson::class, $lesson->id, $lesson->title . ' - Audio');
        }

        // Handle downloadable files
        $this->handleDownloadableFiles($request, $lesson);

        // Update lesson details
        $lesson->content_id = trim($request->content_id ?? '');
        $lesson->free_lesson = $request->boolean('free_lesson');
        $lesson->published = $request->boolean('published');
        $lesson->duration = $request->duration;
        
        if (!$request->filled('slug')) {
            $lesson->slug = Str::slug($request->title);
        }
        
        $lesson->save();

        // Update course timeline
        $this->updateCourseTimeline($lesson, $request->course_id);

        return redirect()
            ->route('admin.lessons.index', ['course_id' => $request->course_id])
            ->withFlashSuccess(__('alerts.backend.general.created'));
    }

    /**
     * Show the form for editing Lesson.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }
        
        $courses = Course::has('category')
            ->ofTeacher()
            ->orderBy("sort_order", "asc")
            ->get()
            ->pluck('title', 'id')
            ->prepend('Please select', '');

        $lesson = Lesson::with('media')->findOrFail($id);
        
        $videos = '';
        if ($lesson->media) {
            $videos = $lesson->media()->where('media.type', '=', 'YT')->pluck('url')->implode(',');
        }
        
        $contents = [];
        if ($lesson) {
            $contents = CourseContent::where("course_id", $lesson->course_id)->get();
        }

        return view('backend.lessons.edit', compact('lesson', 'courses', 'videos', 'contents'));
    }

    /**
     * Update Lesson in storage.
     *
     * @param  \App\Http\Requests\UpdateLessonsRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLessonsRequest $request, $id)
    {
        if (!Gate::allows('lesson_edit')) {
            return abort(401);
        }
        
        $lesson = Lesson::findOrFail($id);
        
        // Update basic fields
        $lesson->update($request->except('downloadable_files', 'lesson_image'));
        
        $lesson->content_id = trim($request->content_id ?? '');
        $lesson->free_lesson = $request->boolean('free_lesson');
        $lesson->published = $request->boolean('published');
        $lesson->duration = $request->duration;
        
        if (!$request->filled('slug') || $request->slug === '') {
            $lesson->slug = Str::slug($request->title);
        }
        
        // Handle lesson image upload
        if ($request->hasFile('lesson_image')) {
            // Delete old image
            if ($lesson->lesson_image) {
                $this->deleteFile($lesson->lesson_image);
            }
            $lesson->lesson_image = $this->uploadImage($request->file('lesson_image'));
        }

        // Handle video media
        $this->handleVideoMedia($request, $lesson);

        // Handle PDF upload - delete old if new one is uploaded
        if ($request->hasFile('add_pdf')) {
            $oldPdf = $lesson->mediaPDF;
            if ($oldPdf) {
                $this->mediaUploadService->delete($oldPdf);
            }
            $this->mediaUploadService->uploadPDF($request->file('add_pdf'), Lesson::class, $lesson->id, $lesson->title . ' - PDF');
        }

        // Handle Audio upload - delete old if new one is uploaded
        if ($request->hasFile('add_audio')) {
            $oldAudio = $lesson->mediaAudio;
            if ($oldAudio) {
                $this->mediaUploadService->delete($oldAudio);
            }
            $this->mediaUploadService->uploadAudio($request->file('add_audio'), Lesson::class, $lesson->id, $lesson->title . ' - Audio');
        }

        // Handle downloadable files
        $this->handleDownloadableFiles($request, $lesson);

        $lesson->save();

        // Update course timeline
        $this->updateCourseTimeline($lesson, $request->course_id);

        return redirect()
            ->route('admin.lessons.index', ['course_id' => $request->course_id])
            ->withFlashSuccess(__('alerts.backend.general.updated'));
    }

    /**
     * Display Lesson.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }
        
        $courses = Course::orderBy("sort_order", "asc")
            ->get()
            ->pluck('title', 'id')
            ->prepend('Please select', '');

        $tests = Test::where('lesson_id', $id)->get();
        $lesson = Lesson::findOrFail($id);

        return view('backend.lessons.show', compact('lesson', 'tests', 'courses'));
    }

    /**
     * Remove Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        
        $lesson = Lesson::findOrFail($id);
        $lesson->chapterStudents()->where('course_id', $lesson->course_id)->forceDelete();
        $lesson->delete();

        return back()->withFlashSuccess(__('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Lesson at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        
        if ($request->input('ids')) {
            $entries = Lesson::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
        
        return response()->noContent();
    }

    /**
     * Restore Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        
        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Lesson from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }
        
        $lesson = Lesson::onlyTrashed()->findOrFail($id);

        // Delete lesson image
        if ($lesson->lesson_image) {
            $this->deleteFile($lesson->lesson_image);
            $this->deleteFile('thumb/' . $lesson->lesson_image);
        }

        // Delete all associated media files
        foreach ($lesson->media as $media) {
            $this->mediaUploadService->delete($media);
        }

        // Delete timeline
        $timelineStep = CourseTimeline::where('model_id', '=', $id)
            ->where('course_id', '=', $lesson->course->id)
            ->first();
            
        if ($timelineStep) {
            $timelineStep->delete();
        }

        $lesson->forceDelete();

        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Handle video media upload/update
     */
    protected function handleVideoMedia(Request $request, Lesson $lesson): void
    {
        if (!$request->filled('media_type')) {
            return;
        }

        $media = $lesson->mediaVideo;
        
        if ($request->media_type === 'upload') {
            if ($request->hasFile('video_file')) {
                // Delete old video if exists
                if ($media) {
                    $this->mediaUploadService->delete($media);
                }
                
                $this->mediaUploadService->uploadVideo(
                    $request->file('video_file'),
                    Lesson::class,
                    $lesson->id,
                    $lesson->title . ' - Video'
                );
            }
        } else {
            // YouTube, Vimeo, or Embed
            if (!$media) {
                $media = new Media();
            }
            
            $url = $request->video;
            $videoId = '';
            
            if ($request->media_type === 'youtube' || $request->media_type === 'vimeo') {
                $videoId = Arr::last(explode('/', $request->video));
            }

            $media->model_type = Lesson::class;
            $media->model_id = $lesson->id;
            $media->name = $lesson->title . ' - video';
            $media->url = $url;
            $media->type = $request->media_type;
            $media->file_name = $videoId;
            $media->size = 0;
            $media->save();
        }
    }

    /**
     * Handle downloadable files upload
     */
    protected function handleDownloadableFiles(Request $request, Lesson $lesson): void
    {
        if (!$request->hasFile('downloadable_files')) {
            return;
        }

        foreach ($request->file('downloadable_files') as $file) {
            $this->mediaUploadService->upload($file, Lesson::class, $lesson->id);
        }
    }

    /**
     * Upload image file
     */
    protected function uploadImage($file): string
    {
        $filename = time() . '-' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads', $filename, 'public');
        return $filename;
    }

    /**
     * Delete file from storage
     */
    protected function deleteFile(string $filename): void
    {
        $path = public_path('/storage/uploads/' . $filename);
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Update course timeline
     */
    protected function updateCourseTimeline(Lesson $lesson, int $courseId): void
    {
        if (!$lesson->published) {
            return;
        }

        $sequence = 1;
        if ($lesson->course && $lesson->course->courseTimeline->count() > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence') + 1;
        }

        $timeline = CourseTimeline::firstOrNew([
            'model_type' => Lesson::class,
            'model_id' => $lesson->id,
            'course_id' => $courseId,
        ]);

        $timeline->sequence = $sequence;
        $timeline->save();
    }
}
