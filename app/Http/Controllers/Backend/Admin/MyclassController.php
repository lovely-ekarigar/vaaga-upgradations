<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Locale;
use App\Models\Config;
use App\Models\Batch;
use App\Models\Course;
use App\Models\Auth\User;
use App\Models\TeacherBatch;
use App\Models\TeacherProfile;
use App\Models\Recording;
// use App\Models\Assignment;
use App\Models\Elearn;
use App\Models\Lesson;
use App\Models\Test;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\BatchUpload;
use App\Models\TestResponse;
use App\Models\CourseContent;
use App\Models\LessionComplete;
use App\Models\Assignment;
use App\Models\AssignmentUpload;
// getDemoLaunchURL
use App\Models\StudentJoin;
use App\Models\DemoHistory;
use App\Models\StudentTeacherBatch;
use App\Models\OauthClient;
use Illuminate\Http\Request;
use App\Models\DemoRequest;
use App\Models\StudentFeedbackQuestion;
use App\Models\TeacherFeedbackQuestion;
use App\Models\Feedback;
use App\Models\Unavailability;
use App\Models\SubjectiveExam;
use App\Models\SubjectiveExamUpload;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Mail\FeedbackEmail;
use App\Mail\TeacherFeedbackEmail;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\ExamTest;
use App\Models\ExamBatch;
use App\Models\ExamBatchTest;
use App\Models\ExamBatchUser;
use App\Models\ExamUser;



use URL;
use Auth;
use Mail;
class MyclassController extends Controller
{



public function calendar(){

    $uo = new Unavailability;

    $dateList = $uo->getTeacherAvail(auth()->user()->id);
    // dd($dateList);
    return view('backend.myclass.calendar',compact('dateList'));
}

public function markUnavail(Request $request){

    $un = new Unavailability;
    $un->user_id = auth()->user()->id;
    $un->date_time = $request->date;
    $un->reason = $request->reason;
    $un->save();


    return redirect()->back()->withFlashSuccess("Your Unavailability has been marked.");

}



 public function examsUploadSave(Request $request,$id,$bid)
    {
        $this->validate($request, [
           
            'file' => 'required|max:120000|mimes:doc,docx,pdf,png,jpg,jpeg'

        ], [
            
            'file.required' => 'Kindly Upload Assignment',
            'file.max' => 'File Size should be less than 10MB',
            'file.mimes' => 'File Type should be PDF, Doc, PNG, JPG & JPEG',

        ]);

        $assignment = new SubjectiveExamUpload();
        $assignment->batch_id = $bid;
        $assignment->exam_id = $id;
        $assignment->user_id = auth()->user()->id;
      
        if ($request->has('file')) {
            $fname=time().".".$request->file->getClientOriginalExtension();
             $request->file->move(public_path('storage/assignment'), $fname);
           
            $assignment->file = 'storage/assignment/'.$fname;
        }
        $assignment->save();

        return redirect('/user/sexams/'.$bid);


    }

public function examsUpload($id,$bid){


if(auth()->user()==null){

return abort(404);
}


$user=User::find(auth()->user()->id);
$batch=Batch::find($bid);
$stb=StudentTeacherBatch::where("bid",$bid)->where("uid",auth()->user()->id)->count();
if($stb==0){
return abort(404);
}

$assig = SubjectiveExam::find($id);

if(!$assig){
return abort(404);
}

 $upload = SubjectiveExamUpload::where('exam_id',$id)->where('user_id',auth()->user()->id)->first();

return view('backend.myclass.exams-upload', compact('upload','batch','assig'));

}


public function exams($id){

if(auth()->user()==null){

return abort(404);
}


$user=User::find(auth()->user()->id);
$batch=Batch::find($id);
$stb=StudentTeacherBatch::where("bid",$id)->where("uid",auth()->user()->id)->count();

if($stb==0){
return abort(404);
}


$assigs = SubjectiveExam::where('batch_id',$id)->orderBy("id","desc")->get();

$list = [];

foreach($assigs as $a){

    $aup = SubjectiveExamUpload::where('exam_id',$a->id)->where('user_id',auth()->user()->id)->first();

        $a->uploaded=false;
    if($aup){
        $a->uploaded=true;
        $a->data = $aup;
    }

$list[] = $a;
}


return view('backend.myclass.exams', compact('list','batch'));



}



public function MyExamUploadsRemarks($id, Request $request){

        $aup = SubjectiveExamUpload::find($request->upload_id);
        if($aup){
            $aup->marks = $request->marks;
            $aup->update();
        }


        return redirect()->back()->withFlashSuccess('Subjective marks updated sucessfully');
    }
public function MyExamUploads($id){

        $assignment = SubjectiveExam::find($id);

        if(!$assignment){
            return abort(404);
        }

        $batch_id = $assignment->batch_id;
        $batch = Batch::find($batch_id);

        $students = StudentTeacherBatch::where('bid',$batch_id)->get();

        $users=[];
        foreach($students as $s){

            $s->user = User::find($s->uid);

            $aup = SubjectiveExamUpload::where('batch_id',$batch_id)->where('user_id',$s->uid)->where('exam_id',$id)->first();
            $s->uploaded=false;
            if($aup){
                $s->uploaded=true;
                $s->assignment = $aup;
            }
            $users[] = $s;
        }

    

 return view('backend.myclass.exam-users',compact('batch','users','assignment'));

    }

