<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Str;
use App\Models\NoteCategory;

class NoteCategoryController extends Controller
{
  
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $categories = NoteCategory::orderBy("id","asc");
         $categories = $categories->paginate(10);
         
         $team = NoteCategory::find($request->del);
            if($team){
                $team->delete();
                return redirect()->back()->withFlashSuccess("Category deleted successfully");
            }
        
       return view('backend.note.category.list',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = NoteCategory::orderBy("id","asc")->get();
        return view('backend.note.category.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Name',

        ]);


        $co = new NoteCategory();
        $co->name=$request->name;
        // $co->parent_id=$request->parent_id;
        $co->description=$request->description; 
        $co->status='1'; 
        $co->slug=Str::slug($request->name);
        $co->meta_title=$request->meta_title; 
        $co->meta_description=$request->meta_description; 
        $co->meta_keyword=$request->meta_keyword; 

        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/note/category/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }

        $co->save();

        return redirect()->route('admin.note.category.list')->withFlashSuccess("Category added");
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $categories = NoteCategory::where('id','!=',$id)->orderBy("id","asc")->get();
        
        $category=NoteCategory::find($id);
        if(!$category){
            return abort(404);
        }
         return view('backend.note.category.edit',compact('category','categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // dd($request->all());
         
         $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Name',

        ]);


        $co = NoteCategory::find($request->id);
        $co->name=$request->name;
        // $co->parent_id=$request->parent_id;
        $co->description=$request->description; 
        $co->status=$request->status; 
        $co->slug=$request->slug; 

        $co->meta_title=$request->meta_title; 
        $co->meta_description=$request->meta_description; 
        $co->meta_keyword=$request->meta_keyword;

        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/note/category/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }

        $co->update();

        return redirect()->route('admin.note.category.list')->withFlashSuccess("Category updated");
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}


