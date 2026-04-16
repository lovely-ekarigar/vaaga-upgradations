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
use App\Models\StudentCommitment;


use Carbon\Carbon;
use App\Models\Recording;
use App\Models\StudentTeacherBatch;
use App\Models\OauthClient;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Mail\Frontend\Demo\TeacherBatchEmail;
use Mail;
use Illuminate\Support\Str;

class BatchController extends Controller
{
   public function index(){

    if(!auth()->user()->hasAnyRole(['administrator', 'backend-support-staff'])){
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

      $busers = StudentTeacherBatch::where("bid",$id)->get();
       
       $userProgress=[];
       
       foreach($busers as $bu){
           
           $user = User::find($bu->uid);
           $bu->user = $user;
           
           $commit = StudentCommitment::where("batch_id",$id)->where("student_id",$bu->uid)->first();
           $bu->commit = $commit;
           
           $userProgress[] = $bu;
           
       }

        $course_content_list = CourseContent::where('course_id',$batch_list->cid)->get();

            $list=array();
            foreach($course_content_list as $course_content){
                $lesson_list = Lesson::where('content_id',$course_content->id)->where('published','1')->get();
                
                    $course_content->lesson_lists=$lesson_list;

                $list[] =$course_content;
                
                
            }

            $lession_complete_list = LessionComplete::where('batch_id',$batch_list->id)->get();
// dd($lession_complete_list);
            return view('backend.batch.batch-progress',compact('list','lession_complete_list','userProgress'));
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
       
       
       $busers = StudentTeacherBatch::where("bid",$id)->get();
       
       $userProgress=[];
       
       foreach($busers as $bu){
           
           $user = User::find($bu->uid);
           $bu->user = $user;
           
           $commit = StudentCommitment::where("batch_id",$id)->where("student_id",$bu->uid)->first();
           $bu->commit = $commit;
           
           $userProgress[] = $bu;
           
       }
       
       
    //   StudentCommitment

        $course_content_list = CourseContent::where('course_id',$batch_list->cid)->get();

            $list=array();
            foreach($course_content_list as $course_content){
                $lesson_list = Lesson::where('content_id',$course_content->id)->where('published','1')->get();
                
                    $course_content->lesson_lists=$lesson_list;

                $list[] =$course_content;
                
                
            }

            $lession_complete_list = LessionComplete::where('batch_id',$batch_list->id)->get();

            return view('backend.batch.batch-progress',compact('list','lession_complete_list','userProgress','id'));
   }
   
public function updatebatchprogressList($id, Request $request)
{
    if ($request->has('commitments')) {
        foreach ($request->commitments as $studentId => $data) {

            // Try to find an existing commitment
            $commitment = StudentCommitment::where('batch_id', $id)
                ->where('student_id', $studentId)
                ->first();

            if (!$commitment) {
                // Create a new one if it doesn't exist
                $commitment = new StudentCommitment();
                $commitment->batch_id = $id;
                $commitment->student_id = $studentId;
            }

            // Assign values from form
            $commitment->total_class = $data['total_class'] ?? 0;
            $commitment->total_test  = $data['total_test'] ?? 0;
            $commitment->joining_date  = $data['joining_date'] ?? null;
            $commitment->completion_date  = $data['completion_date'] ?? null;

            // Save to database
            $commitment->save();
        }
    }

    return redirect()->back()->with('success', 'Batch commitments updated successfully.');
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
      'app_id' => config('services.onesignal.app_id'),
      'include_player_ids' => $ids,
      'contents' => $content,
      'headings' => $headings
    );
    
    $fields = json_encode($fields);
     
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic ' . config('services.onesignal.rest_api_key')));
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
     if(!auth()->user()->hasAnyRole(['administrator', 'backend-support-staff'])){
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
            // $b->total_class=$request->total_class;
            // $b->total_test=$request->total_test;
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
            // $b->total_class=$request->total_class;
            // $b->total_test=$request->total_test;
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
   if(!auth()->user()->hasAnyRole(['administrator', 'backend-support-staff'])){
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
     if(!auth()->user()->hasAnyRole(['administrator', 'backend-support-staff'])){
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
   if(!auth()->user()->hasAnyRole(['administrator', 'backend-support-staff'])){
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

               // Update order end_date for eligible course modes when students are enrolled in batch
        $batch = Batch::find($request->bid);
        if ($batch && $batch->start_date) {
            foreach($request->student as $sid) {
                // Get ALL orders for this student and course (regardless of course_mode)
                $orders = Order::where('user_id', $sid)
                    ->where('status', '1')
                    ->whereHas('items', function($query) use ($batch) {
                        $query->where('item_id', $batch->cid)
                              ->where('item_type', 'App\\Models\\Course');
                    })
                    ->get();

                foreach($orders as $order) {
                    // Get course mode for this order
                    $courseMode = strtolower($order->course_mode ?? '');

                    // Update end_date based on course mode
                    if (strpos($courseMode, 'monthly') !== false) {
                        // Handle monthly orders
                        $batchStartDate = Carbon::parse($batch->start_date);
                        $orderCreatedAt = Carbon::parse($order->created_at);

                        // If batch start date is in future from order created_at
                        if ($batchStartDate->gt($orderCreatedAt)) {
                            // Use batch start_date + 1 month
                            $newEndDate = $batchStartDate->copy()->addMonth()->format('Y-m-d');
                        } else {
                            // If batch start date is in past or same as order created_at
                            // Use order created_at + 1 month
                            $newEndDate = $orderCreatedAt->copy()->addMonth()->format('Y-m-d');
                        }

                        $order->end_date = $newEndDate;

                        // Update subscription end_date
                        $subscription = Subscription::where('order_id', $order->id)->first();
                        if ($subscription) {
                            $subscription->end_date = $newEndDate;
                            $subscription->update();
                        }
                    } elseif (strpos($courseMode, 'full') !== false) {
                        // Handle full course orders (full, onetoone_full, onetomany_full)
                        $newEndDate = Carbon::parse($batch->start_date)->addMonths(6)->format('Y-m-d');
                        $order->end_date = $newEndDate;
                    }
                    // For any other course mode, no date field is changed

                    // Save the order
                    $order->update();
                }
            }
        }

        return redirect()->route('admin.batch.batchassign',['id'=>$request->bid])->withFlashSuccess("Batch has been assigned successfully");

}

    /**
     * Show available mock tests for a batch (Admin View)
     * Shows ALL mock tests for the course - NO chapter progress filter
     * Admin can select any mock test to assign to the batch
     */
    public function availableMockTests($id)
    {
        $batch = Batch::findOrFail($id);
        $course = Course::find($batch->cid);
        
        // Get batch course ID
        $courseId = $batch->cid;
        
        // Get completed lesson/chapter IDs for this batch (for display purposes only)
        $completedLessonIds = \App\Models\LessionComplete::where('batch_id', $id)
            ->where('status', 'completed')
            ->pluck('lession_id')
            ->toArray();
        
        // First, get all mock series for this course
        $mockSeriesIds = DB::table('mock_series')
            ->where('course_id', $courseId)
            ->pluck('id')
            ->toArray();
        
        // Get ALL mock tests from mock_list table - NO FILTERING by chapter progress
        // Admin can assign any mock test regardless of batch progress
        $mockTests = DB::table('mock_list')
            ->join('mock_series', 'mock_series.id', '=', 'mock_list.mock_series_id')
            ->whereIn('mock_list.mock_series_id', $mockSeriesIds)
            ->select(
                'mock_list.id',
                'mock_list.name',
                'mock_list.description',
                'mock_list.total_questions',
                'mock_list.duration',
                'mock_list.section_questions',
                'mock_list.sort_order',
                'mock_series.id as series_id',
                'mock_series.name as series_name',
                'mock_series.detail as series_detail'
            )
            ->orderBy('mock_series.id')
            ->orderBy('mock_list.sort_order')
            ->get();
        
        // Get already assigned mock test IDs for this batch (checking mock_list_id)
        $assignedMockIds = \App\Models\BatchMockTest::where('batch_id', $id)
                            ->pluck('mock_list_id')
                            ->toArray();
        
        return response()->json([
            'success' => true,
            'mockTests' => $mockTests,
            'assignedMockIds' => $assignedMockIds,
            'batchName' => $batch->name,
            'courseName' => $course->title ?? ''
        ]);
    }
    
    /**
     * Save selected mock tests for a batch
     */
    public function saveMockTests(Request $request, $id)
    {
        $request->validate([
            'mock_test_ids' => 'required|array',
            'mock_test_ids.*' => 'exists:mock_list,id'
        ]);
        
        $batch = Batch::findOrFail($id);
        
        $mocksToAdd = $request->input('mocks_to_add', []);
        $mocksToRemove = $request->input('mocks_to_remove', []);
        $allCurrentMocks = $request->mock_test_ids;
        
        // Handle removals - delete only the specific mock tests being removed
        if (!empty($mocksToRemove)) {
            \App\Models\BatchMockTest::where('batch_id', $id)
                ->whereIn('mock_list_id', $mocksToRemove)
                ->delete();
        }
        
        // Handle additions - add only new mock tests
        if (!empty($mocksToAdd)) {
            // Get the current max sort order
            $maxSortOrder = \App\Models\BatchMockTest::where('batch_id', $id)->max('sort_order') ?? -1;
            
            foreach ($mocksToAdd as $mockTestId) {
                // Check if this mock test is not already assigned (safety check)
                $exists = \App\Models\BatchMockTest::where('batch_id', $id)
                    ->where('mock_list_id', $mockTestId)
                    ->exists();
                
                if (!$exists) {
                    // Get the mock_series_id for this mock test
                    $mockTest = \App\Models\MockList::find($mockTestId);
                    if ($mockTest) {
                        \App\Models\BatchMockTest::create([
                            'batch_id' => $id,
                            'mock_series_id' => $mockTest->mock_series_id,
                            'mock_list_id' => $mockTestId,
                            'sort_order' => ++$maxSortOrder,
                            'is_active' => 0,  // Default to inactive until manually activated
                            'scheduled_at' => null  // No schedule by default
                        ]);
                    }
                }
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Mock tests updated successfully. Schedules and activation status preserved for remaining tests.'
        ]);
    }

    /**
     * Show mock results page for a batch
     */
    public function mockResults($id)
    {
        $batch = Batch::findOrFail($id);
        $course = Course::findOrFail($batch->cid);
        
        return view('backend.batch.mock-results', compact('batch', 'course'));
    }
    
    /**
     * Get list of students for a batch
     */
    public function getStudentsList($id)
    {
        $studentBatches = StudentTeacherBatch::where('bid', $id)->get();
        
        $students = [];
        foreach ($studentBatches as $stb) {
            $user = User::find($stb->uid);
            if ($user) {
                $students[] = [
                    'id' => $user->id,
                    'name' => trim($user->first_name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name),
                    'email' => $user->email
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'students' => $students
        ]);
    }
    
    /**
     * Get list of mock tests assigned to a batch
     */
    public function getMockTestsList($id)
    {
        $mockTests = \App\Models\BatchMockTest::where('batch_id', $id)
            ->with(['mockList:id,name,description,total_questions,duration', 'mockSeries:id,name'])
            ->get()
            ->map(function($item) {
                $mockList = $item->mockList;
                $mockSeries = $item->mockSeries;
                
                if (!$mockList) {
                    return null;
                }
                
                return [
                    'id' => $item->id,
                    'mock_list_id' => $mockList->id,
                    'name' => $mockList->name,
                    'series_name' => $mockSeries ? $mockSeries->name : 'N/A',
                    'description' => $mockList->description,
                    'total_questions' => $mockList->total_questions,
                    'duration' => $mockList->duration
                ];
            })
            ->filter() // Remove null entries
            ->values();
        
        return response()->json([
            'success' => true,
            'mockTests' => $mockTests
        ]);
    }
    
    /**
     * Get student's result for a specific mock test
     */
    public function getStudentMockResult($student_id, $mock_id)
    {
        // Find the exam record for this student and mock test
        $exam = \App\Models\MyExam::where('user_id', $student_id)
            ->where('exam_id', $mock_id)
            ->where('status', 'completed')
            ->latest()
            ->first();
        
        if (!$exam) {
            return response()->json([
                'success' => false,
                'message' => 'No completed exam found for this student and mock test.'
            ]);
        }
        
        // Calculate result using the same logic as answer key
        $questions = json_decode($exam->questions, true);
        $userAnswers = json_decode($exam->answers, true) ?? [];
        
        $totalQuestions = 0;
        $correctAnswers = 0;
        $wrongAnswers = 0;
        $unattempted = 0;
        
        foreach ($questions as $sectionId => $questionIds) {
            foreach ($questionIds as $qid) {
                $totalQuestions++;
                $question = \App\Models\Question::find($qid);
                
                if (!$question) {
                    continue;
                }
                
                // Parse options - preserve base64 images
                $options = $question->options;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                $optionKeyMapping = []; // Map old keys to new numeric indices
                
                if (is_array($options)) {
                    $index = 0;
                    foreach ($options as $key => $opt) {
                        $optionKeyMapping[$key] = $index; // Map 'option1' => 0, 'option2' => 1, etc.
                        $index++;
                    }
                }
                
                // Get user answer and correct answer, convert to numeric index
                $userAnswer = isset($userAnswers[$qid]) ? $userAnswers[$qid] : null;
                $correctAnswer = $question->correct_answer;
                
                // Convert option keys to numeric indices
                if ($userAnswer !== null && isset($optionKeyMapping[$userAnswer])) {
                    $userAnswer = $optionKeyMapping[$userAnswer];
                }
                if (isset($optionKeyMapping[$correctAnswer])) {
                    $correctAnswer = $optionKeyMapping[$correctAnswer];
                }
                
                $isCorrect = ($userAnswer !== null && $userAnswer == $correctAnswer);
                $isAttempted = ($userAnswer !== null);
                
                if ($isAttempted) {
                    if ($isCorrect) {
                        $correctAnswers++;
                    } else {
                        $wrongAnswers++;
                    }
                } else {
                    $unattempted++;
                }
            }
        }
        
        $score = $correctAnswers;
        $percentage = ($totalQuestions > 0) ? ($correctAnswers / $totalQuestions) * 100 : 0;
        
        return response()->json([
            'success' => true,
            'result' => [
                'exam_id' => $exam->id,
                'exam_date' => \Carbon\Carbon::parse($exam->exam_date_time)->format('d M Y, h:i A'),
                'duration' => $exam->duration,
                'time_spent' => $exam->time_spent ? gmdate('H:i:s', $exam->time_spent) : 'N/A',
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'unattempted' => $unattempted,
                'score' => $score,
                'percentage' => number_format($percentage, 1),
                'status' => 'Completed'
            ]
        ]);
    }


}
 