    public function MyExam($id)
    {
        $batch = Batch::find($id);

        $assignment_list = SubjectiveExam::where('batch_id',$id)->get();

        return view('backend.myclass.exam',compact('batch','assignment_list'));
    }

    public function MyExamDelete($id){

        $se = SubjectiveExam::find($id);
        if($se){
            $se->delete();
        }
return redirect()->back()->withFlashSuccess('Subjective Exam has been deleted');
    }

    public function MyExamCreate(Request $request,$id)
    {
        $this->validate($request, [
            'title' => 'required',
            'date' => 'required',
            'file' => 'required|max:10000|mimes:doc,docx,pdf,png,jpg,jpeg'

        ], [
            'title.required' => 'Kindly Enter Title',
            'file.required' => 'Kindly Upload Assignment',
            'file.max' => 'File Size should be less than 1MB',
            'file.mimes' => 'File Type should be PDF, Doc, PNG, JPG & JPEG',

        ]);

        $assignment = new SubjectiveExam();
        $assignment->batch_id = $request->batch_id;
        $assignment->title = $request->title;
        $assignment->exam_date = date("Y-m-d",strtotime($request->date));
        if ($request->has('file')) {
            $fname=time().".".$request->file->getClientOriginalExtension();
             $request->file->move(public_path('storage/exams'), $fname);
           
            $assignment->file = 'storage/exams/'.$fname;
        }
        $assignment->save();



         $message = "Dear Student,<br> <b>".$request->title.'</b> Subjective exam has been assigned. Kindly visit classes module to check.';

        $not = new Notification;
        $not->title = $request->title."Subjective exam assigned.";
        $not->message = $message;
        $not->batch_type = 'selected';
        $not->user_type = 'student';
        $not->batch_list = json_encode([$request->batch_id]);
        $not->created_by = Auth::user()->id;
        $not->save();
        $id = $not->id;
            
            $stbs = StudentTeacherBatch::where('bid',$request->batch_id)->get();

            foreach($stbs as $st){

            $un = new UserNotification;
            $un->notification_id = $id;
            $un->user_id = $st->uid;
            $un->status = '0';
            $un->save();
        }


        return redirect()->back()->withFlashSuccess('Subjective Exam Uploaded sucessfully');


    }




public function sendBatchEmail(Request $request, $id){


$stb = StudentTeacherBatch::where('bid',$id)->get();

$tid = 0;
foreach($stb as $s){
$tid = $s->tid;
    $user = User::find($s->uid);
    if($user){
Mail::to($user)->send(new FeedbackEmail($user,$id));
    }
}

$teacher = User::find($tid);
if($teacher){
Mail::to($teacher)->send(new TeacherFeedbackEmail($teacher));
}
// Mail::to($user)->send(new FeedbackEmail($user,$id));



    return redirect()->back()->withFlashSuccess("Feedback email has been sent to batch Students & Tutor");
}


    public function batchFeedback(Request $request, $id){

        $batch = Batch::find($id);

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
            $sq->avg_rating = Feedback::where("question_id",$sq->id)->where("batch_id",$id)->where('is_teacher','0')->avg("review");
            $sqlist[] = $sq;
        }

  foreach($teacher_questions as $sq){
            $sq->avg_rating = Feedback::where("question_id",$sq->id)->where("batch_id",$id)->where('is_teacher','1')->avg("review");
            $tqlist[] = $sq;
        }

        $feedbacks=[];
        if($request->qid){

            $feedbacks = Feedback::with('user')->where('question_id',$request->qid)->where('batch_id',$id)->where('is_teacher',$is_teacher)->orderBy('id','desc')->get();

        }

        // dd($sqlist);

