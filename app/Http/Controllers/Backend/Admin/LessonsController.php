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
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class LessonsController extends Controller
{
    use FileUploadTrait;

    protected MediaUploadService $mediaUploadService;

    public function __construct(MediaUploadService $mediaUploadService)
    {
        $this->mediaUploadService = $mediaUploadService;
    }

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

    public function getData(Request $request)
    {
        $has_view   = false;
        $has_delete = false;
        $has_edit   = false;

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

        $has_view   = auth()->user()->can('lesson_view');
        $has_edit   = auth()->user()->can('lesson_edit');
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

   public function store(StoreLessonsRequest $request)
{
    if (!Gate::allows('lesson_create')) {
        return abort(401);
    }

    $lesson = Lesson::create(
        $request->except('downloadable_files', 'lesson_image')
        + ['position' => Lesson::where('course_id', $request->course_id)->max('position') + 1]
    );

    // ── STEP 1: Video ──
    if ($request->media_type == 'upload' && $request->hasFile('video_file')) {
        $file      = $request->file('video_file');
        $name      = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename  = $this->generateFilename(public_path('uploads'), $name, $extension); // ✅
        $size      = $file->getSize() / 1024;
        $url       = asset('uploads/' . $filename);
        $file->move(public_path('uploads/'), $filename); // ✅ path fix

        $media = Media::where([
            ['type', $request->media_type],
            ['model_type', Lesson::class],
            ['model_id', $lesson->id],
        ])->first();

        if (!$media) {
            Media::create([
                'model_type' => Lesson::class,
                'model_id'   => $lesson->id,
                'name'       => $lesson->title . ' - video',
                'url'        => $url,
                'type'       => $request->media_type,
                'file_name'  => $filename,
                'size'       => $size,
            ]);
        }
    }

    // ── STEP 2: PDFs + Downloadable ──
    $request = $this->saveAllFiles($request, 'downloadable_files', Lesson::class, $lesson);
    $request = $this->saveAllFiles($request, 'add_pdf', Lesson::class, $lesson);

    // ── STEP 3: YouTube / Vimeo / Embed ──
    if (!empty($request->media_type) && $request->media_type != 'upload') {
        $name = $lesson->title . ' - video';

        if (in_array($request->media_type, ['youtube', 'vimeo'])) {
            $videos = array_filter((array)$request->video, fn($v) => trim($v) !== '');
            foreach ($videos as $video) {
                $video_id = collect(explode('/', $video))->last();
                $exists   = Media::where([
                    ['url', $video_id],
                    ['type', $request->media_type],
                    ['model_type', Lesson::class],
                    ['model_id', $lesson->id],
                ])->first();
                if (!$exists) {
                    Media::create([
                        'model_type' => Lesson::class,
                        'model_id'   => $lesson->id,
                        'name'       => $name,
                        'url'        => $video,
                        'type'       => $request->media_type,
                        'file_name'  => $video_id,
                        'size'       => 0,
                    ]);
                }
            }
        } elseif ($request->media_type == 'embed') {
            Media::create([
                'model_type' => Lesson::class,
                'model_id'   => $lesson->id,
                'name'       => $name,
                'url'        => $request->video,
                'type'       => 'embed',
                'file_name'  => '',
                'size'       => 0,
            ]);
        }
    }

    // ── STEP 4: Lesson fields save ──
    $lesson->content_id  = trim($request->content_id);
    $lesson->free_lesson = (int)$request->free_lesson === 1 ? 1 : 0;
    $lesson->duration    = $request->duration;
    if (empty($request->slug)) {
        $lesson->slug = Str::slug($request->title);
    }
    $lesson->save();

    $sequence = $lesson->course->courseTimeline->count() > 0
        ? $lesson->course->courseTimeline->max('sequence') + 1
        : 1;

    if ($lesson->published == 1) {
        $timeline           = CourseTimeline::firstOrNew([
            'model_type' => Lesson::class,
            'model_id'   => $lesson->id,
            'course_id'  => $request->course_id,
        ]);
        $timeline->sequence = $sequence;
        $timeline->save();
    }

    return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id])
        ->withFlashSuccess(__('alerts.backend.general.created'));
}

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

 public function update(UpdateLessonsRequest $request, $id)
{
    if (!Gate::allows('lesson_edit')) return abort(401);

    $lesson     = Lesson::findOrFail($id);
    $uploadPath = public_path('uploads/');
    if (!is_dir($uploadPath)) mkdir($uploadPath, 0777, true);

    $execEnabled = function_exists('exec')
        && !in_array('exec', array_map('trim', explode(',', ini_get('disable_functions'))));

    $addPdfFiles       = $request->hasFile('add_pdf')
                            ? (array)$request->file('add_pdf')
                            : [];
    $downloadableFiles = $request->hasFile('downloadable_files')
                            ? (array)$request->file('downloadable_files')
                            : [];
    $videoFile         = $request->hasFile('video_file')
                            ? $request->file('video_file')
                            : null;

    // ── STEP 2: Video ──
    if ($request->media_type == 'upload' && $videoFile && $videoFile->isValid()) {
        $name      = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $videoFile->getClientOriginalExtension();
        $filename  = $this->generateFilename(public_path('uploads'), $name, $extension);
        $size      = $videoFile->getSize() / 1024;
        $videoFile->move($uploadPath, $filename);

        Media::updateOrCreate(
            ['model_type' => Lesson::class, 'model_id' => $lesson->id, 'type' => 'upload'],
            [
                'name'      => $lesson->title . ' - video',
                'url'       => asset('uploads/' . $filename),
                'file_name' => $filename,
                'size'      => $size,
            ]
        );
    }

    // ── STEP 3: PDF files ──
    foreach ($addPdfFiles as $file) {
        if (!$file || !$file->isValid()) continue;

        $ext      = strtolower($file->getClientOriginalExtension());
        $name     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->generateFilename(public_path('uploads'), $name, $ext);
        $file->move($uploadPath, $filename);

        if (in_array($ext, ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx']) && $execEnabled) {
            $pdfName = pathinfo($filename, PATHINFO_FILENAME) . '.pdf';
            exec('/bin/libreoffice --headless --convert-to pdf '
                . escapeshellarg($uploadPath . $filename)
                . ' --outdir '
                . escapeshellarg($uploadPath));
            if (file_exists($uploadPath . $pdfName)) {
                @unlink($uploadPath . $filename);
                $filename = $pdfName;
            }
        }

        $size = filesize($uploadPath . $filename) / 1024;
        Media::create([
            'model_type' => Lesson::class,
            'model_id'   => $lesson->id,
            'name'       => $filename,
            'url'        => asset('uploads/' . $filename),
            'type'       => 'lesson_pdf',
            'file_name'  => $filename,
            'size'       => $size,
        ]);
    }

    // ── STEP 4: Downloadable files ──
    foreach ($downloadableFiles as $file) {
        if (!$file || !$file->isValid()) continue;

        $ext      = strtolower($file->getClientOriginalExtension());
        $name     = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->generateFilename(public_path('uploads'), $name, $ext);
        $file->move($uploadPath, $filename);

        $size = filesize($uploadPath . $filename) / 1024;
        Media::create([
            'model_type' => Lesson::class,
            'model_id'   => $lesson->id,
            'name'       => $filename,
            'url'        => asset('uploads/' . $filename),
            'type'       => 'downloadable',
            'file_name'  => $filename,
            'size'       => $size,
        ]);
    }

    // ── STEP 5: DB update ──
    $lesson->update(
        $request->except('downloadable_files', 'lesson_image', 'add_pdf', 'video_file')
    );
    $lesson->free_lesson = (int)$request->free_lesson === 1 ? 1 : 0;
    $lesson->duration    = $request->duration;
    $lesson->content_id  = trim($request->content_id);
    if (empty($request->slug)) {
        $lesson->slug = Str::slug($request->title);
    }
    $lesson->save();

    // YouTube / Vimeo
    if (!empty($request->media_type) && in_array($request->media_type, ['youtube', 'vimeo'])) {
        foreach ((array)$request->video as $video) {
            if (empty($video)) continue;
            $exists = Media::where('url', $video)
                ->where('type', $request->media_type)
                ->where('model_type', Lesson::class)
                ->where('model_id', $lesson->id)
                ->exists();
            if (!$exists) {
                Media::create([
                    'model_type' => Lesson::class,
                    'model_id'   => $lesson->id,
                    'name'       => $lesson->title . ' - video',
                    'url'        => $video,
                    'type'       => $request->media_type,
                    'file_name'  => basename($video),
                    'size'       => 0,
                ]);
            }
        }
    }

    // Delete old PDFs
    if ($request->removed_old_pdfs) {
        $documents = Media::whereIn('id', explode(',', $request->removed_old_pdfs))
            ->where(function ($q) {
                $q->where('type', 'lesson_pdf')
                  ->orWhere('type', 'application/pdf')
                  ->orWhere('type', 'application/msword')
                  ->orWhere('type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                  ->orWhere('file_name', 'like', '%.pdf')
                  ->orWhere('file_name', 'like', '%.doc')
                  ->orWhere('file_name', 'like', '%.docx');
            })
            ->where('model_id', $lesson->id)
            ->get();
        foreach ($documents as $doc) {
            $fp = public_path('uploads/' . $doc->file_name);
            if (file_exists($fp)) unlink($fp);
            $doc->delete();
        }
    }

    // Delete old videos
    if ($request->removed_old_videos) {
        Media::whereIn('id', explode(',', $request->removed_old_videos))
            ->where('model_id', $lesson->id)
            ->delete();
    }

    // Timeline
    if ((int)$request->published === 1) {
        $sequence = ($lesson->course && $lesson->course->courseTimeline->count() > 0)
            ? $lesson->course->courseTimeline->max('sequence') + 1
            : 1;
        $timeline           = CourseTimeline::firstOrNew([
            'model_type' => Lesson::class,
            'model_id'   => $lesson->id,
            'course_id'  => $request->course_id,
        ]);
        $timeline->sequence = $sequence;
        $timeline->save();
    }

    return redirect()->route('admin.lessons.index', ['course_id' => $request->course_id])
        ->withFlashSuccess(__('alerts.backend.general.updated'));
}


    public function show($id)
    {
        if (!Gate::allows('lesson_view')) {
            return abort(401);
        }

        $courses = Course::orderBy("sort_order", "asc")
            ->get()
            ->pluck('title', 'id')
            ->prepend('Please select', '');

        $tests  = Test::where('lesson_id', $id)->get();
        $lesson = Lesson::findOrFail($id);

        return view('backend.lessons.show', compact('lesson', 'tests', 'courses'));
    }

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

    public function restore($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }

        $lesson = Lesson::onlyTrashed()->findOrFail($id);
        $lesson->restore();

        return back()->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    public function perma_del($id)
    {
        if (!Gate::allows('lesson_delete')) {
            return abort(401);
        }

        $lesson = Lesson::onlyTrashed()->findOrFail($id);

        if ($lesson->lesson_image) {
            $this->deleteFile($lesson->lesson_image);
            $this->deleteFile('thumb/' . $lesson->lesson_image);
        }

        foreach ($lesson->media as $media) {
            $this->mediaUploadService->delete($media);
        }

        $timelineStep = CourseTimeline::where('model_id', '=', $id)
            ->where('course_id', '=', $lesson->course->id)
            ->first();

        if ($timelineStep) {
            $timelineStep->delete();
        }

        $lesson->forceDelete();

        return back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    protected function handleVideoMedia(Request $request, Lesson $lesson): void
    {
        if (!$request->filled('media_type')) return;

        $media = $lesson->mediaVideo;

        if ($request->media_type === 'upload') {
            if ($request->hasFile('video_file')) {
                if ($media) $this->mediaUploadService->delete($media);
                $this->mediaUploadService->uploadVideo(
                    $request->file('video_file'),
                    Lesson::class,
                    $lesson->id,
                    $lesson->title . ' - Video'
                );
            }
        } else {
            if (!$media) $media = new Media();
            $videoId = '';
            if (in_array($request->media_type, ['youtube', 'vimeo'])) {
                $videoId = Arr::last(explode('/', $request->video));
            }
            $media->model_type = Lesson::class;
            $media->model_id   = $lesson->id;
            $media->name       = $lesson->title . ' - video';
            $media->url        = $request->video;
            $media->type       = $request->media_type;
            $media->file_name  = $videoId;
            $media->size       = 0;
            $media->save();
        }
    }

    protected function handleDownloadableFiles(Request $request, Lesson $lesson): void
    {
        if (!$request->hasFile('downloadable_files')) return;
        foreach ($request->file('downloadable_files') as $file) {
            $this->mediaUploadService->upload($file, Lesson::class, $lesson->id);
        }
    }

    protected function uploadImage($file): string
    {
        $filename = time() . '-' . Str::slug($file->getClientOriginalName()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('uploads', $filename, 'public');
        return $filename;
    }

    protected function deleteFile(string $filename): void
    {
        $path = public_path('/storage/uploads/' . $filename);
        if (File::exists($path)) File::delete($path);
    }

    protected function updateCourseTimeline(Lesson $lesson, int $courseId): void
    {
        if (!$lesson->published) return;

        $sequence = 1;
        if ($lesson->course && $lesson->course->courseTimeline->count() > 0) {
            $sequence = $lesson->course->courseTimeline->max('sequence') + 1;
        }

        $timeline = CourseTimeline::firstOrNew([
            'model_type' => Lesson::class,
            'model_id'   => $lesson->id,
            'course_id'  => $courseId,
        ]);
        $timeline->sequence = $sequence;
        $timeline->save();
    }
}
