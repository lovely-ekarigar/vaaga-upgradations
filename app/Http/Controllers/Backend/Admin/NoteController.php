<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Auth;
use Illuminate\Support\Str;
use App\Models\NoteCategory;
use App\Models\Note;
use App\Models\Category;
use App\Models\Course;

class NoteController extends Controller
{
    public function imageUpload(Request $request)
    {
         $file = $request->file('file');
    $ext = strtolower($file->getClientOriginalExtension());
    $size = $file->getSize();
    if($ext == 'png' || $ext == 'jpg' || $ext == 'jpeg'|| $ext == 'gif' || $ext == 'webp' || $ext == 'svg'){
        if($size <= 500*1024){ 
            $fname = time()."_".$file->getClientOriginalName();
        $destinationPath = 'uploads/'.date("Y")."/".date("m")."/".date("d");
        $file->move($destinationPath,$fname);
        return response()->json([
            "success"=>true,
            "location"=>"https://www.vaagaacademy.com/".$destinationPath."/".$fname
        ]);
    }else{
        return response()->json([
            "success"=>false,
            "status" => "Max file size upload limit is 500KB",
	    "errorcode" => "405"
        ]);  
    }
    }else{
        return response()->json([
            "success"=>false,
            "status" => "Only png, jpeg, gif, svg & webp files are allowed",
	    "errorcode" => "406"
        ]);
    }
    }
    
  public function categories()
  {
      $categories = NoteCategory::where('status','1')->orderBy("id","asc")->get();
      
   
       $notes =Note::with('category')->orderBy("id","asc")->get();
       return view('frontend.notes.category',compact('categories','notes'));
  }
  
      public function notes($slug)
    {
        
         $category = NoteCategory::where('slug',$slug)->first();
         if(!$category){
            return abort(404);
        }
        
        $categories = NoteCategory::where('parent_id',$category->id)->orderBy("id","asc")->get();
         $notes = Note::with('category')->where('category_id',$category->id)->get();
         
          $categoriesx = NoteCategory::where("id","!=",$category->id)->where('status','1')->orderBy("id","desc")->limit(5)->get();

     
        return view('frontend.notes.note',compact('notes','category','categories','categoriesx'));
    }
    
    public function download($id){
        
        $note=Note::find($id);
        if(!$note){
            return abort(404);
        }
        
      $file_path = public_path($note->file);
    return response()->download($file_path);

        
    }
    
      public function noteDeatails($slug)
    {
       
       
         $note =Note::with('category')->where('slug',$slug)->first();
         if(!$note){
            return abort(404);
        }
        
         $course = Course::where("id",$note->course_id)->first();
        
        if(!$course){
            return abort(404);
        }
        
       
        
        
         $notesx = Note::where("id","!=",$note->id)->orderBy("id","desc")->limit(5)->get();
         
        return view('frontend.notes.notedetails',compact('note','course'));
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $notes = Note::with('category')->orderBy("id","desc");
         $notes = $notes->paginate(10);
         
         $team = Note::find($request->del);
            if($team){
                $team->delete();
                return redirect()->back()->withFlashSuccess("Note deleted successfully");
            }
            
       return view('backend.note.list',compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $courses = Course::get();
        
        $categories = NoteCategory::orderBy("id","asc")->get();
        return view('backend.note.create',compact('categories','courses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //   dd($request->all());
        $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Title',

        ]);


        $co = new Note();
        $co->name=$request->name;
        $co->category_id=$request->category_id;
        $co->course_id=$request->course_id;
        $co->description=$request->description; 
        $co->meta_title=$request->meta_title; 
        $co->meta_description=$request->meta_description; 
        $co->meta_keyword=$request->meta_keyword; 
        $co->slug=Str::slug($request->name);

        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/note/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }
        
        $image = $request->file('file');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = $request->name.'_'.time().'.'.$extension;
        
        $destinationPath = 'upload/note/file/';
        
        $image->move($destinationPath, $filename);
        
        $co->file =$destinationPath.$filename;

        }

        $co->save();

        return redirect()->route('admin.note.list')->withFlashSuccess("Note added");
       
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
        $courses = Course::get();
         $categories = NoteCategory::orderBy("id","asc")->get();
         $note=Note::find($id);
        if(!$note){
            return abort(404);
        }
         return view('backend.note.edit',compact('note','categories','courses'));
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
        //   dd($request->all());
         
           $this->validate($request,[
            'name' => 'required|max:450',

        ],[
                'name.required' => 'Kindly Enter Team Name',

        ]);


        $co =Note::find($request->id);
        $co->name=$request->name;
        $co->category_id=$request->category_id;
        $co->course_id=$request->course_id;
        $co->description=$request->description; 
        $co->meta_title=$request->meta_title; 
        $co->meta_description=$request->meta_description; 
        $co->meta_keyword=$request->meta_keyword;
        $co->slug=$request->slug; 
        
        $image = $request->file('image');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = time().'.'.$extension;
        
        $destinationPath = 'upload/note/';
        
        $image->move($destinationPath, $filename);
        
        $co->image =$destinationPath.$filename;

        }
        
        $image = $request->file('file');
        if($image){
            
        $filename = $image->getClientOriginalName();
        
        $extension = $image->getClientOriginalExtension(); 
        
        $filename = $request->name.'_'.time().'.'.$extension;
        
        $destinationPath = 'upload/note/file/';
        
        $image->move($destinationPath, $filename);
        
        $co->file =$destinationPath.$filename;

        }

        $co->update();

        return redirect()->route('admin.note.list')->withFlashSuccess("Note updated");
       
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