        return view('backend.feedback.batch',compact('sqlist','tqlist','userType','id','feedbacks','batch'));

    }


    public function AssignmentUploadsRemarks($id, Request $request){

        $aup = AssignmentUpload::find($request->upload_id);
        if($aup){
            $aup->remarks = $request->remarks;
            $aup->update();
        }

 return redirect()->back()->withFlashSuccess('Assignment remarks updated sucessfully');
    }

     public function batchFees($id){

        $batch = Batch::find($id);

        if(!$batch){
            return abort(404);
        }

        $tb = TeacherBatch::where("bid",$batch->id)->where("active","1")->first();


        

    

 return view('backend.myclass.fees',compact('batch','tb'));

    }

    public function batchFeesUpdate(Request $request){

        $tb = TeacherBatch::find($request->tb_id);
        $tb->fees = $request->fees;
        $tb->fees_schedule = $request->fees_schedule;
        $tb->update();

        return redirect()->back()->withFlashSuccess("Batch tutor fees updated");

    }


    public function AssignmentUploads($id){

        $assignment = Assignment::find($id);

        if(!$assignment){
            return abort(404);
        }

        $batch_id = $assignment->batch_id;
        $batch = Batch::find($batch_id);

        $students = StudentTeacherBatch::where('bid',$batch_id)->get();

        $users=[];
        foreach($students as $s){

            $s->user = User::find($s->uid);

            $aup = AssignmentUpload::where('batch_id',$batch_id)->where('user_id',$s->uid)->where('assignment_id',$id)->first();
            $s->uploaded=false;
            if($aup){
                $s->uploaded=true;
                $s->assignment = $aup;
            }
            $users[] = $s;
        }

    

 return view('backend.myclass.assignment-users',compact('batch','users','assignment'));

    }

    public function Assignment($id,Request $request)
    {

        if($request->del){
            $as = Assignment::find($request->del);
            if($as){
                $as->delete();
                return redirect()->back()->withFlashSuccess('Assignment deleted sucessfully');
            }
        }

        $batch = Batch::find($id);

        $assignment_list = Assignment::where('batch_id',$id)->get();

        return view('backend.myclass.assignment',compact('batch','assignment_list'));
    }

    public function AssignmentCreate(Request $request,$id)
    {
        $this->validate($request, [
            'title' => 'required',
            'file' => 'required|max:10000|mimes:doc,docx,pdf,png,jpg,jpeg'

        ], [
            'title.required' => 'Kindly Enter Title',
            'file.required' => 'Kindly Upload Assignment',
            'file.max' => 'File Size should be less than 1MB',
            'file.mimes' => 'File Size should be PDF,Doc, PNG, JPG and JPEG',

        ]);

        $assignment = new Assignment();
        $assignment->batch_id = $request->batch_id;
        $assignment->title = $request->title;
        if ($request->has('file')) {
            $fname=time().".".$request->file->getClientOriginalExtension();
             $request->file->move(public_path('storage/assignment'), $fname);
           
            $assignment->file = 'storage/assignment/'.$fname;
        }
        $assignment->save();


         $message = "Dear Student,<br> <b>".$request->title.'</b> Assigment has been assigned. Kindly visit assigment module to check.';

        $not = new Notification;
        $not->title = $request->title." Assigment assigned.";
        $not->message = $message;
        $not->batch_type = 'selected';
        $not->user_type = 'student';
        $not->batch_list = json_encode([$request->batch_id]);
        $not->created_by = Auth::user()->id;
        $not->save();
        $id = $not->id;
            
            $stbs = StudentTeacherBatch::where('bid',$request->batch_id)->get();

            foreach($stbs as $st){

            $un = new UserNotification;
            $un->notification_id = $id;
            $un->user_id = $st->uid;
            $un->status = '0';
            $un->save();
        }



        return redirect()->back()->withFlashSuccess('Assignment Upload sucessfully');


    }

public function studentClasses($slug){
    $course=Course::where("slug",$slug)->first();

    $expired=false;



    if($course){
         $cro = new Course;
    $expired = $cro->isCourseExpired($course->id,auth()->user()->id);
    $cs=DB::table("course_student")->where("user_id",auth()->user()->id)->where("course_id",$course->id)->count();
    if($cs>0){
        $batches=Batch::where("cid",$course->id)->get();
        $bids=array();
        $batchlist=array();
        foreach($batches as $b){
            $bc=StudentTeacherBatch::where("bid",$b->id)->where("uid",auth()->user()->id)->first();
            if($bc){
                $b['teacher']=User::find($bc['tid']);
                $batchlist[]=$b;

            }

            $meetid=Recording::where("parent",$b->parent_api_class_id)->where("created_at",">=",date("Y-m-d 00:00:00"))->orderBy("id","desc")->first();
            if($meetid){
                $b["can_join"]=true;
                $b["api_id"]=$meetid->api_class_id;

            }else{
                $b["can_join"]=false;
                $b["api_id"]=""; 
            }
        }

return view('backend.myclass.studentclasses', compact('batchlist','course','expired'));

    }else{
      return redirect("/user/dashboard")->withFlashDanger("Course not found");   
    }
}else{
return redirect("/user/dashboard")->withFlashDanger("Course not found");  
    }

}

   public function index(){
     if(auth()->user()->isAdmin()){ 
            return abort(403);
        } 
       
        $bids = StudentTeacherBatch::select('bid')->distinct()->where("tid",auth()->user()->id)->get();

        $list=array();
        foreach($bids as $x){
            $b=Batch::find($x->bid);
              if($b){
            $tb = TeacherBatch::where("tid",auth()->user()->id)->where("bid",$x->bid)->first();
            $b->active = '0';
            if($tb){
            $b->fees = $tb->fees;
            $b->active = $tb->active;
        }
            //dd($b);
          
$b["course"]=Course::where("id",$b->cid)->first();
            $list[]=$b;
            }
            
            
        }
        
        return view('backend.myclass.index', compact('list'));
       
   }

   public function courseTracking($id)
    {

            $batch_list=Batch::find($id);

            $course_content_list = CourseContent::where('course_id',$batch_list->cid)->get();

            $list=array();
            foreach($course_content_list as $course_content){
                $lesson_list = Lesson::where('content_id',$course_content->id)->where('published','1')->get();
                
                    $course_content->lesson_lists=$lesson_list;

                $list[] =$course_content;
                
                
            }
            
        return view('backend.myclass.course-tracking',compact('lesson_list','course_content_list','list'));
    }

    public function courseTrackingValidate(Request $request,$id)
    {
        dd($request->all(),$id);
    }

   public function details($id){
    $a=TeacherBatch::where("bid",$id)->where("tid",auth()->user()->id)->count();
if($a==0){
return redirect()->route('admin.myclass')->withFlashDanger("Invalid batch");
}

    $batch=Batch::find($id);


    $course=Course::find($batch->cid);
    $lessons = Lesson::where("course_id",$batch->cid)->orderBy("title","asc")->get();
    $sids=StudentTeacherBatch::where("bid",$id)->get();
    $students=array();
    foreach($sids as $s){
        $students[]=User::find($s->uid);
    }

  


    return view('backend.myclass.details', compact('batch','course','students','lessons'));
   }


