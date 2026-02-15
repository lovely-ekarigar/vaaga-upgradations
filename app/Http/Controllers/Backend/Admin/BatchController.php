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
use App\Models\TeacherFee;
use App\Models\Elearn;
use App\Models\OnesignalApp;
use App\Models\Lesson;
use App\Models\CourseContent;
use App\Models\LessionComplete;
use App\Models\Certificate;
use Carbon\Carbon;
use App\Models\Recording;
use App\Models\StudentTeacherBatch;
use App\Models\OauthClient;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Mail\Frontend\Demo\TeacherBatchEmail;
use Mail;
use Illuminate\Support\Str;

class BatchController extends Controller
{
   public function index(){

     if(!auth()->user()->isAdmin()){ 
            return abort(403);
        } 
        $batches=Batch::orderBy("id","desc")->get();

        $list=array();
        foreach($batches as $b){
            $b["course"]=Course::where("id",$b->cid)->first();
             
            $list[]=$b;
            
        }
       
        return view('backend.batch.index', compact('list'));
       
   }

   public function batchprogressteacherList($id)
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

            return view('backend.batch.batch-progress',compact('list','lession_complete_list'));
   }

   public function batchisCompleted($id)
   {
       $batch_list = Batch::find($id);
       $batch_list->is_completed = '1';
       $batch_list->update();

       $course = Course::find($batch_list->cid);
       $stb = StudentTeacherBatch::where("bid",$id)->get();
       $co = new Course;
       $crx = $co->getCouseNameWithCat($course->id);
       // dd($crx);

       foreach($stb as $s){
        $user = User::find($s->uid);

        if($user){

         $certificate = Certificate::firstOrCreate([
                'user_id' => $s->uid,
                'course_id' => $course->id
            ]);

            $data = [
                'name' => $user->first_name." ".$user->last_name,
                'course_name' => $crx,
                'date' => Carbon::now()->format('d M, Y'),
            ];
            $certificate_name = Str::slug('Certificate-' . $crx . '-' . $s->uid ).".pdf";
            $certificate->name = $crx;
            $certificate->url = $certificate_name;
            $certificate->save();

            $pdf = \PDF::loadView('certificate.index', compact('data'))->setPaper('', 'landscape');

            $pdf->save(public_path('storage/certificates/' . $certificate_name));
        }
}



       return redirect()->back()->withFlashSuccess("Batch Completed Update Successfully");
   }




   public function generateBatchCertificate(Request $request)
    {
        $course = Course::whereHas('students', function ($query) {
            $query->where('id', \Auth::id());
        })
            ->where('id', '=', $request->course_id)->first();
        if (($course != null) && ($course->progress() == 100)) {
            $certificate = Certificate::firstOrCreate([
                'user_id' => auth()->user()->id,
                'course_id' => $request->course_id
            ]);

            $data = [
                'name' => auth()->user()->name,
                'course_name' => $course->title,
                'date' => Carbon::now()->format('d M, Y'),
            ];
            $certificate_name = 'Certificate-' . $course->id . '-' . auth()->user()->id . '.pdf';
            $certificate->name = auth()->user()->id;
            $certificate->url = $certificate_name;
            $certificate->save();

            $pdf = \PDF::loadView('certificate.index', compact('data'))->setPaper('', 'landscape');

            $pdf->save(public_path('storage/certificates/' . $certificate_name));

            return back()->withFlashSuccess(trans('alerts.frontend.course.completed'));
        }
        return abort(404);
    }


   public function batchprogressList($id)
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

            return view('backend.batch.batch-progress',compact('list','lession_complete_list'));
   }


public function onesignal(Request $request){
    
     $stbs=StudentTeacherBatch::where("bid",$request->bid)->get();
     $uids = [];
      $xuids = [];
     foreach($stbs as $st){
        $xuids[] = $st->uid;
         $op = OnesignalApp::where("user_id",$st->uid)->first();
         if($op){
             $uids[] = $op->player_id; 
         }
     }
     
    //  dd($xuids);
   if($request->title && $request->msg){
     $this->sendPush($uids,$request->title,$request->msg);
      return response()->json(["success"=>true,"message"=>"Push notifications has been sent"]);
   }else{
       return response()->json(["success"=>false,"message"=>"Title & Message cac not be blank"]);
   }
     
}


