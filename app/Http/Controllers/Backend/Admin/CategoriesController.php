<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreCategoriesRequest;
use App\Http\Requests\Admin\UpdateCategoriesRequest;
use App\Models\Category;
use App\Models\Board;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{

    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *c
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Gate::allows('category_access')) {
            return abort(401);
        }
        $parent=0;

        if($request->parent){
            $parent=$request->parent;
        }
        $cats=[];
        if($parent!=0){
            $cat=Category::find($parent);
            $cats[] = $cat;
            if($cat->parent!=0){
                 $cat=Category::find($cat->parent);
            $cats[] = $cat;
            }
             if($cat->parent!=0){
                 $cat=Category::find($cat->parent);
            $cats[] = $cat;
            }
             if($cat->parent!=0){
                 $cat=Category::find($cat->parent);
            $cats[] = $cat;
            }
        }

        // dd($cats);
        $cats = array_reverse($cats);


        if (request('show_deleted') == 1) {
            if (!Gate::allows('category_delete')) {
                return abort(401);
            }
            $categories = Category::onlyTrashed()->get();
        } else {
            $categories = Category::all();
        }
// dd($categories);
        return view('backend.categories.index', compact('categories','parent','cats'));
    }

    public function updateSort(Request $request){

        $cat = Category::find($request->cid);
        if($cat){
            $cat->sort_order = $request->sort;
            $cat->update();
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
        $categories = "";


        if (request('show_deleted') == 1) {
            if (!Gate::allows('category_delete')) {
                return abort(401);
            }
            $categories = Category::onlyTrashed()->orderBy('created_at', 'desc')->get();
        } else {
            $categories = Category::where('parent',$request->parent)->orderBy('created_at', 'desc')->get();
        }

        if (auth()->user()->can('category_view')) {
            $has_view = true;
        }
        if (auth()->user()->can('category_edit')) {
            $has_edit = true;
        }
        if (auth()->user()->can('category_delete')) {
            $has_delete = true;
        }

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                $allow_delete = false;

                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.categories', 'label' => 'category', 'value' => $q->id]);
                }
//                if ($has_view) {
//                    $view = view('backend.datatable.action-view')
//                        ->with(['route' => route('admin.categories.show', ['category' => $q->id])])->render();
//                }
                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.categories.edit', ['category' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $data = $q->courses->count() + $q->blogs->count();
                    if($data == 0){
                        $allow_delete = true;
                    }
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.categories.destroy', ['category' => $q->id]),'allow_delete'=> $allow_delete])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a class="btn btn-warning mb-1" href="' . route('admin.courses.index', ['cat_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';


                return $view;

            })
            ->editColumn('icon', function ($q) {
                if ($q->icon != "") {
                    return '<i style="font-size:40px;" class="'.$q->icon.'"></i>';
                }else{
                    return 'N/A';
                }
            })
            ->editColumn('sort_order', function ($q) {
                return '<input type="number" class="form-control sort_order_field" data-id="'.$q->id.'" value="'.$q->sort_order.'"  />';
               
            })
            ->editColumn('courses', function ($q) {
                return $q->courses->count();
            })
             ->editColumn('name', function ($q) {
                $board = Board::find($q->board_id);
                if($board){
                return "<a href='?parent=".$q->id."'>".$board->name." ".$q->name."</a>";
            }else{
                return "<a href='?parent=".$q->id."'>".$q->name."</a>"; 
            }
            })
            ->editColumn('blogs', function ($q) {
                return $q->blogs->count();
            })
            ->editColumn('status', function ($q) {
                return ($q->status == 1) ? "Enabled" : "Disabled";
            })
            ->rawColumns(['actions', 'icon','name','sort_order'])
            ->make();
    }

    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Gate::allows('category_create')) {
            return abort(401);
        }
        $courses = \App\Models\Course::ofTeacher()->get();
        $courses_ids = $courses->pluck('id');
        $courses = $courses->pluck('title', 'id')->prepend('Please select', '');
        $lessons = \App\Models\Lesson::whereIn('course_id', $courses_ids)->get()->pluck('title', 'id')->prepend('Please select', '');
        $cats=Category::all();
        $boardsx = Board::where('status','1')->get();
        $boards=[];
        $boards[] = array("id"=>"0","name"=>"");
       
        foreach($boardsx as $b){
            $boards[] = array("id"=>$b->id,"name"=>$b->name);  
        }
        return view('backend.categories.create', compact('courses', 'lessons','cats','boards'));
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param  \App\Http\Requests\StoreCategorysRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoriesRequest $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        if (!Gate::allows('category_create')) {
            return abort(401);
        }
        $category = Category::where('slug','=',str_slug($request->name))->first();
        if($category == null){
            $category = new  Category();
        }
        if($request->parent!=0){
            $cat = Category::find($request->parent);
             $category->slug = str_slug($request->name);
            if($cat){
                 $category->slug = $cat->slug.'-'.str_slug($request->name);
            }
           
        }else{
           $category->slug = str_slug($request->name); 
        }
        $category->name = $request->name;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keyword = $request->meta_keyword;
         $category->board_id = $request->boards_id;
        
        $category->icon = $request->icon;
         $category->parent = $request->parent;
        
       if($request->hasFile('course_image')){
 $file = \Illuminate\Support\Facades\Request::file('course_image');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $category->course_image = $filename ;

          
        }
        
         if($request->boards_id!=0){
                $board = Board::find($request->boards_id);
                    $alc = explode('-', $category->slug);
                    $first = $alc[0];
                    $findBoard = Board::where('slug',$first)->first();
                    if($findBoard){
$category->slug = $category->slug;
                    }else{
                        $category->slug = $board->slug.'-'.$category->slug;
                    }
             
        }else{
            $category->slug = $category->slug;
        }
        
        $category->board_id=$request->boards_id;