public function attend(Request $request,$id){
     $batch=Batch::find($id);
$date = date("Y-m-d");
if($request->date){
    $date = $request->date;
}
     $reusr=StudentTeacherBatch::where("bid",$id)->get();

foreach($reusr as $u){
    $st=User::find($u->uid);
    $is=StudentJoin::where("uid",$u->uid)->where("date",date("Y-m-d",strtotime($date)))->first();
    if($is){
        $st["present"]=true;
        $st["time"]=$is->time;

    }else{
       $st["present"]=false; 
       $st["time"]=""; 
    }
    $st["date"]=$date;
$students[]=$st;
}

return view('backend.myclass.attendance', compact('batch','students'));

}



public function Attendance(Request $request,$id){
   $a=TeacherBatch::where("bid",$id)->where("tid",auth()->user()->id)->count();
if($a==0){
return redirect()->route('admin.myclass')->withFlashDanger("Invalid batch");
}
$students=array();
if($request->bid!=null && $request->date!=null){

$reusr=StudentTeacherBatch::where("bid",$request->bid)->get();

foreach($reusr as $u){
    $st=User::find($u->uid);
    $is=StudentJoin::where("uid",$u->uid)->where("date",$request->date)->first();
    if($is){
        $st["present"]=true;
        $st["time"]=$is->time;

    }else{
       $st["present"]=false; 
       $st["time"]=""; 
    }
    $st["date"]=$request->date;
$students[]=$st;
}

}

    $batch=Batch::find($id);
return view('backend.myclass.attendance', compact('batch','students'));
}
public function uploadFile(Request $request){
 $file = $request->file('file');
   $ext=$file->getClientOriginalExtension();
   $allowed=array("jpg","jpeg","png","gif","doc","docx","pdf","ppt","pptx","txt");
   if(in_array($ext, $allowed)){
//Display File Name
     /* echo 'File Name: '.$file->getClientOriginalName();
      echo '<br>';
   
      //Display File Extension
      echo 'File Extension: '.$file->getClientOriginalExtension();
     
      echo '<br>';
   
      //Display File Real Path
      echo 'File Real Path: '.$file->getRealPath();
      echo '<br>';
   
      //Display File Size
      echo 'File Size: '.$file->getSize();
      echo '<br>';
   
      //Display File Mime Type
      echo 'File Mime Type: '.$file->getMimeType();*/
   
      //Move Uploaded File
      $destinationPath = 'uploads';
      $fname=time().strtolower($file->getClientOriginalName());
      
      $bu=new BatchUpload;
      $bu->bid=$request->bid;
      $bu->tid=auth()->user()->id;
      $bu->file_name=$file->getClientOriginalName();
      $bu->mime=$file->getMimeType();
      $bu->file_url="uploads/".$fname;
      $bu->save();
$file->move($destinationPath,$fname);
      return redirect()->route('admin.myclass.upload',["id"=>$request->bid])->withFlashSuccess("File uploaded");  
   }else{
    return redirect()->route('admin.myclass.upload',["id"=>$request->bid])->withFlashDanger("Unsupported file type");  
   }
      

} 

public function rmFile(Request $request){
    $fid=$request->fid;
    $bu=BatchUpload::find($fid);
    $bu->delete();
    return redirect()->route('admin.myclass.upload',["id"=>$request->bid])->withFlashSuccess("File has been deleted"); 

}



public function saveAvailability(Request $request){

    $start=$request->start;
    $end=$request->end;
    $slots=[];

    foreach($start as $k=>$s){

        $lists=[];
        $liste=[];
        for($i=0;$i<count($s);$i++){

            if($s[$i] && $end[$k][$i]){
            $lists[]=$s[$i];
            $liste[]=$end[$k][$i];
}

        }

        $slots[$k] = array("s"=>$lists,"e"=>$liste);

    }



   // dd($slots);


     $tp = TeacherProfile::where('user_id',auth()->user()->id)->first();
     $tp->availability = json_encode($slots);
     $tp->update();

     return redirect()->back()->withFlashSuccess("Availability has been updated");
}