public function sendPush($ids,$title,$msg){


     $content = array(
      "en" => $msg
      );
    $headings=array("en"=>$title);
    
    $fields = array(
      'app_id' => "14b961ad-14f5-4d89-8b94-c529886061a7",
      'include_player_ids' => $ids,
      'contents' => $content,
      'headings' => $headings
    );
    
    $fields = json_encode($fields);
     
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8','Authorization: Basic YTgwYjU2ZGItNTZkOS00ODkwLThhNGYtNjhiNWM3MTk3NTJm'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    // dd($response);
    curl_close($ch);
}



public function create(){
     if(!auth()->user()->isAdmin()){
            return abort(403);
        } 
        $courses=Course::orderBy("sort_order","asc")->get();
      
        return view('backend.batch.create', compact('courses'));
       
   }

public function updateBatch(Request $request){
 $this->validate($request, [
            'batch' => 'required|min:3',
            'cid' => 'required',
            'startbatchdate' => 'required',
            'endbatchdate' =>'required',
            'sbatchtime' => 'required',
            'ebatchtime' =>'required',
            'occurance' =>'required',
            'bid'=>'required'
        ]);

  $b=Batch::find($request->bid);
            $b->name=$request->batch;
            $b->cid=$request->cid;

            $b->start_date=$request->startbatchdate;
            $b->end_date=$request->endbatchdate;
            $b->start_time=$request->sbatchtime;
            $b->end_time=$request->ebatchtime;
            
            $b->occur=json_encode($request->occurance);

            $b->update();

            return redirect()->route('admin.batch.edit',['id'=>$request->bid])->withFlashSuccess("Batch has been updated");

}
   public function saveBatch(Request $request){
        $this->validate($request, [
            'batch' => 'required|min:3',
            'cid' => 'required',
            'startbatchdate' => 'required',
            'endbatchdate' =>'required',
            'sbatchtime' => 'required',
            'ebatchtime' =>'required',
            'occurance' =>'required',
        ]);

$in=array(
            "name"=>$request->batch,
            "meetingID"=>"livet-".time().rand(10000,99999),
           "attendeePW"=>"ap",
			"moderatorPW"=>"mp",
			"record"=>'true',
			"autoStartRecording"=>'true',
			"duration"=>'600',
			"welcome"=>"<br>Welcome to <b>%%CONFNAME%%</b>!",
			"webcamsOnlyForModerator"=>'true',
			"allowModsToUnmuteUsers"=>'true',
			"lockSettingsDisablePrivateChat"=>'true',
			"allowStartStopRecording"=>'true'
        );
      
    //  print_r($in);
$mid="";
$e=new Elearn;
    $x=$e->eClass("create",$in);
     // dd($x);
    if($x['returncode']){
if($x['returncode']=="SUCCESS"){
$mid=$x['meetingID'];

 //dd($x);
 $b=new Batch;
            $b->name=$request->batch;
            $b->cid=$request->cid;
            $b->start_date=$request->startbatchdate;
            $b->end_date=$request->endbatchdate;
            $b->start_time=$request->sbatchtime;
            $b->end_time=$request->ebatchtime;
            $b->api_class_id=$mid;
            $b->parent_api_class_id=$mid;
            $b->occur=json_encode($request->occurance);

            $b->save();
//dd($b);
            return redirect()->route('admin.batch.create')->withFlashSuccess("Batch has been created");
}else{
return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");

}
}else{
  return redirect()->route('admin.batch.create')->withFlashDanger("You have an issues with whiteboard API. Please contact with providers");  
}
           
}


public function update(Request $request){
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 


   }
public function deleteBatch($id){
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 

        $b=Batch::find($id);
            
            $b->delete();

            return redirect()->route('admin.batch')->withFlashDanger("Batch has been deleted");


}
public function show(){
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 


}
public function editBatch($id){
   // echo $id;
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 
        $batch=Batch::where("id",$id)->first();
        $courses=Course::orderBy("sort_order","asc")->get();
   //dd($batch);
        return view('backend.batch.edit', compact('batch','courses'));
}

