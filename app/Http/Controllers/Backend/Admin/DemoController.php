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
use App\Models\DemoBatch;
use App\Models\Elearn;
use App\Models\DemoRequest;
use App\Models\DemoFeedback;
use App\Models\StudentFeedbackQuestion;
use App\Models\TeacherFeedbackQuestion;
use App\Models\DemoBatchStudent;
 
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


use App\Jobs\SendWhatsAppAiSensy;

use App\Models\AiSensy;
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
      
        return view('backend.demo.teacher_index', compact('users','demo_request'));
       
    }
        
    }
    
    
    public function demoBatchSave(Request $request){
        
        // dd($request->all());
        
        $demo = new DemoBatch();
        
        $demo->name = $request->name;
        $demo->demo_date = date("Y-m-d",strtotime($request->date));
        $demo->time_from = $request->from;
         $demo->time_to = $request->to;
        $demo->teacher_id = $request->tid;
        $demo->save();
         return redirect()->route('admin.demo_batch')->withSuccess("Demo Batch created succesfully");
        
    }
    
    
    public function demoBatchEdit($id){
        $batch = DemoBatch::find($id);
       $teachers = User::role('teacher')->get();
        
       return view('backend.demo.batch-edit', compact('teachers','batch'));   
    }
    
    public function demoBatchAdd(){
        $teachers = User::role('teacher')->get();
        
       return view('backend.demo.batch-add', compact('teachers')); 
        
    }
    
    public function demoBatchUpdate(Request $request,$id){
        
         $demo =  DemoBatch::find($id);
        
        $demo->name = $request->name;
        $demo->demo_date = date("Y-m-d",strtotime($request->date));
        $demo->time_from = $request->from;
         $demo->time_to = $request->to;
        $demo->teacher_id = $request->tid;
        $demo->update();
         return redirect()->route('admin.demo_batch')->withSuccess("Demo Batch updated succesfully");
    }
    
    
    public function demoBatchStudentUpdate(Request $request,$id){
        
       
        $batch = DemoBatch::find($id);
        $teacher = User::find($batch->teacher_id);
        $demoLink = date("ymd").rand(100,999).rand(1000,9999);
        $batch->link = $demoLink;
        $batch->update();
        if($request->student){
             DemoBatchStudent::where("batch_id",$id)->delete();
            foreach($request->student as $st){
                $db = new DemoBatchStudent();
                $db->batch_id = $id;
                $db->user_id = $st;
                $db->save();
                
                
                $user = User::find($st);
                
                
                $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'student_demo',
            'destination' => '+91'.$user->phone,
            'userName' => $user->name,
            'source' => 'schedule_demo',
            'templateParams' => [strtoupper(explode(" ",$user->name)[0]), date("d-M-Y h:i A",strtotime($demo->demo_date_time))." IST",$teacher->first_name,"Olympiad Class",$demoLink,$demoLink],
            'tags' => ['demo', 'new-demo'],
            'attributes' => ['eenquiry_id' => $user->id],
        ];
        
      
       $x= AiSensy::send($whatsappPayload);
                
            }
            
        }
        
          return redirect()->back()->withSuccess("Demo Batch student succesfully");
    }
    
    public function demoBatchStudent($id){
        
        $batch =  DemoBatch::find($id);
        
        $requestList = DemoRequest::with('course')->orderBy("id","desc")->limit(100)->get();
        $assigneduid=DemoBatchStudent::where("batch_id",$id)->pluck("user_id")->toArray();
        
          return view('backend.demo.batch-student', compact('requestList','batch','assigneduid')); 
        
    }
    
    
    public function demoBatch(Request $request){
        
        if($request->del){
            
            $batch = DemoBatch::find($request->del);
            if($batch){
                $batch->delete();
                return redirect()->route('admin.demo_batch')->withSuccess("Demo Batch deleted succesfully");
            }
        }
        
        $batches = DemoBatch::with('teacher')->orderBy("id","desc")->get();
        
    
        return view('backend.demo.batch', compact('batches'));
        
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
      
        return view('backend.demo.index', compact('users','demo_request'));
       
    }elseif(auth()->user()->hasRole('administrator')){

        $users = User::where('active',true)->role('teacher')->get();

        return view('backend.demo.index', compact('users'));
       
    }

   
       
   } 
   
   
   public function statusUpdate(Request $request){
      
    if($request->demo_type=='single'){
      $demo = DemoRequest::find($request->demo_id); 
      if($demo->teacher_id != Auth::user()->id){
           return redirect()->back()->withErrors("Unbale to perform this action");
      }
      $demo->demo_status = $request->status;
      $demo->remarks = $request->teacher_remarks;
      $demo->update();
    }else{
       $demo = DemoBatch::find($request->demo_id); 
      if($demo->teacher_id != Auth::user()->id){
           return redirect()->back()->withErrors("Unbale to perform this action");
      }
      $demo->demo_status = $request->status;
      $demo->remarks = $request->teacher_remarks;
      $demo->update();  
    }
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
Mail::to($userx)->send(new FeedbackEmail($userx,$id));
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

            $demo_history["course"]= Course::where("id",$demo_history->course_id)->first();
            $demo_history["course_old"]= Course::where("id",$demo_history["demo_request"]->course_id)->first();

            $list[]=$demo_history;
            
                    $el=new Elearn;
        $in=array(
            "meetingID"=>$demo_history["demo_request"]->api_class_id,
        );
        $x=$el->eClass("getRecordings",$in);
        $demo_history['api'] = $x;
         
        }
        
        // dd($list);

        return view('backend.demo.history',compact('list'));
   }
   
   