public function availability(){

    $user = auth()->user();

    $tp = TeacherProfile::where('user_id',$user->id)->first();

    if(!$tp){
        return abort(404);
    }
     $days  = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday'); 

     if(!$tp->availability){
        $ex = ["s"=>[],"e"=>[]];
       $data = array('monday'=>$ex,'tuesday'=>$ex,'wednesday'=>$ex,'thursday'=>$ex,'friday'=>$ex,'saturday'=>$ex,'sunday'=>$ex); 
     }else{
        $data = json_decode($tp->availability,true);
     }
     // dd($data);

    return view('backend.myclass.availability',compact('user','data'));
     
}


public function testanalysis(Request $request,$id)
{
    

    $test = Test::find($id);

    if(!$test){
        return abort(404);
    }

    $response = TestResponse::where('test_id',$id)->where('user_id',Auth::user()->id)->get();

if(count($response)==0){
        return abort(404);
    }
    $responses = [];

    foreach($response as $r){
        $question = Question::find($r->question_id);
        $options = QuestionsOption::where('question_id',$r->question_id)->get();
        $r->question = $question;
        $r->options = $options;
        $responses[] = $r;
    }


    return view('backend.myclass.test-analysis',compact('test','responses'));
}


public function testPages(Request $request,$id)
{
     $test_list=[];
     
    $examUser = ExamUser::where("sync_id",Auth::user()->id)->first();
    if($examUser){
        
        $examBatchUsers = ExamBatchUser::where("user_id",$examUser->id)->get()->pluck("batch_id")->toArray();
       $examTests = ExamBatchTest::whereIn("batch_id",$examBatchUsers)->get();
       
       foreach ($examTests as $et){
           
           $test = ExamTest::find($et->test_id);
           $et->test = $test;
           $test_list[] = $et;
       }
       
    }
    
    // dd($id,$request->course_id);
   

    // $test_listx = Test::where('batch_id',$id)->where('published','1')->orderBy("id","desc")->get();

    // foreach($test_listx as $t){
    //       $test = TestResponse::where("user_id",auth()->user()->id)->where('test_id',$t->id);

    //       $t->isAttempted = $test->count() > 0 ? true : false;
    //       $t->totalQuestion = $test->count();
    //       $t->totalCorrect = $test->where('is_correct','1')->count();
    //       $t->totalUnattempted = $test->where('is_correct','0')->where('response_option_id',null)->count();

    //       $test_list[] = $t;
    // }
    $batch = Batch::find($id);

    $course = Course::find($batch->cid);

    return view('backend.myclass.test-list',compact('test_list','course','batch'));
}


public function submitTest(Request $request){

 

$test = Test::find($request->test_id);
$qt_list = \DB::table('question_test')->where('test_id', $test->id)->get();
foreach($qt_list as $q){
$trf = TestResponse::where("user_id",auth()->user()->id)->where('test_id',$test->id)->where('question_id',$q->question_id)->count();
if($trf==0){
$tr = new TestResponse();
$tr->user_id = auth()->user()->id;
$tr->test_id = $test->id;
$tr->question_id = $q->question_id;
$tr->save();
}
}

if($request->option){
$options = $request->option;

foreach($options as $q=>$optid){

$trs = TestResponse::where("user_id",auth()->user()->id)->where('test_id',$test->id)->where('question_id',$q)->first();

$trs->response_option_id = $optid;

$qop = QuestionsOption::find($optid);

// dd($qop);
if($qop->correct==1){
$trs->is_correct = '1';
}
$trs->update();

}


}


return redirect('/user/dashboard')->withFlashSuccess('Test Submitted sucessfully');


}


