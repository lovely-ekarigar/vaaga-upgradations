<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\CourseTimeline;
use App\Models\Media;
use App\Models\Lesson;
use App\Models\Board;
use App\Models\CourseContent;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCoursesRequest;
use App\Http\Requests\Admin\UpdateCoursesRequest;
use App\Http\Controllers\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class CoursesController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if (!Gate::allows('course_access')) {
            return abort(401);
        }

        // Get category ID from query parameter (when clicking from categories list)
        $catId = $request->input('cat_id');
        $category = null;

        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $courses = Course::onlyTrashed()->ofTeacher()->get();
        } else {
            $query = Course::ofTeacher()->orderBy("id","desc");
            
            // Filter by category if cat_id is provided (include children)
            if ($catId) {
                $category = Category::find($catId);
                $categoryIds = [$catId];
                
                if ($category) {
                    // Get child categories
                    $children = Category::where('parent', $catId)->get();
                    foreach ($children as $child) {
                        $categoryIds[] = $child->id;
                        // Get grandchildren too
                        $grandchildren = Category::where('parent', $child->id)->get();
                        foreach ($grandchildren as $gc) {
                            $categoryIds[] = $gc->id;
                        }
                    }
                }
                
                $query->whereIn('category_id', $categoryIds);
            }
            
            $courses = $query->get();
        }
        
        return view('backend.courses.index', compact('courses', 'category'));
    }

    
    public function lessonSortOrder($id,$order){

        $cc= Lesson::find($id);
        $cc->position = $order;
        $cc->update();

        return response()->json(["success"=>true]);

    }

    public function sortOrder(Request $request){

        $course = Course::find($request->cid);
        if($course){
            $course->sort_order = $request->order;
            $course->update();
        }
    }


    public function contentSortOrder($id,$order){

        $cc= CourseContent::find($id);
        $cc->sort_order = $order;
        $cc->update();

        return response()->json(["success"=>true]);

    }

    public function listContent(Request $request){

  if (!Gate::allows('lesson_access')) {
            return abort(401);
        }

        $contents=array();
        if($request->course_id){
            $contents=CourseContent::where("course_id",$request->course_id)->get();
        }
        $courses = Course::has('category')->ofTeacher()->orderBy("sort_order","asc")->pluck('title', 'id')->prepend('Please select', '');

return view('backend.courses.content', compact('courses','contents'));
    }