public function Course(Request $request){
  if(!auth()->user()->isAdmin()){
            return abort(403);
        }  
        
$sel=$request->id;

         $courses=Course::orderBy("sort_order","asc")->get();
        // $students=User::with('roles')->get();
        $students = User::role('student')->with('roles')->get();
        $als=DB::table('course_student')->where("course_id",$sel)->get();
        $uids=array();
        foreach($als as $s){
            $uids[]=$s->user_id;

        }
        return view('backend.batch.course', compact('courses','sel','students','uids'));

}
public function courseSave(Request $request){
$students=$request->student;
$cid=$request->cid;
$al=DB::table('course_student')->where("course_id",$cid)->delete();
foreach($students as $s){
    
DB::table('course_student')->insert(
    ['course_id' => $cid, 'user_id' => $s,'created_at'=>date("Y-m-d H:i:s"),'updated_at'=>date("Y-m-d H:i:s")]
);


}
//dd($students);
return redirect()->route('admin.batch.course',['id'=>$cid])->withFlashSuccess("Course has been assigned to students");
}
public function batchassign($id){
    if(!auth()->user()->isAdmin()){
            return abort(403);
        } 
          
        $teachers=array();
      
        $bid=$id;
        $assigned=StudentTeacherBatch::where("bid",$id)->get();
        $assigneduid=array();
        foreach($assigned as $au)
        {
            if(!in_array($au->uid, $assigneduid)){
 $assigneduid[]=$au->uid; 
            }
          

        }
        
        $batch=Batch::find($id);
       
        $cid=$batch->cid;
        $users = DB::table('course_user')->where("course_id",$cid)->get();

        $students=array();
        foreach($users as $u)
        {
            $ux=User::with('roles', 'permissions', 'providers')->where("id",$u->user_id)->first();         
            $teachers[]=$ux;
        }

             $susers = DB::table('course_student')->where("course_id",$cid)->get();
       
       $stids=array();
        foreach($susers as $u)
        {
            if(!in_array($u->user_id, $stids)){
            $ux=User::with('roles', 'permissions', 'providers')->where("id",$u->user_id)->first();
      
            $students[]=$ux;
            $stids[]=$u->user_id;
        }
          
        }
        $teacher=TeacherBatch::where("bid",$id)->where("active","1")->first();

    return view('backend.batch.batchassign',compact('teachers','students','bid','assigned','assigneduid','teacher','batch'));

}

