<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Locale;
use App\Models\Config;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Category;
use App\Models\Board;
use App\Models\Auth\User;
use App\Models\TeacherBatch;
use App\Models\Elearn;
use App\Models\DemoRequest;
use App\Models\DemoFeedback;
use App\Models\StudentFeedbackQuestion;
use App\Models\TeacherFeedbackQuestion;
 
use App\Models\Recording;
use App\Models\StudentTeacherBatch;
use App\Models\OauthClient;
use App\Models\DemoHistory;
use Illuminate\Http\Request;
use App\Mail\Frontend\Demo\DemoStudentEmail;
use App\Mail\Frontend\Demo\DemoTeacherEmail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Mail\FeedbackEmail;
use URL;
use Mail;
use Auth;
class DemoController extends Controller
{
    
    
    public function indexTeacher(){
        if(auth()->user()->hasRole('teacher')){
       
       $users = User::where('id',auth()->user()->id)->first();

        $demo_request = DemoRequest::with(['coursed','user'])->where('teacher_id',auth()->user()->id)->get();
        //   dd($demo_request[0]);

           $arr = [];
        //   dd(Course::where("id","40")->first());

           foreach($demo_request as $drr){
            $cour = Course::find($drr->course_id);
           $arr[] = $drr->course_id;
           }
        //   dd($arr);
      
        $locale_full_name = \App\Models\Locale::where('short_name', app()->getLocale())->value('name') ?? 'English';
        return view('backend.demo.index', compact('users','demo_request', 'locale_full_name'));
       
    }
        
    }
   public function index(){

    if(auth()->user()->hasRole('teacher')){
       
       $users = User::where('id',auth()->user()->id)->first();

        $demo_request = DemoRequest::with(['coursed','user'])->where('teacher_id',auth()->user()->id)->get();
        //   dd($demo_request[0]);

           $arr = [];
        //   dd(Course::where("id","40")->first());

           foreach($demo_request as $drr){
            $cour = Course::find($drr->course_id);
           $arr[] = $drr->course_id;
           }
        //   dd($arr);
        
        $locale_full_name = \App\Models\Locale::where('short_name', app()->getLocale())->value('name') ?? 'English';
        return view('backend.demo.index', compact('users','demo_request', 'locale_full_name'));
       
    }elseif(auth()->user()->hasRole('administrator')){

        $users = User::where('active',true)->role('teacher')->get();

        $locale_full_name = \App\Models\Locale::where('short_name', app()->getLocale())->value('name') ?? 'English';
        return view('backend.demo.index', compact('users', 'locale_full_name'));
       
    }

   
       
   } 
   
   
   public function statusUpdate(Request $request){
      
      $demo = DemoRequest::find($request->demo_id); 
      if($demo->teacher_id != Auth::user()->id){
           return redirect()->back()->withErrors("Unbale to perform this action");
      }
      $demo->demo_status = $request->status;
      $demo->remarks = $request->teacher_remarks;
      $demo->update();
       
       return redirect()->back()->withSuccess("Status updated succesfully");
   }

   public function demoFeedback(Request $request,$id)
   {

        $demo = DemoRequest::find($id);

        $student_questions = StudentFeedbackQuestion::all();
        $teacher_questions = TeacherFeedbackQuestion::all();
            $sqlist=[];
            $tqlist=[];

            $userType='student';
        $is_teacher='0';
        if($request->type){
$userType=$request->type;
        }
        if($userType=='teacher'){ 
            $is_teacher='1';
        }

         foreach($student_questions as $sq){
            $sq->avg_rating = DemoFeedback::where("question_id",$sq->id)->where("demo_id",$id)->where('is_teacher','0')->avg("review");
            $sqlist[] = $sq;
        }

  foreach($teacher_questions as $sq){
            $sq->avg_rating = DemoFeedback::where("question_id",$sq->id)->where("demo_id",$id)->where('is_teacher','1')->avg("review");
            $tqlist[] = $sq;
        }

        $feedbacks=[];
        if($request->qid){

            $feedbacks = DemoFeedback::with('user')->where('question_id',$request->qid)->where('demo_id',$id)->where('is_teacher',$is_teacher)->orderBy('id','desc')->get();

        }


       return view('backend.feedback.demo',compact('sqlist','tqlist','userType','id','feedbacks','demo'));
   }

   public function senddemoEmail(Request $request, $id)
   {
      $stb = DemoRequest::where('id',$id)->get();

foreach($stb as $s){

    $user = User::find($s->user_id);
    if($user){
Mail::to($user)->send(new FeedbackEmail($user,$id));
    }
}

$userx = User::find($stb->teacher_id);
if($userx){
Mail::to($user)->send(new FeedbackEmail($userx,$id));
}


    return redirect()->back()->withFlashSuccess("Feedback email has been sent to Student & Tutor");
   }
   