public function createContent(Request $request){

  if (!Gate::allows('lesson_access')) {
            return abort(401);
        }
        $courses = Course::has('category')->ofTeacher()->orderBy("sort_order","asc")->pluck('title', 'id')->prepend('Please select', '');

return view('backend.courses.content_create', compact('courses'));
    }
    public function deleteContent(Request $request){
        if($request->course_id){
            $cc=CourseContent::find($request->course_id);
            $cc->delete();

        }
          return redirect()->back()->withFlashSuccess("Course content deleted");
    }

    public function updateContent(Request $request){

            $content = CourseContent::find($request->content_id);
            if($content){
                $content->title = $request->content_data;
                $content->update();
            }
            return redirect()->back()->withFlashSuccess("Course content updated");
    }

    public function saveContent(Request $request){
  if (!Gate::allows('lesson_access')) {
            return abort(401);
        }

if($request->course_id){

if($request->name){

    $alc = CourseContent::where('title',trim($request->name))->where('course_id',$request->course_id)->first();
if($alc){
  return redirect()->route('admin.content.index',['course_id'=>$request->course_id])->withFlashDanger("Course content already exist");
}
$cc=new CourseContent();
$cc->title=trim($request->name);
$cc->course_id=$request->course_id;
$cc->save();

  return redirect()->route('admin.content.index',['course_id'=>$request->course_id])->withFlashSuccess("Course content added");
}else{

    return redirect()->back()->withFlashDanger("Kindly enter name");
}
}else{
    return redirect()->back()->withFlashDanger("Kindly select course");
}

    }
    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $courses = "";

        if (request('show_deleted') == 1) {
            if (!Gate::allows('course_delete')) {
                return abort(401);
            }
            $courses = Course::onlyTrashed()
                ->whereHas('category')
                ->ofTeacher()->orderBy('sort_order', 'asc')->get();

        } else if (request('teacher_id') != "") {
            $id = request('teacher_id');
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->whereHas('teachers', function ($q) use ($id) {
                    $q->where('course_user.user_id', '=', $id);
                })->orderBy('sort_order', 'asc')->get();
        } else if (request('cat_id') != "") {
            $id = request('cat_id');
            
            // Get this category and all its children
            $category = Category::find($id);
            $categoryIds = [$id];
            
            if ($category) {
                // Get child categories
                $children = Category::where('parent', $id)->get();
                foreach ($children as $child) {
                    $categoryIds[] = $child->id;
                    // Get grandchildren too
                    $grandchildren = Category::where('parent', $child->id)->get();
                    foreach ($grandchildren as $gc) {
                        $categoryIds[] = $gc->id;
                    }
                }
            }
            
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->whereIn('category_id', $categoryIds)
                ->orderBy('sort_order', 'asc')
                ->get();
        } else {
            $courses = Course::ofTeacher()
                ->whereHas('category')
                ->orderBy('sort_order', 'asc')->get();
        }


        if (auth()->user()->can('course_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('course_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('lesson_delete')) {
            $has_delete = true;
        }

        return DataTables::of($courses)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.courses', 'label' => 'lesson', 'value' => $q->id]);
                }
                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.courses.show', ['course' => $q->id])])->render();
                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.courses.edit', ['course' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.courses.destroy', ['course' => $q->id])])
                        ->render();
                    $view .= $delete;
                }
                if($q->published == 1){
                    $type = 'action-unpublish';
                }else{
                    $type = 'action-publish';
                }

                $view .= view('backend.datatable.'.$type)
                    ->with(['route' => route('admin.courses.publish', ['id' => $q->id])])->render();
                return $view;

                

            })
            ->editColumn('teachers', function ($q) {
                $teachers = "";
                foreach ($q->teachers as $singleTeachers) {
                    $teachers .= '<span class="label label-info label-many">' . $singleTeachers->name . ' </span>';
                }
                return $teachers;
            })
            ->addColumn('lessons', function ($q) {
                $lesson = '<a href="' . route('admin.lessons.create', ['course_id' => $q->id]) . '" class="btn btn-success mb-1"><i class="fa fa-plus-circle"></i></a>  <a href="' . route('admin.lessons.index', ['course_id' => $q->id]) . '" class="btn mb-1 btn-warning text-white"><i class="fa fa-arrow-circle-right"></a>';
                return $lesson;
            })
             ->editColumn('title', function ($q) {
                $crs = new Course();
                     return $crs->getCouseNameWithCat($q->id);
                
            })
            ->editColumn('course_image', function ($q) {
                return ($q->course_image != null) ? '<img height="50px" src="' . asset('storage/uploads/' . $q->course_image) . '">' : 'N/A';
            })
            ->editColumn('status', function ($q) {
                $text = "";
                $text = ($q->published == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-dark p-1 mr-1' >" . trans('labels.backend.courses.fields.published') . "</p>" : "";
                $text .= ($q->featured == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-warning p-1 mr-1' >" . trans('labels.backend.courses.fields.featured') . "</p>" : "";
                $text .= ($q->trending == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-success p-1 mr-1' >" . trans('labels.backend.courses.fields.trending') . "</p>" : "";
                $text .= ($q->popular == 1) ? "<p class='text-white mb-1 font-weight-bold text-center bg-primary p-1 mr-1' >" . trans('labels.backend.courses.fields.popular') . "</p>" : "";
                return $text;
            })
            ->editColumn('price', function ($q) {
                if ($q->free == 1) {
                    return trans('labels.backend.courses.fields.free');
                }
                return $q->price;
            })
            ->editColumn('sort_order', function ($q) {
                
                return "<input type='number' class='form-control sort_order' style='width:80px;' data-id='".$q->id."' value='".$q->sort_order."'  />";
            })
            ->addColumn('category', function ($q) {
                return $q->category ? $q->category->name : 'N/A';
            })
            ->rawColumns(['teachers', 'lessons', 'course_image', 'actions', 'status','sort_order'])
            ->make();
    }
    

    /**
     * Show the form for creating new Course.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('course_create')) {
            return abort(401);
        }
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'id');

        $categories = Category::where('status', '=', 1)->pluck('name', 'id');
        
        $boardsx = Board::where('status','1')->get();
        $boards=[];
        $boards[] = array("id"=>"0","name"=>"");
       
        foreach($boardsx as $b){
            $boards[] = array("id"=>$b->id,"name"=>$b->name);  
        }

        $coupons = Coupon::where('expires_at','>=',date('Y-m-d'))->orWhere("expires_at",null)->where('status','1')->get();
       

        return view('backend.courses.create', compact('teachers', 'categories','boards','coupons'));
    }

    /**
     * Store a newly created Course in storage.
     *
     * @param  \App\Http\Requests\StoreCoursesRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCoursesRequest $request)
    {

        // dd($request->all());
        if (!Gate::allows('course_create')) {
            return abort(401);
        }

        $request->all();

        $request = $this->saveFiles($request);

        $course = Course::create($request->all());

        //Saving  videos
        if ($request->media_type != "") {
            $model_type = Course::class;
            $model_id = $course->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $course->title . ' - video';

            if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                $video = $request->video;
                $url = $video;
                $video_id = Arr::last(explode('/', $request->video));
                $media = Media::where('url', $video_id)
                    ->where('type', '=', $request->media_type)
                    ->where('model_type', '=', 'App\Models\Course')
                    ->where('model_id', '=', $course->id)
                    ->first();
                $size = 0;

            } elseif ($request->media_type == 'upload') {
                if (\Illuminate\Support\Facades\Request::hasFile('video_file')) {
                    $file = \Illuminate\Support\Facades\Request::file('video_file');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

                    $video_id = $filename;
                    $url = asset('storage/uploads/' . $filename);

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Lesson')
                        ->where('model_id', '=', $course->id)
                        ->first();
                }
            } else if ($request->media_type == 'embed') {
                $url = $request->video;
                $filename = $course->title . ' - video';
            }

            if ($media == null) {
                $media = new Media();
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }
        }

$cat = Category::find($request->category_id);
        if (($request->slug == "") || $request->slug == null) {
            $course->slug = Str::slug($cat->slug." ".$request->title);
            $course->save();
        }
        if ((int)$request->price == 0) {
            $course->price = NULL;
            $course->save();
        }
$course->type=$request->type;
$course->elevel=$request->elevel;
$course->pre_requisite=$request->pre_requisite;
$course->board_id=$request->boards_id;
$course->coupon_id=$request->coupon_id; 
$course->coupon_id_full_price=$request->coupon_id_full_price; 
$course->coupon_id_quarterly_price=$request->coupon_id_quarterly_price; 
$course->coupon_id_monthly_price=$request->coupon_id_monthly_price; 
$course->duration_text=$request->duration_text; 

$course->full_price=$request->full_price; 
$course->quarterly_price=$request->quarterly_price; 
$course->monthly_price=$request->monthly_price; 
$course->regular_monthly_1=$request->regular_monthly_1;
$course->monthly_price=$request->monthly_price; 
$course->duration=$request->duration; 
$course->save();

        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $course->teachers()->sync($teachers);


        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        

        if (!Gate::allows('course_edit')) {
            return abort(401);
        }
        $teachers = \App\Models\Auth\User::whereHas('roles', function ($q) {
            $q->where('role_id', 2);
        })->get()->pluck('name', 'id');
        $categories = Category::where('status', '=', 1)->pluck('name', 'id');

 $boardsx = Board::where('status','1')->get();
        $boards=[];
        $boards[] = array("id"=>"0","name"=>"");
       
        foreach($boardsx as $b){
            $boards[] = array("id"=>$b->id,"name"=>$b->name);  
        }
        $course = Course::findOrFail($id);
         $coupons = Coupon::where('expires_at','>=',date('Y-m-d'))->orWhere("expires_at",null)->where('status','1')->get();

        return view('backend.courses.edit', compact('course','boards', 'teachers', 'categories','coupons'));
    }

    /**
     * Update Course in storage.
     *
     * @param  \App\Http\Requests\UpdateCoursesRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCoursesRequest $request, $id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }
        $course = Course::findOrFail($id);
        $request = $this->saveFiles($request);

        //Saving  videos
        if ($request->media_type != "" || $request->media_type  != null) {
            if($course->mediavideo){
                $course->mediavideo->delete();
            }
            $model_type = Course::class;
            $model_id = $course->id;
            $size = 0;
            $media = '';
            $url = '';
            $video_id = '';
            $name = $course->title . ' - video';
            $media = $course->mediavideo;
            if ($media == "") {
                $media = new  Media();
            }
            if ($request->media_type != 'upload') {
                if (($request->media_type == 'youtube') || ($request->media_type == 'vimeo')) {
                    $video = $request->video;
                    $url = $video;
                    $video_id = Arr::last(explode('/', $request->video));
                    $size = 0;

                } else if ($request->media_type == 'embed') {
                    $url = $request->video;
                    $filename = $course->title . ' - video';
                }
                $media->model_type = $model_type;
                $media->model_id = $model_id;
                $media->name = $name;
                $media->url = $url;
                $media->type = $request->media_type;
                $media->file_name = $video_id;
                $media->size = 0;
                $media->save();
            }

            if ($request->media_type == 'upload') {

                if ($request->video_file != null) {

                    $media = Media::where('type', '=', $request->media_type)
                        ->where('model_type', '=', 'App\Models\Course')
                        ->where('model_id', '=', $course->id)
                        ->first();

                    if ($media == null) {
                        $media = new Media();
                    }
                    $media->model_type = $model_type;
                    $media->model_id = $model_id;
                    $media->name = $name;
                    $media->url = url('storage/uploads/'.$request->video_file);
                    $media->type = $request->media_type;
                    $media->file_name = $request->video_file;
                    $media->size = 0;
                    $media->save();

                }
            }
        }


          // Generate slug if not provided
        $sclug= "";
        if($request->boards_id){
            $board = Board::find($request->boards_id);
            $sclug = $board->slug;

        }
        $cat = Category::find($request->category_id);
        $sclug = $cat->slug;

        // Prepare update data
        $updateData = $request->all();

        // If slug is empty or null, generate it from category/board and title
        if (empty($request->slug)) {
            $updateData['slug'] = Str::slug($sclug."-".$request->title);
        }

        $course->update($updateData);
        if ((int)$request->price == 0) {
            $course->price = NULL;
            $course->save();
        }
        $course->type=$request->type;
        $course->elevel=$request->elevel;
        $course->pre_requisite=$request->pre_requisite;
        $course->board_id=$request->boards_id;

$course->coupon_id=$request->coupon_id; 
$course->coupon_id_full_price=$request->coupon_id_full_price; 
$course->coupon_id_quarterly_price=$request->coupon_id_quarterly_price; 
$course->coupon_id_monthly_price=$request->coupon_id_monthly_price; 
$course->duration_text=$request->duration_text; 
        $course->full_price=$request->full_price; 
$course->monthly_price=$request->monthly_price; 
$course->quarterly_price=$request->quarterly_price; 
        $course->regular_monthly=$request->regular_monthly;
$course->regular_monthly_1=$request->regular_monthly_1;
$course->duration=$request->duration; 
        $course->save();

        $teachers = \Auth::user()->isAdmin() ? array_filter((array)$request->input('teachers')) : [\Auth::user()->id];
        $course->teachers()->sync($teachers);

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Course.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('course_view')) {
            return abort(401);
        }
        $teachers = User::get()->pluck('name', 'id');
        $lessons = \App\Models\Lesson::where('course_id', $id)->get();
        $tests = \App\Models\Test::where('course_id', $id)->get();

        $course = Course::findOrFail($id);
        $courseTimeline = $course->courseTimeline()->orderBy('sequence', 'asc')->get();

        return view('backend.courses.show', compact('course', 'lessons', 'tests', 'courseTimeline'));
    }


    /**
     * Remove Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::findOrFail($id);
        if ($course->students->count() >= 1) {
            return redirect()->route('admin.courses.index')->withFlashDanger(trans('alerts.backend.general.delete_warning'));
        } else {
            $course->delete();
        }


        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Course at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Course::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->restore();

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Course from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        $course = Course::onlyTrashed()->findOrFail($id);
        $course->forceDelete();

        return redirect()->route('admin.courses.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Permanently save Sequence from storage.
     *
     * @param  Request
     */
    public function saveSequence(Request $request)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        foreach ($request->list as $item) {
            $courseTimeline = CourseTimeline::find($item['id']);
            $courseTimeline->sequence = $item['sequence'];
            $courseTimeline->save();
        }

        return 'success';
    }


    /**
     * Publish / Unpublish courses
     *
     * @param  Request
     */
    public function publish($id)
    {
        if (!Gate::allows('course_edit')) {
            return abort(401);
        }

        $course = Course::findOrFail($id);
        if ($course->published == 1) {
            $course->published = 0;
        } else {
            $course->published = 1;
        }
        $course->save();

        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }
}