public function attemptTest(Request $request,$id)
{
    // dd($id,$request->course_id);

    $test_list = Test::find($id);
    if(!$test_list){
        return abort(404);
    }
    $testRc = TestResponse::where("user_id",auth()->user()->id)->where('test_id',$test_list->id)->count();

    if($testRc>0){

        return redirect()->back()->withFlashSuccess('Test has been already attempted');
    }

    $qt_list = \DB::table('question_test')->where('test_id', $test_list->id)->get();
    // dd($qt_list);

    $ret_list = [];

    foreach($qt_list as $qt)
    {

        $question_id = Question::with('options')->where('id',$qt->question_id)->first();

        $qt->question_list=$question_id;

        $ret_list[] =$qt;

    }

    
    return view('backend.myclass.test-page',compact('test_list','question_id','ret_list'));
}


   public function upload($id){
   $role = Auth::user()->roles;

   $a=0; 
   if($role && $role[0]->name == 'administrator')
   {
    $a=TeacherBatch::where("bid",$id)->count();
   }else{

       $a=TeacherBatch::where("bid",$id)->where("tid",auth()->user()->id)->count();
   }

    
if($a==0){
return redirect()->route('admin.myclass')->withFlashDanger("Invalid batch");
}
$batch=Batch::find($id);
if($role && $role[0]->name == 'administrator')
   {
    $files=BatchUpload::where("bid",$id)->orderBy("id","desc")->get();

   }else{
$files=BatchUpload::where("bid",$id)->where("tid",auth()->user()->id)->orderBy("id","desc")->get();
   }
return view('backend.myclass.upload', compact('batch','files'));

   }

   public function adminRecordings($id){
      $batch=Batch::find($id);
      $list=array();

    $list=Recording::with('lesson')->where("parent",$batch->parent_api_class_id)->orderBy("id","desc")->get();
// dd($list);
  return view('backend.myclass.recordings', compact('list','batch'));
   }

  public function recordings($id){
    $a=0; 
        $a=TeacherBatch::where("bid",$id)->where("tid",auth()->user()->id)->count();
if($a==0){
return redirect()->route('admin.myclass')->withFlashDanger("Invalid batch");
}
    $batch=Batch::find($id);
      $list=array();
//    $recordings=Recording::where("parent",$batch->parent_api_class_id)->where("view_url",null)->get();

//     foreach($recordings as $rd){
//     $found=0;    
//         $el=new Elearn;
//         $in=array(
//             "meetingID"=>$rd->internal_id,
//             "recordID"=>$rd->internal_id
//         );
//         $x=$el->eClass("getRecordings",$in);
// if(array_key_exists("recordings",$x)){  
//        foreach($x["recordings"] as $rec){
//         $found++;
//     $rx=(array)$rec;
// //print_r();
//     $pb=(array)$rx["playback"];
//     $format=(array)$pb["format"];
//    // $prv=(array)$format["preview"];
//    // $images=(array)$prv["images"];
// $sr=Recording::find($rd->id);
// $sr->name=$rx["name"];
// $sr->view_url=$format["url"];
// $sr->start_time=$rx["startTime"];
// $sr->end_time=$rx["endTime"];
// $sr->update();
// //print_r($images["image"]);
//    // $list[]=array("name"=>$rx["name"],"url"=>$format["url"],"images"=>$images["image"],"start"=>$rx["startTime"]/1000,"end"=>$rx["endTime"]/1000);
// }
// }
// /*
// if($found==0){
// $sr=Recording::find($rd->id);
// $sr->name="";
// $sr->view_url="nf";
// $sr->start_time="";
// $sr->end_time="";
// $sr->update();

// }*/
//     }
    $list=Recording::with('lesson')->where("parent",$batch->parent_api_class_id)->orderBy("id","desc")->get();

  return view('backend.myclass.recordings', compact('list','batch'));
    } 


 public function assignmentsUploadSave(Request $request,$id,$bid)
    {
        $this->validate($request, [
           
            'file' => 'required|max:120000|mimes:doc,docx,pdf'

        ], [
            
            'file.required' => 'Kindly Upload Assignment',
            'file.max' => 'File Size should be less than 10MB',
            'file.mimes' => 'File Size should be PDF,Doc',

        ]);

        $assignment = new AssignmentUpload();
        $assignment->batch_id = $bid;
        $assignment->assignment_id = $id;
        $assignment->user_id = auth()->user()->id;
      
        if ($request->has('file')) {
            $fname=time().".".$request->file->getClientOriginalExtension();
             $request->file->move(public_path('storage/assignment'), $fname);
           
            $assignment->file = 'storage/assignment/'.$fname;
        }
        $assignment->save();

        return redirect('/user/assignments/'.$bid);


    }


public function assignmentsUpload($id,$bid){


if(auth()->user()==null){

return abort(404);
}


$user=User::find(auth()->user()->id);
$batch=Batch::find($bid);
$stb=StudentTeacherBatch::where("bid",$bid)->where("uid",auth()->user()->id)->count();
if($stb==0){
return abort(404);
}

$assig = Assignment::find($id);

if(!$assig){
return abort(404);
}

 $upload = AssignmentUpload::where('assignment_id',$id)->where('user_id',auth()->user()->id)->first();

return view('backend.myclass.assignments-upload', compact('upload','batch','assig'));

}