public function batchAssignsave(Request $request){
          $this->validate($request, [
            'teachersid' => 'required',
            'student' => 'required',
            'bid' =>'required'
            
        ]);
$teacher = User::find($request->teachersid);
  $batch = Batch::find($request->bid);

          $alreadyteacher=TeacherBatch::where("bid",$request->bid)->get();

          foreach($alreadyteacher as $at){

            $atx = TeacherBatch::find($at->id);
            $atx->active='0';
            $atx->update();
          }
  $alreadyteacherx=TeacherBatch::where("bid",$request->bid)->where("tid",$request->teachersid)->get();
          if(count($alreadyteacherx)==0){
            $tb=new TeacherBatch;
            $tb->bid=$request->bid;
            $tb->tid=$request->teachersid;
            // $tb->fees=$fees;
            $tb->save();

            
          
            $cro = new Course();
              Mail::to($teacher)->send(new TeacherBatchEmail($teacher,$batch->name, $cro->getCouseNameWithCat($batch->cid)));

          }else{
            $tx=TeacherBatch::where("bid",$request->bid)->where("tid",$request->teachersid)->first();
            $txn=TeacherBatch::find($tx->id);
            $old_tid = $txn->tid;
            $txn->tid=$request->teachersid;
            $txn->active='1';
            // $txn->fees=$fees;
            $txn->update();

            if($old_tid!=$request->teachersid){
 $teacher = User::find($request->teachersid);
            $batch = Batch::find($request->bid);
            $cro = new Course();
              Mail::to($teacher)->send(new TeacherBatchEmail($teacher,$batch->name, $cro->getCouseNameWithCat($batch->cid)));
            }
          }

          $willdel=StudentTeacherBatch::where("bid",$request->bid)->get();
          foreach($willdel as $wl){
            $dl=StudentTeacherBatch::find($wl->id);
            $dl->delete();


          }


          foreach($request->student as $sid){
            $already=StudentTeacherBatch::where("uid",$sid)->where("bid",$request->bid)->first();
            if($already==null){
             $bs=new StudentTeacherBatch;
            $bs->tid=$request->teachersid;
            $bs->bid=$request->bid;
            $bs->uid=$sid;

            $bs->save(); 
            }else{
            $bs=StudentTeacherBatch::find($already->id);
            $bs->tid=$request->teachersid;
            $bs->bid=$request->bid;
            $bs->uid=$sid;

            $bs->update(); 
            }
          
          }
           
            //dd($bs);

        return redirect()->route('admin.batch.batchassign',['id'=>$request->bid])->withFlashSuccess("Batch has been assigned successfully");

}

    /**
     * Update batch progress list
     */
    public function updatebatchprogressList($id, Request $request)
    {
        return redirect()->back()->withFlashSuccess('Progress updated');
    }

    /**
     * Get available mock tests for a batch
     */
    public function availableMockTests($id)
    {
        $batch = \App\Models\Batch::find($id);
        
        if (!$batch) {
            return response()->json([
                'success' => false,
                'message' => 'Batch not found'
            ], 404);
        }
        
        $course = \App\Models\Course::find($batch->cid);
        $courseName = $course ? $course->title : 'Unknown Course';
        
        // Get mock series for this course
        $mockSeries = \App\Models\MockSeries::where('course_id', $batch->cid)
            ->where('status', '1')
            ->with(['mockList' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();
        
        $mockTests = [];
        foreach ($mockSeries as $series) {
            foreach ($series->mockList as $mock) {
                $mockTests[] = [
                    'id' => $mock->id,
                    'name' => $mock->name,
                    'description' => $mock->description,
                    'series_id' => $series->id,
                    'series_name' => $series->name,
                    'series_detail' => $series->detail,
                    'total_questions' => $mock->total_questions,
                    'duration' => $mock->duration
                ];
            }
        }
        
        // Get already assigned mock tests for this batch
        $assignedMockIds = \App\Models\BatchMockTest::where('batch_id', $id)
            ->pluck('mock_series_id')
            ->toArray();
        
        return response()->json([
            'success' => true,
            'courseName' => $courseName,
            'mockTests' => $mockTests,
            'assignedMockIds' => $assignedMockIds
        ]);
    }

    /**
     * Save mock tests for a batch
     */
    public function saveMockTests($id, Request $request)
    {
        $batch = \App\Models\Batch::find($id);
        
        if (!$batch) {
            return redirect()->back()->withFlashError('Batch not found');
        }
        
        $mockTestIds = $request->input('mock_test_ids', []);
        
        // Remove existing assignments
        \App\Models\BatchMockTest::where('batch_id', $id)->delete();
        
        // Add new assignments
        foreach ($mockTestIds as $mockId) {
            $mock = \App\Models\MockList::find($mockId);
            if ($mock) {
                \App\Models\BatchMockTest::create([
                    'batch_id' => $id,
                    'mock_series_id' => $mock->mock_series_id,
                    'sort_order' => 0
                ]);
            }
        }
        
        return redirect()->back()->withFlashSuccess('Mock tests assigned successfully');
    }

    /**
     * Get mock results for a batch
     */
    public function mockResults($id)
    {
        $batch = Batch::find($id);
        $course = $batch ? Course::find($batch->cid) : null;
        return view('backend.batch.mock-results', compact('batch', 'course'));
    }

    /**
     * Get students list for a batch
     */
    public function getStudentsList($id)
    {
        return response()->json(['students' => []]);
    }

    /**
     * Get mock tests list for a batch
     */
    public function getMockTestsList($id)
    {
        return response()->json(['tests' => []]);
    }

    /**
     * Get student mock result
     */
    public function getStudentMockResult($student_id, $mock_id)
    {
        return view('backend.batch.student-mock-result', compact('student_id', 'mock_id'));
    }


}