   public function demoHistory($id)
   {
       $demo_history_list = DemoHistory::where('demo_id',$id)->get();

        $list=array();

        foreach($demo_history_list as $demo_history){

            $demo_history["demo_request"]= DemoRequest::find($demo_history->demo_id);

            $demo_history["teacher"]= User::find($demo_history->teacher_id);

            $demo_history["course"]= Course::where("id",$demo_history["demo_request"]->course_id)->first();

            $list[]=$demo_history;
         
        }

        return view('backend.demo.history',compact('list'));
   }
   
   
public function getDataTeacher(Request $request)
    {
        $contacts = "";
        $contacts = DemoRequest::where('teacher_id',auth()->user()->id)->orderBy('id', 'desc')->get();
        
        // dd($contacts);

        return DataTables::of($contacts)
            ->addIndexColumn()
            ->editColumn('created_at', function ($q) {
               return $q->created_at->format('d M, Y');
            })
            ->addColumn('course', function($q){
                $course = Course::find($q->course_id);
                
                if( $course){
                $cat = Category::find($course->category_id);
                if($cat->board_id==0){
          return
               $cat->name." | ".$course->title;
                }else{
                    $board = Board::find($cat->board_id);
                  return
              $board->name." | ". $cat->name." | ".$course->title;  
                }
                }else{
                    return "";
                }
        })
         ->addColumn('action', function($q){
             if($q->demo_status=='na'){
                 return '';
             }else if($q->demo_status!='completed'){
                  return '<a href="javascript:void(0)" data-id="'.$q->id.'" class="btn btn-sm btn-danger changeStatus mr-2 mb-2"><span class="fa fa-pencil"></span></a><a href="javascript:void(0)" data-id="'.$q->id.'" class="demo-start btn btn-primary btn-sm">Start Demo</a>';
                  
             }else{
                         return '';
             }
        })
        ->editColumn('created_at', function ($q) {
               
                    return date("d M Y h:iA",strtotime($q->demo_date_time));
                
            })
            ->editColumn('number', function ($q) {
                if($q->number == ""){
                    return "N/A";
                }else{
                    return $q->number;
                }
            })
            ->editColumn('demo_status', function ($q) {
                if($q->demo_status == "na"){
                    return "N/A";
                }else{
                    return ucwords($q->demo_status);
                }
            })
            ->make();
    }
  
   public function scheduleDemo(Request $request){
       
       $demo_id = $request->demo_id;
       $demo = DemoRequest::find($demo_id);
       if(!$demo){
           return redirect()->back()->withErrors("Unbale to find demo request");
       }
       
       $demo->teacher_id = $request->teacher;
       $demo->demo_date_time = date("Y-m-d H:i:s",strtotime($request->datetime));
       $demo->demo_status = 'scheduled';
       $demo->instructions = $request->instruction;
       $demo->update();

       $demo_history = new DemoHistory();
       $demo_history->demo_id = $demo->id;
       $demo_history->date_time = $demo->demo_date_time;
       $demo_history->teacher_id = $demo->teacher_id;
       $demo_history->save();

       $user = User::find($demo->user_id);
        $teacher = User::find($request->teacher);
        $demod = DemoRequest::find($demo_id);
        $link = URL::to("/user/dashboard");
       $course = Course::find($demo->course_id);
       $cro = new Course();
       $title = $cro->getCouseNameWithCat($demo->course_id);
       //email jayega user & teachers ko
       Mail::to($user)->send(new DemoStudentEmail($user,$teacher,$demod,$link,$title));
        Mail::to($teacher)->send(new DemoTeacherEmail($user,$teacher,$demod,$link,$title));
        return redirect()->back()->withSuccess("Demo has been scheduled");
       
   }
   