public function assignments($id){

if(auth()->user()==null){

return abort(404);
}


$user=User::find(auth()->user()->id);
$batch=Batch::find($id);
$stb=StudentTeacherBatch::where("bid",$id)->where("uid",auth()->user()->id)->count();

if($stb==0){
return abort(404);
}


$assigs = Assignment::where('batch_id',$id)->orderBy("id","desc")->get();

$list = [];

foreach($assigs as $a){

    $aup = AssignmentUpload::where('assignment_id',$a->id)->where('user_id',auth()->user()->id)->first();

        $a->uploaded=false;
    if($aup){
        $a->uploaded=true;
        $a->data = $aup;
    }

$list[] = $a;
}


return view('backend.myclass.assignments', compact('list','batch'));



}
public function pastClasses($id){
 if(auth()->user()!=null){

$user=User::find(auth()->user()->id);
$batch=Batch::find($id);
$stb=StudentTeacherBatch::where("bid",$id)->where("uid",auth()->user()->id)->count();



if($stb>0){
$list=array();
$el=new Elearn;
        $in=array(
           
        );
 $x=$el->eClassJson("getPastMeetings",$in);
//  dd($x);
//     $recordings=Recording::where("parent",$batch->parent_api_class_id)->where("view_url",null)->orWhere("view_url","nf")->get();
    
// // dd($recordings);

//     foreach($recordings as $rd){
//     $found=0;    
//         $el=new Elearn;
//         $in=array(
//             "meetingID"=>$rd->internal_id,
//             "recordID"=>$rd->internal_id
//         );
//         $x=$el->eClass("getRecordings",$in);

// if(array_key_exists("data",$x)){  
//       foreach($x["data"]["recordings"] as $rec){
//         $found++;
//     $rx=(array)$rec;
// //print_r();
//     $pb=(array)$rx["playback"];
//     $format=(array)$pb["format"];
//     $prv=(array)$format["preview"];
//     $images=(array)$prv["images"];
// $sr=Recording::find($rd->id);
// $sr->name=$rx["name"];
// $sr->view_url=$format["url"];
// $sr->start_time=$rx["startTime"];
// $sr->end_time=$rx["endTime"];
// $sr->update();
// //print_r($images["image"]);
//   // $list[]=array("name"=>$rx["name"],"url"=>$format["url"],"images"=>$images["image"],"start"=>$rx["startTime"]/1000,"end"=>$rx["endTime"]/1000);
// }
// }


// if($found==0){
// $sr=Recording::find($rd->id);
// $sr->name="";
// $sr->view_url="nf";
// $sr->start_time="";
// $sr->end_time="";
// $sr->update();

// }
//     }
    $list=Recording::with('lesson')->where("parent",$batch->parent_api_class_id)->orderBy("id","desc")->get();
    // dd($list);
return view('backend.myclass.pastclass', compact('list','batch'));
}else{
  return redirect("/user/dashboard")->withFlashDanger("Class not found");     
}

}else{
  return redirect("/user/dashboard")->withFlashDanger("Class not found");  
}
}

public function Downloads($id){
    if(auth()->user()!=null){
$user=User::find(auth()->user()->id);
$batch=Batch::find($id);
$stb=StudentTeacherBatch::where("bid",$id)->where("uid",auth()->user()->id)->count();
if($stb>0){
    $downloads=array();
    $inds=BatchUpload::where("bid",$id)->orderBy("id","desc")->get();
    foreach($inds as $in){
        $in["teacher"]=User::find($in->tid);
        $downloads[]=$in;

    }
return view('backend.myclass.downloads', compact('downloads','batch'));
}else{
  return redirect("/user/dashboard")->withFlashDanger("Class not found");     
}

}else{
  return redirect("/user/dashboard")->withFlashDanger("Class not found");  
}
}

public function joinDemoClasss($id,$meetid){
  
if(auth()->user()!=null){

$sj= DemoRequest::find($id);
$sj->student_join_at=date("Y-m-d H:i:s");
$sj->update();

  $api_id=$meetid; 

 $user=User::find(auth()->user()->id); 

    $lin=array(
            "meetingID"=>$api_id,
            "password"=>"ap",
            "fullName"=>$user->first_name." ".$user->last_name,
            "redirect"=>'true',
        );
    $e=new Elearn;
$launch=$e->getLaunch($lin);

if($launch["status"]){
//echo $launch["url"];
return redirect($launch["url"]);

}else{
echo "Something went wrong";
}
}else{
    echo "Session expired";
}
} 