public function getDataTeacher(Request $request)
{
    $userId = auth()->user()->id;

    // Fetch and normalize DemoBatch data (batches first)
    $demoBatchList = DemoBatch::withCount('students')
        ->where("teacher_id", $userId)
        ->orderBy("id", "desc")
        ->get()
        ->map(function ($item) {
            return [
                'id' => $item->id,
                'type' => 'batch',
                'name' => $item->name,
                'email' => 'N/A',
                'phone' => 'N/A',
                'remarks' => 'N/A',
                'course' =>  'Group Demo',
                'demo_status' => ucwords($item->demo_status),
                'instructions'=>$item->students_count." Students",
                'created_at' => date("d M Y h:iA", strtotime($item->demo_date." ".$item->time_from)),
                'action' => $item->demo_status != 'completed' && $item->demo_status != 'Pending'
                    ? '<a href="javascript:void(0)" data-type="batch" data-id="' . $item->id . '" class="btn btn-sm btn-danger changeStatus mr-2 mb-2"><span class="fa fa-pencil"></span></a>
                       <a href="javascript:void(0)" data-type="batch" data-id="' . $item->id . '" class="demo-start btn btn-primary btn-sm">Start Demo</a>'
                    : '',
            ];
        });

    // Fetch and normalize DemoRequest data
    $contacts = DemoRequest::where('teacher_id', $userId)
        ->orderBy('id', 'desc')
        ->get()
        ->map(function ($q) {
            $course = Course::find($q->course_id);
            $cat = $course ? Category::find($course->category_id) : null;
            $board = $cat && $cat->board_id ? Board::find($cat->board_id) : null;

            $courseText = '';
            if ($cat && $course) {
                $courseText = ($board ? $board->name . ' | ' : '') . $cat->name . ' | ' . $course->title;
            }

            return [
                'id' => $q->id,
                'type' => 'request',
                'name' => $q->name,
                'email' => $q->email,
                'phone' => $q->number ?: 'N/A',
                'remarks' => $q->remarks ?: 'N/A',
                'course' => $courseText,
                 'instructions'=>'',
                'demo_status' => $q->demo_status == 'na' ? 'N/A' : ucwords($q->demo_status),
                'created_at' => date("d M Y h:iA", strtotime($q->demo_date_time)),
                'action' => $q->demo_status != 'completed' && $q->demo_status != 'na'
                    ? '<a href="javascript:void(0)" data-type="single" data-id="' . $q->id . '" class="btn btn-sm btn-danger changeStatus mr-2 mb-2"><span class="fa fa-pencil"></span></a>
                       <a href="javascript:void(0)" data-type="single"  data-id="' . $q->id . '" class="demo-start btn btn-primary btn-sm">Start Demo</a>'
                    : '',
            ];
        });

    // Merge batches first, then contacts
    $merged = $demoBatchList->concat($contacts);

    // Return DataTable
    return DataTables::of($merged)
        ->addIndexColumn()
        ->rawColumns(['action'])
        ->make(true);
}