    public function getData(Request $request)
    {
        $contacts = "";
        $contacts = DemoRequest::orderBy('id', 'desc')->get();

        return DataTables::of($contacts)
            ->addIndexColumn()
            ->editColumn('created_at', function ($q) {
               return $q->created_at->format('d M, Y');
            })
            ->addColumn('course', function($q){
                $course = Course::find($q->course_id);
                if($course){
                $cat = Category::find($course->category_id);
                if($cat->board_id==0){
          return
               $cat->name." | ".$course->title;
                }else{
                    $board = Board::find($cat->board_id);
                  return
              $board->name." | ". $cat->name." | ".$course->title;  
                }
            }else{
                return "";
            }
        })
         ->addColumn('action', function($q){
             if($q->demo_status=='na'){
                 return '<a href="javascript:void(0)" data-id="'.$q->id.'" class="demo-init btn btn-primary btn-sm mb-2">Schedule Demo</a>

                 <a href="/user/demo-history/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">History</a>

                 <a href="/user/demo-feedback-list/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">Feedback</a>

                 ';
             }else if($q->demo_status=='scheduled'){
                  return '<span class="green-text">Demo is scheduled at '.$q->demo_date_time.'</span><br><a href="javascript:void(0)" data-id="'.$q->id.'" class="demo-init btn btn-primary btn-sm mb-2">ReSchedule Demo</a>

                  <a href="/user/demo-history/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">History</a>
                  <a href="/user/demo-feedback-list/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">Feedback</a>
                  
                         <a href="javascript:void(0)" data-flag="1" data-id="'.$q->id.'" data-mid="'.$q->api_class_id.'"  class="btn btn-outline-primary btn-sm mb-2 joinDemo">Join Demo</a>
                  ';
                  
             }else{
                         return '<span class="red-text">Demo was scheduled at '.$q->demo_date_time.'</span><br><a href="javascript:void(0)" data-id="'.$q->id.'" class="demo-init btn btn-primary btn-sm mb-2">ReSchedule Demo</a>

                         <a href="/user/demo-history/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">History</a>
                         <a href="/user/demo-feedback-list/'.$q->id.'" class="btn btn-outline-info btn-sm mb-2">Feedback</a>
                         <a href="javascript:void(0)" data-id="'.$q->id.'" data-mid="'.$q->api_class_id.'"  class="btn btn-outline-primary btn-sm mb-2 joinDemo">Join Demo</a>
                         ';
             }


        })
            ->editColumn('number', function ($q) {
                if($q->number == ""){
                    return "N/A";
                }else{
                    return $q->number;
                }
            })
            ->editColumn('demo_status', function ($q) {
                if($q->demo_status == "na"){
                    return "N/A";
                }else{
                    return ucwords($q->demo_status);
                }
            })
            ->make();
    }
     


    /**
     * Display demo batch list
     */
    public function demoBatch()
    {
        $batches = \App\Models\Batch::all();
        
        // Manually load teacher data since relationships don't exist
        foreach ($batches as $batch) {
            if ($batch->tid) {
                $batch->teacher = \App\Models\Auth\User::find($batch->tid);
            }
        }
        
        return view('backend.demo.batch_index', compact('batches'));
    }

    /**
     * Show form to add demo batch
     */
    public function demoBatchAdd()
    {
        $teachers = User::role('teacher')->where('active', true)->get();
        return view('backend.demo.batch_add', compact('teachers'));
    }

    /**
     * Save new demo batch
     */
    public function demoBatchSave(Request $request)
    {
        // TODO: Implement save logic
        return redirect()->route('admin.demo_batch')->withFlashSuccess('Demo batch created successfully');
    }

    /**
     * Show form to edit demo batch
     */
    public function demoBatchEdit($id)
    {
        $batch = \App\Models\Batch::find($id);
        $teachers = User::role('teacher')->where('active', true)->get();
        return view('backend.demo.batch_edit', compact('batch', 'teachers'));
    }

    /**
     * Update demo batch
     */
    public function demoBatchUpdate(Request $request, $id)
    {
        // TODO: Implement update logic
        return redirect()->route('admin.demo_batch')->withFlashSuccess('Demo batch updated successfully');
    }

    /**
     * Display students for a demo batch
     */
    public function demoBatchStudent($id)
    {
        $batch = \App\Models\Batch::find($id);
        
        // Get demo requests that haven't been assigned to any batch yet
        $requestList = \App\Models\DemoRequest::with('user', 'course')
            ->where('demo_status', 'pending')
            ->orWhere('batch_id', $id)
            ->get();
            
        // Get already assigned user IDs for this batch
        $assigneduid = \App\Models\StudentTeacherBatch::where('bid', $id)
            ->pluck('uid')
            ->toArray();
        
        return view('backend.demo.batch_student', compact('batch', 'requestList', 'assigneduid'));
    }

    /**
     * Update demo batch students
     */
    public function demoBatchStudentUpdate(Request $request, $id)
    {
        // TODO: Implement student update logic
        return redirect()->route('admin.demo_batch.student', $id)->withFlashSuccess('Students updated successfully');
    }

    /**
     * Join a demo class (for students)
     */
    public function join($id)
    {
        $demo = DemoRequest::findOrFail($id);
        return view('backend.demo.join', compact('demo'));
    }

    /**
     * Check and verify demo join request
     */
    public function joinCheck($id)
    {
        $demo = DemoRequest::findOrFail($id);
        return response()->json(['status' => $demo->demo_status, 'api_class_id' => $demo->api_class_id]);
    }
}
