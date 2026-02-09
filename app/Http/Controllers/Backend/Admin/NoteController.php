<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Upload image for Editor.js ImageTool. Returns JSON format expected by Editor.js.
     */
    public function uploadImageEditorJs(Request $request)
    {
        $file = $request->file('image') ?? $request->file('file');
        if (!$file || !$file->isValid()) {
            return response()->json(['success' => 0], 422);
        }
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        if (!in_array($ext, $allowed) || $file->getSize() > 5 * 1024 * 1024) {
            return response()->json(['success' => 0], 422);
        }
        $path = $file->store('editorjs/' . date('Y') . '/' . date('m'), 'public');
        $url = asset('storage/' . $path);
        return response()->json([
            'success' => 1,
            'file' => ['url' => $url],
        ]);
    }

    /**
     * Upload image for CKEditor 4. Stores in public/uploads and returns script for CKEditor callback.
     */
    public function uploadImageCkEditor(Request $request)
    {
        $file = $request->file('upload');
        if (!$file || !$file->isValid()) {
            return response('<script>window.parent.CKEDITOR.tools.callFunction(' . (int) $request->input('CKEditorFuncNum') . ', "", "Upload failed.");</script>', 422);
        }
        $ext = strtolower($file->getClientOriginalExtension());
        $allowed = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
        if (!in_array($ext, $allowed) || $file->getSize() > 5 * 1024 * 1024) {
            return response('<script>window.parent.CKEDITOR.tools.callFunction(' . (int) $request->input('CKEditorFuncNum') . ', "", "Invalid file or size &gt; 5MB.");</script>', 422);
        }
        $dir = 'uploads/' . date('Y') . '/' . date('m');
        $path = public_path($dir);
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move($path, $filename);
        $url = asset($dir . '/' . $filename);
        $funcNum = (int) $request->input('CKEditorFuncNum');
        return response('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction(' . $funcNum . ', "' . addslashes($url) . '", "");</script>');
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
                return redirect()->back()->withFlashSuccess("Notes deleted successfully");
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

        return redirect()->route('admin.note.list')->withFlashSuccess("Notes added");
       
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

        return redirect()->route('admin.note.list')->withFlashSuccess("Notes updated");
       
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