public function join($id){
    $demo = DemoRequest::where("link",$id)->first();
    if(!$demo){
        return abort(404);
    }
   
   $user = User::find($demo->user_id); 
   Auth::login($user);
   $teacher = User::find($demo->teacher_id);
   
   $course = Course::find($demo->course_id);
   
  
   return view("demo.wait",compact('demo','user','course','teacher'));
   
}
public function joinCheck($id){
    
    $demo = DemoRequest::find($id); 
    
    if($demo->api_class_id){
        
        
            return response()->json(['can_join'=>true,'api_id'=>$demo->api_class_id]);
            
            
    }else{
        return response()->json(['can_join'=>false]);
    }
}




  
   public function scheduleDemo(Request $request){
       
       $demo_id = $request->demo_id;
       $demo = DemoRequest::find($demo_id);
       $demoLink = date("ymd").rand(10,99).rand(100,999);
       if(!$demo){
           return redirect()->back()->withErrors("Unbale to find demo request");
       }
       
       $demo->teacher_id = $request->teacher;
       $demo->demo_date_time = date("Y-m-d H:i:s",strtotime($request->datetime));
       $demo->demo_status = 'scheduled';
       $demo->instructions = $request->instruction;
       $demo->api_class_id=null;
       $demo->link=$demoLink;
       $demo->update();

       $demo_history = new DemoHistory();
       $demo_history->demo_id = $demo->id;
       $demo_history->date_time = $demo->demo_date_time;
       $demo_history->teacher_id = $demo->teacher_id;
       $demo_history->course_id = $demo->course_id;
       $demo_history->save();

       $user = User::find($demo->user_id);
        $teacher = User::find($request->teacher);
        $demod = DemoRequest::find($demo_id);
        $link = URL::to("/user/dashboard");
       $course = Course::find($demo->course_id);
       $cro = new Course();
       $title = $cro->getCouseNameWithCat($demo->course_id);
       
       
        $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'student_demo',
            'destination' => '+91'.$demo->phone,
            'userName' => $demo->name,
            'source' => 'schedule_demo',
            'templateParams' => [strtoupper(explode(" ",$demo->name)[0]), date("d-M-Y h:i A",strtotime($demo->demo_date_time))." IST",$teacher->first_name,$course->title,$demoLink,$demoLink],
            'tags' => ['demo', 'new-demo'],
            'attributes' => ['eenquiry_id' => $demo->id],
        ];
        
        $grade =explode(" ",$course->title);
        
         $whatsappPayloadTutor = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'tutor_demo',
            'destination' => '+91'.$teacher->phone,
            'userName' => $teacher->name,
            'source' => 'schedule_demo',
            'templateParams' => [strtoupper(explode(" ",$teacher->name)[0]), date("d-M-Y h:i A",strtotime($demo->demo_date_time))." IST",$course->title,$course->title],
            'tags' => ['demo', 'new-demo'],
            'attributes' => ['eenquiry_id' => $demo->id],
        ];
        //  dispatch(new SendWhatsAppAiSensy($whatsappPayload));
         $xt= AiSensy::send($whatsappPayloadTutor);
       $x= AiSensy::send($whatsappPayload);
        // dd($xt);
        
       
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
     

}