$category->save();
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Category.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Gate::allows('category_edit')) {
            return abort(401);
        }
        $courses = \App\Models\Course::ofTeacher()->get();
        $courses_ids = $courses->pluck('id');
        $courses = $courses->pluck('title', 'id')->prepend('Please select', '');
        $lessons = \App\Models\Lesson::whereIn('course_id', $courses_ids)->get()->pluck('title', 'id')->prepend('Please select', '');

        $category = Category::findOrFail($id);
        // dd($category);
 $cats=Category::all();
 $boardsx = Board::where('status','1')->get();
        $boards=[];
        $boards[] = array("id"=>"0","name"=>"");
       
        foreach($boardsx as $b){
            $boards[] = array("id"=>$b->id,"name"=>$b->name);  
        }
        return view('backend.categories.edit', compact('category', 'courses', 'lessons','cats','boards'));
    }

    /**
     * Update Category in storage.
     *
     * @param  \App\Http\Requests\UpdateCategorysRequest $request 
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoriesRequest $request, $id)
    {
        if (!Gate::allows('category_edit')) {
            return abort(401);
        }

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->meta_title = $request->meta_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keyword = $request->meta_keyword;
        $category->status = $request->status;
        if($request->parent!=0){
            $cat = Category::find($request->parent);
             $category->slug = str_slug($request->name);
            if($cat){
                 $category->slug = $cat->slug.'-'.str_slug($request->name);
            }
           
        }else{
           $category->slug = str_slug($request->name); 
        }
         $category->board_id = $request->boards_id;
        $category->icon = $request->icon;
        $category->parent = $request->parent;
         if($request->hasFile('course_image')){
 $file = \Illuminate\Support\Facades\Request::file('course_image');
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $size = $file->getSize() / 1024;
                    $path = public_path() . '/storage/uploads/';
                    $file->move($path, $filename);

       $category->course_image = $filename ;

          
        }
        
          if($request->boards_id!=0){
                $board = Board::find($request->boards_id);
                    $alc = explode('-', $category->slug);
                    $first = $alc[0];
                    $findBoard = Board::where('slug',$first)->first();
                    if($findBoard){
$category->slug = $category->slug;
                    }else{
                        $category->slug = $board->slug.'-'.$category->slug;
                    }
             
        }else{
            $category->slug = $category->slug;
        }
        
        $category->board_id=$request->boards_id;
        $category->save();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }


    /**
     * Display Category.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Gate::allows('category_view')) {
            return abort(401);
        }
        $category = Category::findOrFail($id);

        return view('backend.categories.show', compact('category'));
    }


    /**
     * Remove Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('category_delete')) {
            return abort(401);
        }
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Category at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('category_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Category::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        if (!Gate::allows('category_delete')) {
            return abort(401);
        }
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()->route('admin.categories.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {
        if (!Gate::allows('category_delete')) {
            return abort(401);
        }
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();

        return redirect()->route('admin.categories.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
}