public function joinClasss($id,$meetid){
  
if(auth()->user()!=null){

$sj=new StudentJoin;
$sj->uid=auth()->user()->id;
$sj->date=date("Y-m-d");
$sj->time=date("H:i:s");
$sj->bid=$id;
$sj->save();

  $api_id=$meetid; 

 $user=User::find(auth()->user()->id); 

    $lin=array(
            "meetingID"=>$api_id,
            "password"=>"ap",
            "fullName"=>$user->first_name." ".$user->last_name,
            "redirect"=>'true',
        );
    $e=new Elearn;
$launch=$e->getLaunch($lin);

if($launch["status"]){
//echo $launch["url"];
return redirect($launch["url"]);

}else{
echo "Something went wrong";
}
}else{
    echo "Session expired";
}
}
 
 public function getDemoLaunchURL(Request $request){
        
        $demo_id=$request->demo_id;
        $user=User::find(auth()->user()->id);
        $demo = DemoRequest::find($demo_id);
          $meetid=$demo_id."-".time();
          $e=new Elearn;
          
          
          if($demo->api_class_id){
              
$status=$e->eClass("isMeetingRunning",array("meetingID"=>$demo->api_class_id));

if($status['running']=='false'){
   $demo->api_class_id = null;
   $demo->update();
    //   dd($demo);
}
   $demo = DemoRequest::find($demo_id);

          }
        if(!$demo->api_class_id){
      
        $in=array(
            "name"=>"Demo for ".$demo->name,
            "meetingID"=>$meetid,
            "attendeePW"=>"ap",
            "moderatorPW"=>"mp",
            "record"=>'true',
            "welcome"=>"<br>Welcome to <b>%%CONFNAME%%</b>!",
            "allowStartStopRecording"=>'true',
            "autoStartRecording"=>"true",
            "logoutURL"=>URL::to("/")."/user/demo-feedback/".$demo_id,
        );
        
    //  print_r($in);
$mid="";

    $x=$e->eClass("create",$in);
   // print_r($x);
  // dd($x);
    if($x['returncode']){
if($x['returncode']=="SUCCESS"){
$mid=$x['meetingID'];
$internal = $x['internalMeetingID'];
 $b=DemoRequest::find($demo_id);
            $b->api_class_id=$mid;
            $b->demo_status='started';
            $b->update();

            $dr = DemoHistory::where("demo_id",$demo_id)->orderBy("id","desc")->first();
            // $dr->demo_id = $demo_id;
            $dr->internal_id = $internal;
            // $dr->teacher_id = Auth::user()->id;
            $dr->save();



           // return redirect()->route('admin.batch.create')->withFlashSuccess("Batch has been created");
}else{
    return response()->json(['success' => false, 'url' => "Something went wrong."]);
//return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");

}
}else{
    return response()->json(['success' => false, 'url' => "Something went wrong."]);
  //return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");  
}

}else{
  $meetid =$demo->api_class_id; 
}



$lin=array(
            "meetingID"=>$meetid,
            "password"=>"mp",
            "fullName"=>$user->first_name." ".$user->last_name,
            "redirect"=>'true',
        );
$launch=$e->getLaunch($lin);

// https://manager.bigbluemeeting.com/bigbluebutton/api/isMeetingRunning?meetingID=random-468054&checksum=794faa007b371f66a116d81af50f320b978c1baa

if($launch["status"]){
return response()->json(['success' => true, 'url' => $launch["url"]]);
}else{
return response()->json(['success' => false, 'url' => "Something went wrong."]);
}



    }

    public function lessionProgress($id)
    {
        
        $batch_list = Batch::find($id);

        $course_content_list = CourseContent::where('course_id',$batch_list->cid)->get();

            $list=array();
            foreach($course_content_list as $course_content){
                $lesson_list = Lesson::where('content_id',$course_content->id)->where('published','1')->get();
                
                    $course_content->lesson_lists=$lesson_list;

                $list[] =$course_content;
                
                
            }

            $lession_complete_list = LessionComplete::where('batch_id',$batch_list->id)->get();

            return view('backend.myclass.lession-progress',compact('list','lession_complete_list','batch_list'));
    }
    
    
    public function getLaunchURL(Request $request){

        $lesson=$request->lesson;
        $bid=$request->batch;
        $user=User::find(auth()->user()->id);
        $batch=Batch::find($bid);
        $meetid=$batch->parent_api_class_id."-".time();
        $in=array(
            "name"=>$batch->name." - ".$lesson,
            "meetingID"=>$meetid,
            "attendeePW"=>"ap",
            "moderatorPW"=>"mp", 
            "record"=>'true',
            "welcome"=>"<br>Welcome to <b>%%CONFNAME%%</b>!",
            "allowStartStopRecording"=>'true',
            "autoStartRecording"=>"true",
            "logoutURL"=>URL::to("/")."/user/feedback/".$bid,
        );

        $lession_complete_list = LessionComplete::where('batch_id',$bid)->where('lession_id',$lesson)->first();
        $ongoing_lession = LessionComplete::where('batch_id',$bid)->where('status','ongoing')->first();

       // dd($lession_complete_list);
        if(!$lession_complete_list){

            $lession_complete = new LessionComplete();
            $lession_complete->batch_id = $bid;
            $lession_complete->lession_id = $lesson;
            $lession_complete->status = 'ongoing';
           $lession_complete->save();

        }

        if($ongoing_lession){

            if($ongoing_lession->lession_id!=$lesson){
                $ongoing_lession->status='completed';
                $ongoing_lession->update();
            }
        }
        
           


        
        
    //  print_r($in);
$mid="";
$e=new Elearn;
    $x=$e->eClass("create",$in);
   // print_r($x);
  // dd($x);
    if($x['returncode']){
if($x['returncode']=="SUCCESS"){
$mid=$x['meetingID'];
 $b=Batch::find($bid);
            $b->api_class_id=$mid;
            $b->update();
$r=new Recording;
$r->parent=$batch->parent_api_class_id;
$r->api_class_id=$mid;
$r->lesson_id=$lesson;
$r->tid=auth()->user()->id;
$r->start_time=time();
$r->internal_id=$x['internalMeetingID'];
$r->save();
Session::put("class_rec_id",$r->id);

$lin=array(
            "meetingID"=>$meetid,
            "password"=>"mp",
            "fullName"=>$user->first_name." ".$user->last_name,
            "redirect"=>'true',
        );
$launch=$e->getLaunch($lin);

if($launch["status"]){
return response()->json(['success' => true, 'url' => $launch["url"]]);
}else{
return response()->json(['success' => false, 'url' => "Something went wrong."]);
}

           // return redirect()->route('admin.batch.create')->withFlashSuccess("Batch has been created");
}else{
    return response()->json(['success' => false, 'url' => "Something went wrong."]);
//return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");

}
}else{
    return response()->json(['success' => false, 'url' => "Something went wrong."]);
  //return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");  
}

    }


}


