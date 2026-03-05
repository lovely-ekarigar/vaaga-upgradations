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
use App\Models\DemoBatch;
use App\Models\Recording;
use App\Models\StudentCommitment;
use Carbon\Carbon;

// use App\Models\Assignment;
use App\Models\Elearn;
use App\Models\Lesson;
use App\Models\Objection;
use App\Models\Test;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\BatchUpload;
use App\Models\TestResponse;
use App\Models\CourseContent;
use App\Models\LessionComplete;
use App\Models\Assignment;
use App\Models\MockList;
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
use App\Models\AiQuestion;
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
use App\Models\ExamMyTest;


use App\Models\Media;
use App\Models\AiSensy;
use URL;
use Auth;
use Mail;

use GuzzleHttp\Client;

use PhpOffice\PhpWord\IOFactory;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Str;
class MyclassController extends Controller
{


public function runningStatusExam(){
    
    // Fetch exams from local database that are active (status = 'started')
    // and have recent activity (last_ping within last 5 minutes)
    $recentlyActive = \App\Models\MyExam::where('status', 'started')
        ->where('last_ping', '>=', time() - (5 * 60)) // Active within last 5 minutes
        ->with(['user', 'exam'])
        ->get();
    
    $exams = [];
    
    foreach ($recentlyActive as $myExam) {
        $testName = 'Unknown Test';
        
        // Determine if it's a test series or mock test
        if ($myExam->test_series_purchase_id) {
            // It's a test series exam
            $testList = \App\Models\TestList::find($myExam->exam_id);
            $testName = $testList ? $testList->name : 'Test Series Exam';
        } elseif ($myExam->batch_mock_test_id) {
            // It's a mock test
            $mockList = \App\Models\MockList::find($myExam->exam_id);
            $testName = $mockList ? $mockList->name : 'Mock Test';
        }
        
        $exams[] = [
            'name' => $myExam->user ? $myExam->user->name : 'Unknown Student',
            'test' => $testName,
            'last_update' => $myExam->last_ping,
            'question' => $myExam->current_question ?? 1
        ];
    }
    
    return view('backend.myclass.trackliveexam', compact('exams'));
}


public function tutorwaiting(Request $request){
    
    dd($request->all());
    
}


public function getLengthOfClass()
{
    $recordings = Recording::whereNull('recording_date')->get();

    $el = new Elearn();

    foreach ($recordings as $recording) {
        // Call API using api_class_id as meetingID
        $meetInfo = $el->eClass("getRecordings", [
            'meetingID' => $recording->api_class_id
        ]);

        // Check if length exists in the API response
        if (
            isset($meetInfo['recordings']['recording']['playback']['format']['length'])
            && is_numeric($meetInfo['recordings']['recording']['playback']['format']['length'])
        ) {
            $length = $meetInfo['recordings']['recording']['playback']['format']['length'];

            // Update the length in database
            $recording->length = $length;
            $recording->recording_date = date("Y-m-d",$meetInfo['recordings']['recording']['startTime']/1000);
            $recording->startTime = $meetInfo['recordings']['recording']['startTime'];
            $recording->endTime = $meetInfo['recordings']['recording']['endTime'];
            $recording->save();

            echo "Updated Recording ID {$recording->id} with length {$length} <br>";
        } else {
            echo "Length not found for Recording ID {$recording->id} <br>";
        }
    }
}

    //shruti
    public function studyMaterial($id)
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

            return view('backend.myclass.study-material',compact('list','lession_complete_list','batch_list'));
    }
    //shruti

//    public function viewMaterial($lesson_id, $batch_id)
// {
//     // Fetch the lesson
//     $lesson = Lesson::find($lesson_id);

//     // Fetch all uploaded files for this batch
//     $downloads = BatchUpload::where('bid', $batch_id)
//                             ->orderBy('id','desc')
//                             ->get();
// // dd($lesson, $downloads);

//     return view('backend.myclass.view-material', compact('lesson', 'batch_id', 'downloads'));
// }
public function viewMaterial($lesson_id, $batch_id)
{
    // 1. Batch se course nikaalo
    $batch = Batch::findOrFail($batch_id);
    $course_id = $batch->cid;

    // 2. Sidebar ke liye course ke saare lessons
    $lessons = Lesson::where('course_id', $course_id)
                    ->orderBy('position', 'asc')
                    ->get();

    // 3. Current lesson
    $lesson = Lesson::findOrFail($lesson_id);

    // 4. Check lesson_complete table
    $lessonStatus = LessionComplete::where('batch_id', $batch_id)
                    ->where('lession_id', $lesson_id)
                    ->whereIn('status', ['ongoing', 'completed'])
                    ->first();

    // 5. Default empty
    $lessonMedia = collect();

    // 6. Agar allowed hai tabhi PDFs/Documents lao
    if ($lessonStatus) {
        $lessonMedia = Media::where('model_id', $lesson_id)
            ->where('model_type', 'App\Models\Lesson')
            ->where(function ($q) {
                $q->where('type', 'lesson_pdf')
                  ->orWhere('type', 'application/pdf')
                  ->orWhere('type', 'application/msword')
                  ->orWhere('type', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')
                  ->orWhere(function ($subQ) {
                      // Check file_name extension for doc/docx/pdf
                      $subQ->where('file_name', 'like', '%.docx')
                           ->orWhere('file_name', 'like', '%.doc')
                           ->orWhere('file_name', 'like', '%.pdf');
                  });
            })
            ->orderBy('id', 'desc')
            ->get();
    }
//     dd([
//     'lesson' => $lesson,
//     'lessonStatus' => $lessonStatus,
//     'lessonMedia' => $lessonMedia,
// ]);

    return view('backend.myclass.view-material', compact(
        'lesson',
        'lessons',
        'lessonMedia',
        'lessonStatus',
        'batch_id'
    ));
}


//shruti
public function viewPdf($id)
{
    // Validate ID is numeric and not zero
    if (!is_numeric($id) || $id <= 0) {
        abort(400, 'Invalid media ID provided');
    }
    
    $media = Media::findOrFail($id);
    
    // Ensure file_name exists
    if (empty($media->file_name)) {
        abort(404, 'File information is incomplete');
    }
    
    // Get file extension reliably
    $fileName = $media->file_name;
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Check if file exists on disk
    $filePath = public_path('storage/uploads/' . $media->file_name);
    if (!file_exists($filePath)) {
        abort(404, 'File not found on server');
    }
    
    // For PDF files, use the PDF viewer
    if ($fileExt === 'pdf') {
        return view('backend.myclass.view-pdf', compact('media'));
    }
    
    // For Word documents, use Mammoth.js client-side viewer
    if (in_array($fileExt, ['docx', 'doc'])) {
        return view('backend.myclass.view-docx-mammoth', compact('media'));
    }
    
    // Default: redirect to file URL
    return redirect(asset('storage/uploads/' . $media->file_name));
}

public function viewDoc($id)
{
    // DEBUG: Log entry point
    \Log::info('viewDoc called with ID: ' . $id);
    
    if (!is_numeric($id) || $id <= 0) {
        \Log::error('viewDoc: Invalid media ID: ' . $id);
        abort(400, 'Invalid media ID');
    }
    
    // DEBUG: Log before database query
    \Log::info('viewDoc: Looking for media with ID: ' . $id);
    
    $media = \App\Models\Media::find($id);
    
    if (!$media) {
        \Log::error('viewDoc: Media not found for ID: ' . $id);
        abort(404, 'Media record not found for ID: ' . $id);
    }
    
    // DEBUG: Log media found
    \Log::info('viewDoc: Media found', ['id' => $media->id, 'file_name' => $media->file_name]);
    
    if (empty($media->file_name)) {
        \Log::error('viewDoc: File name empty for media ID: ' . $id);
        abort(404, 'File not found');
    }
    
    $ext = strtolower(pathinfo($media->file_name, PATHINFO_EXTENSION));
    if (!in_array($ext, ['docx', 'doc'])) {
        \Log::error('viewDoc: Not a document file', ['media_id' => $id, 'extension' => $ext]);
        abort(404, 'Not a document file');
    }
    
    \Log::info('viewDoc: Rendering view for media ID: ' . $id);
    
    // Read file content and encode as base64 for embedded viewing
    $filePath = storage_path('app/public/uploads/' . $media->file_name);
    $fileContent = file_get_contents($filePath);
    $fileContentBase64 = base64_encode($fileContent);
    
    return view('backend.myclass.view-docx-embed', compact('media', 'fileContentBase64'));
}

public function serveDoc($id)
{
    // Validate ID is numeric and not zero
    if (!is_numeric($id) || $id <= 0) {
        abort(400, 'Invalid media ID provided');
    }
    
    $media = Media::findOrFail($id);
    
    // Ensure file_name exists
    if (empty($media->file_name)) {
        abort(404, 'File information is incomplete');
    }
    
    // Get file extension reliably
    $fileName = $media->file_name;
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Verify it's a DOCX file
    if (!in_array($fileExt, ['docx', 'doc'])) {
        abort(404, 'Not a document file');
    }
    
    // Check if file exists on disk
    $filePath = storage_path('app/public/uploads/' . $media->file_name);
    if (!file_exists($filePath)) {
        abort(404, 'File not found on server');
    }
    
    // Serve the file with inline Content-Disposition for browser's built-in viewer
    $contentType = $fileExt === 'docx' 
        ? 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        : 'application/msword';
    
    return response()->file($filePath, [
        'Content-Type' => $contentType,
        'Content-Disposition' => 'inline; filename="' . $media->file_name . '"',
    ]);
}

public function runningStatus(){
    $el = new Elearn();
    $meetings = $el->eClass("getMeetings",[]);
    $meetings = $meetings['meetings'] ?? [];
    
    // Fetch today's recordings as fallback when BBB API doesn't return meetings
    // This handles cases where meeting is on a different BBB server
    $today = date('Y-m-d');
    $todaysRecordings = Recording::whereDate('created_at', $today)
        ->whereNotNull('api_class_id')
        ->orderBy('id', 'desc')
        ->get();
    
    // Create a list of active classes from recordings for display
    // Also try to fetch meeting info with attendees from BBB
    $activeClasses = [];
    $meetingAttendees = []; // Store attendees from BBB API
    
    foreach($todaysRecordings as $recording){
        $batch = Batch::where('parent_api_class_id', $recording->parent)->first();
        
        // Try to get meeting info from BBB API (includes attendees)
        $meetingInfo = $el->eClass("getMeetingInfo", ["meetingID" => $recording->api_class_id]);
        $attendees = [];
        $participantCount = '-';
        $moderatorCount = '-';
        
        if(isset($meetingInfo['returncode']) && $meetingInfo['returncode'] == 'SUCCESS'){
            // Meeting found on BBB
            $participantCount = $meetingInfo['participantCount'] ?? '-';
            $moderatorCount = $meetingInfo['moderatorCount'] ?? '-';
            
            // Get attendees
            if(isset($meetingInfo['attendees'])){
                $attendees = $meetingInfo['attendees']['attendee'] ?? [];
                // Handle single attendee case
                if(isset($attendees['fullName'])){
                    $attendees = [$attendees];
                }
            }
            
            // Store attendees for display
            $meetingAttendees[$recording->api_class_id] = $attendees;
        }
        
        $activeClasses[] = [
            'meetingName' => $batch ? $batch->name : 'Unknown Class',
            'meetingID' => $recording->api_class_id,
            'internalMeetingID' => $recording->api_class_id,
            'startTime' => strtotime($recording->created_at) * 1000,
            'participantCount' => $participantCount,
            'moderatorCount' => $moderatorCount,
            'running' => 'true',
            'recording' => $recording,
            'batch' => $batch,
            'attendees' => $attendees
        ];
    }
    
    // Fetch today's student joins with user and batch info
    $studentJoins = [];
    try {
        $studentJoins = \App\Models\StudentJoin::with(['user', 'batch'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->get();
    } catch (\Exception $e) {
        \Log::warning('Could not fetch student joins - table may have missing columns');
    }
    
    // Group joins by batch_id for easier display
    $joinsByBatch = [];
    foreach($studentJoins as $join){
        $batchId = $join->batch_id;
        if(!isset($joinsByBatch[$batchId])){
            $joinsByBatch[$batchId] = [];
        }
        $joinsByBatch[$batchId][] = $join;
    }
    
    return view('backend.myclass.tracklive', compact('meetings', 'studentJoins', 'joinsByBatch', 'activeClasses'));
}

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
    
    
  public function MyExamUploadGenerate(Request $request, $id)
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

    $questionsJson = null;

    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $fname = time() . "." . $file->getClientOriginalExtension();
        $filepath = public_path('storage/exams/' . $fname);
        $file->move(public_path('storage/exams'), $fname);

        // Extract text
        $extension = strtolower($file->getClientOriginalExtension());
        $text = '';

        try {
            if (in_array($extension, ['doc', 'docx'])) {
                $doc = IOFactory::load($filepath);
                $text = $doc->getSections()[0]->getElements()[0]->getText();
            } elseif ($extension === 'pdf') {
                $parser = new Parser();
                $pdf = $parser->parseFile($filepath);
                $text = $pdf->getText();
                // dd($text);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Unable to read file content.']);
        }

        // Only continue if text is valid
        if (Str::length($text) > 100) {
            $prompt = <<<PROMPT
Extract all  questions and options from the following text. remove a,b,c,d etc from option, also include answer index number. Return in this JSON format:

[
  {
    "question": "..."
  },
  ...
]

Text:
$text
PROMPT;

            $client = new Client();
            try {
                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                        'Content-Type'  => 'application/json',
                    ],
                    'json' => [
                        'model' => 'gpt-4',
                        'messages' => [
                            ['role' => 'system', 'content' => 'You are an expert in parsing exam question papers.'],
                            ['role' => 'user', 'content' => $prompt],
                        ],
                        'temperature' => 0.2,
                    ]
                ]);

                $data = json_decode($response->getBody(), true);
                $raw = $data['choices'][0]['message']['content'] ?? '';
                $json = json_decode($raw, true);
                $questionsJson = json_encode($json ?: ['raw' => $raw]);
              if($json){
                  $aiq = new AiQuestion();
                  $aiq->batch_id = $id;
                  $aiq->question = $raw;
                  $aiq->save();
              }

            } catch (\Exception $e) {
                return back()->withErrors(['openai' => 'Error in OpenAI API: ' . $e->getMessage()]);
            }

           
        }
    }

    return redirect()->back()->withFlashSuccess('Subjective Exam Uploaded and Parsed Successfully');
}
      public function MyExamUpload($id)
    {
        $batch = Batch::find($id);

       $assignment_list = SubjectiveExam::where('batch_id',$id)->get();
       $aiqlist= AiQuestion::where("batch_id",$id)->orderBy("id","desc")->get();

        return view('backend.myclass.exam-upload',compact('batch','assignment_list','aiqlist'));
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
    $assignment->exam_date = date("Y-m-d", strtotime($request->date));

    $questionsJson = null;

    if ($request->has('file')) {
        $file = $request->file('file');
        $fname = time() . "." . $file->getClientOriginalExtension();
        $file->move(public_path('storage/exams'), $fname);
        $assignment->file = 'storage/exams/' . $fname;

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

        $assignment_list = Assignment::where('batch_id',$id)->orderBy("id","desc")->get();

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
        
        
            $batchUsers = StudentTeacherBatch::where("bid",$request->batch_id)->get();
    
    
    foreach ($batchUsers as $bu){
        
        $bUser = User::find($bu->uid);
          $commit = StudentCommitment::where("batch_id",$request->batch_id)->where("student_id",$bu->uid)->first();
  
    if($commit->completion_date==null || date("Y-m-d")<=$commit->completion_date){
        if($bUser){
            
              $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'homework_upload',
            'destination' => '+91'.$bUser->phone,
            'userName' => $bUser->name,
            'source' => 'homework_upload',
            'templateParams' => [],
            'tags' => ['homework_upload', 'new-homework_upload'],
            'attributes' => ['user_id' => $bUser->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
        }
    }
    }



        return redirect()->back()->withFlashSuccess('Assignment Upload sucessfully');


    }
    
    public function waiting($id){
        $batch = Batch::where('parent_api_class_id',$id)->first();
        if(!$batch){
            return abort(404);
        }
        $course = Course::find($batch->cid);
        $tb = TeacherBatch::where("bid",$batch->id)->first();
        $teacher = User::find($tb->tid);
        
        // For testing: Override with Demo Tutor if you want to test with specific tutor
        // Uncomment the next 2 lines and replace with Demo Tutor's user ID
        // $demoTutor = User::find(DEMO_TUTOR_USER_ID_HERE);
        // if($demoTutor) $teacher = $demoTutor;
       
       return view('backend.myclass.waiting', compact('batch','course','teacher','id'));
    }
    
    
    public function checkWaiting($id){
        
         $meetid=Recording::where("parent",$id)->where("created_at",">=",date("Y-m-d 00:00:00"))->orderBy("id","desc")->first();
           if($meetid){
               
               return response()->json(['can_join'=>true,'api_id'=>$meetid->api_class_id]);
          

            }else{
               return response()->json(['can_join'=>false,'api_id'=>'']);
            }
    }
    

public function commitment($id)
{
    $batch = Batch::find($id);
    
    $commit = StudentCommitment::where("batch_id",$id)->where("student_id",Auth::user()->id)->first();
     // dd($commit);
  
    if($commit){
       $batch->total_class = $commit->total_class;
       $batch->total_test = $commit->total_test; 
       
        
    }
    
    

    $userId = Auth::id(); 
    
    
     $test_list=[];
       
    $examUser = ExamUser::where("sync_id",Auth::user()->id)->first();

    if($examUser){
        
        $examBatchUsers = ExamBatchUser::where("user_id",$examUser->id)->get()->pluck("batch_id")->toArray();
        $examBatch = ExamBatch::where("sync_id",$batch->id)->first();
         
       $examTests = ExamBatchTest::whereIn("batch_id",[$examBatch->id])->orderBy("id","desc")->get();
    
       foreach ($examTests as $et){
           
           $test = ExamTest::find($et->test_id);
           $mytest = ExamMyTest::where("user_id",$examUser->id)->where("test_id",$et->test_id)->where('status','submitted')->first();
           //Closing submitted with ref ticket no 131.
          // $mytest = ExamMyTest::where("user_id",$examUser->id)->where("test_id",$et->test_id)->where('status','submitted')->first();
           //if($mytest){ 
           $et->test = $test;
           $et->mytest = $mytest;
           $test_list[] = $et;
          // }
       }
       
    }
    
    
  

   $listQuery = Recording::where("parent", $batch->parent_api_class_id)
    ->orderBy("id", "desc")
    ->with(['objection' => function($q) use ($userId) {
        $q->where('objection_by', $userId);
    }]);

// Apply joining date filter if available
if ($commit && !empty($commit->joining_date)) {
    $listQuery->whereDate('recording_date', '>=', $commit->joining_date);
}
if ($commit && !empty($commit->completion_date)) {
    $listQuery->whereDate('recording_date', '<=', $commit->completion_date);
}
$list = $listQuery->get();
        
    // Fetch mock tests for this batch
    $mock_list = [];
    $allMockTests = DB::table('batch_mock_tests as bmt')
        ->join('mock_list as ml', 'bmt.mock_list_id', '=', 'ml.id')
        ->where('bmt.batch_id', $batch->id)
        ->whereNotNull('bmt.mock_list_id')
        ->select('ml.*', 'bmt.id as batch_mock_test_id', 'bmt.batch_id', 'bmt.scheduled_at', 'bmt.end_date', 'bmt.is_active', 'bmt.mock_series_id')
        ->orderBy('bmt.scheduled_at')
        ->get();
    
    foreach ($allMockTests as $mockTest) {
        // Check if user has attempted/submitted this mock
        $myExam = \App\Models\MyExam::where('batch_mock_test_id', $mockTest->batch_mock_test_id)
            ->where('user_id', Auth::user()->id)
            ->where('exam_id', $mockTest->id)
            ->whereIn('status', ['completed'])
            ->first();
        
        $mockTest->myExam = $myExam;
        $mockTest->is_submitted = (bool)$myExam;
        
        // Only include mock tests that have a scheduled date or have been attempted
        if ($mockTest->scheduled_at || $mockTest->is_submitted) {
            $mock_list[] = $mockTest;
        }
    }
    
    // dd($list);

    return view('backend.myclass.commitment', compact('list', 'batch','test_list','mock_list'));
}


public function storeObjection(Request $request)
    {
        $request->validate([
            'recording_id' => 'required|integer',
            'reason' => 'required|string',
        ]);

        Objection::create([
            'recording_id' => $request->recording_id,
            'reason' => $request->reason,
            'status' => 'raised',
            'objection_by' => Auth::id(), // or another user identifier
        ]);

        return response()->json(['message' => 'Objection submitted successfully.']);
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
    $is=StudentJoin::where("user_id",$u->uid)->whereDate("created_at",date("Y-m-d",strtotime($date)))->first();
    if($is){
        $st["present"]=true;
        $st["time"]=$is->created_at ? $is->created_at->format('h:i A') : '';

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
    $is=StudentJoin::where("user_id",$u->uid)->whereDate("created_at",$request->date)->first();
    if($is){
        $st["present"]=true;
        $st["time"]=$is->created_at ? $is->created_at->format('h:i A') : '';

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
 if(!$file){
    return redirect()->back()->withFlashDanger("Please select a file to upload");
   }
   
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



            $batchUsers = StudentTeacherBatch::where("bid",$request->bid)->get();
    
    
    foreach ($batchUsers as $bu){
        
        $bUser = User::find($bu->uid);
          $commit = StudentCommitment::where("batch_id",$request->bid)->where("student_id",$bu->uid)->first();
    
    if($commit->completion_date==null || date("Y-m-d")<=$commit->completion_date){
        if($bUser){
            
              $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'material_upload',
            'destination' => '+91'.$bUser->phone,
            'userName' => $bUser->name,
            'source' => 'material_upload',
            'templateParams' => [],
            'tags' => ['material_upload', 'new-material_upload'],
            'attributes' => ['user_id' => $bUser->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
        }
    }
    }
    
    
    
      return redirect()->route('admin.myclass.upload',["id"=>$request->bid])->withFlashSuccess("File uploaded");  
   }else{
    return redirect()->route('admin.myclass.upload',["id"=>$request->bid])->withFlashDanger("Unsupported file type");  
   }
      

} 


public function suspend(Request $request){
    
    // dd($request->all());
      $batch = Batch::find($request->batch_id);
      $batchUsers = StudentTeacherBatch::where("bid",$request->batch_id)->get();
    
     foreach ($batchUsers as $bu){
        //  $batch=Batch::find($bu->bid);
        
        $bUser = User::find($bu->uid);
                 $commit = StudentCommitment::where("batch_id",$bu->bid)->where("student_id",$bu->uid)->first();
    
    if($commit->completion_date==null || date("Y-m-d")<=$commit->completion_date){
        if($bUser){
            
              $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'class_cancellation_new',
            'destination' => '+91'.$bUser->phone,
            'userName' => $bUser->name,
            'source' => 'material_upload',
            'templateParams' => [date("d-M-Y",strtotime($request->suspend_date)),$batch->name],
            'tags' => ['class_cancellation', 'new-class_cancellation'],
            'attributes' => ['user_id' => $bUser->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
       
        }
         
        }
    }
    
    // $numbers = [env('NOTIFY_NUMBER1'),env('NOTIFY_NUMBER2')];
     $numbers = [env('NOTIFY_NUMBER1')];
     foreach ($numbers as $bu){
        
       
            
              $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'class_cancellation_new',
            'destination' => '+91'.$bu,
            'userName' => "Admin",
            'source' => 'material_upload',
            'templateParams' => [date("d-M-Y",strtotime($request->suspend_date)),$batch->name],
            'tags' => ['class_cancellation', 'new-class_cancellation'],
            'attributes' => ['user_id' => $bu],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
       
        
    }
    
      return redirect()->back()->withFlashSuccess("Class suspension notice sent to students."); 
}

public function mockTestsPage($batch_id){
    try {
        // Debug
        \Log::info('mockTestsPage called with batch_id: ' . $batch_id);
        
        $batch = Batch::find($batch_id);
        
        if (!$batch) {
            \Log::error('Batch not found: ' . $batch_id);
            return back()->with('error', 'Batch not found');
        }
        
        \Log::info('Batch found: ' . $batch->name);
        
        // Get completed and ongoing (in progress) lesson/chapter IDs for this batch (for tutor view filtering)
        $completedLessonIds = \App\Models\LessionComplete::where('batch_id', $batch_id)
            ->whereIn('status', ['completed', 'ongoing'])
            ->pluck('lession_id')
            ->toArray();
        
        // Get all mock tests assigned to this batch by admin
        $allMockTests = DB::table('batch_mock_tests')
            ->join('mock_list', 'batch_mock_tests.mock_list_id', '=', 'mock_list.id')
            ->where('batch_mock_tests.batch_id', $batch_id)
            ->select(
                'mock_list.id', 
                'mock_list.name',
                'mock_list.section_questions', // Need this for chapter filtering
                DB::raw('COALESCE(batch_mock_tests.is_active, 0) as is_active'),
                'batch_mock_tests.scheduled_at'
            )
            ->get();
        
        // Process all mock tests and add pending status based on chapter progress
        $mockTests = $allMockTests->map(function($mockTest) use ($completedLessonIds) {
            // Decode section_questions
            $sectionQuestions = null;
            if (!empty($mockTest->section_questions)) {
                $sectionQuestions = is_string($mockTest->section_questions) 
                    ? json_decode($mockTest->section_questions, true) 
                    : (is_array($mockTest->section_questions) ? $mockTest->section_questions : null);
            }
            
            // Default: not pending
            $isPending = false;
            $pendingReason = null;
            
            // Check if mock test should be pending due to progress requirements
            if (!empty($sectionQuestions) && is_array($sectionQuestions)) {
                // Extract all chapter IDs from section_questions
                $requiredChapterIds = [];
                foreach ($sectionQuestions as $sectionId => $chapters) {
                    if (is_array($chapters)) {
                        foreach ($chapters as $chapterId => $questionCount) {
                            $requiredChapterIds[] = (int)$chapterId;
                        }
                    }
                }
                
                // Remove duplicates
                $requiredChapterIds = array_unique($requiredChapterIds);
                
                // Check if all required chapters are completed or in progress
                if (!empty($requiredChapterIds)) {
                    $missingChapters = [];
                    foreach ($requiredChapterIds as $requiredChapterId) {
                        if (!in_array($requiredChapterId, $completedLessonIds)) {
                            $missingChapters[] = $requiredChapterId;
                        }
                    }
                    
                    if (!empty($missingChapters)) {
                        $isPending = true;
                        $pendingReason = 'Required chapters not yet completed/ongoing';
                    }
                }
            }
            
            // Add pending status to mock test object
            $mockTest->is_pending = $isPending;
            $mockTest->pending_reason = $pendingReason;
            
            return $mockTest;
        })->values();
        
        \Log::info('Mock tests found (including pending): ' . count($mockTests));
        
        return view('backend.myclass.mock-tests', compact('batch', 'mockTests'));
    } catch (\Exception $e) {
        \Log::error('Error in mockTestsPage: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        return back()->with('error', 'Error loading mock tests page: ' . $e->getMessage());
    }
}

public function getMockTests(Request $request){
    try {
        $batchId = $request->batch_id;
        
        // Get completed and ongoing (in progress) lesson/chapter IDs for this batch (for tutor view filtering)
        $completedLessonIds = \App\Models\LessionComplete::where('batch_id', $batchId)
            ->whereIn('status', ['completed', 'ongoing'])
            ->pluck('lession_id')
            ->toArray();
        
        // Get all mock tests assigned to this batch by admin
        $allMockTests = DB::table('batch_mock_tests')
            ->join('mock_list', 'batch_mock_tests.mock_list_id', '=', 'mock_list.id')
            ->where('batch_mock_tests.batch_id', $batchId)
            ->select(
                'mock_list.id', 
                'mock_list.name',
                'mock_list.section_questions', // Need this for chapter filtering
                DB::raw('COALESCE(batch_mock_tests.is_active, 0) as is_active'),
                'batch_mock_tests.scheduled_at'
            )
            ->get();
        
        // Process all mock tests and add pending status based on chapter progress
        $mockTests = $allMockTests->map(function($mockTest) use ($completedLessonIds) {
            // Decode section_questions
            $sectionQuestions = null;
            if (!empty($mockTest->section_questions)) {
                $sectionQuestions = is_string($mockTest->section_questions) 
                    ? json_decode($mockTest->section_questions, true) 
                    : (is_array($mockTest->section_questions) ? $mockTest->section_questions : null);
            }
            
            // Default: not pending
            $isPending = false;
            $pendingReason = null;
            
            // Check if mock test should be pending due to progress requirements
            if (!empty($sectionQuestions) && is_array($sectionQuestions)) {
                // Extract all chapter IDs from section_questions
                $requiredChapterIds = [];
                foreach ($sectionQuestions as $sectionId => $chapters) {
                    if (is_array($chapters)) {
                        foreach ($chapters as $chapterId => $questionCount) {
                            $requiredChapterIds[] = (int)$chapterId;
                        }
                    }
                }
                
                // Remove duplicates
                $requiredChapterIds = array_unique($requiredChapterIds);
                
                // Check if all required chapters are completed or in progress
                if (!empty($requiredChapterIds)) {
                    $missingChapters = [];
                    foreach ($requiredChapterIds as $requiredChapterId) {
                        if (!in_array($requiredChapterId, $completedLessonIds)) {
                            $missingChapters[] = $requiredChapterId;
                        }
                    }
                    
                    if (!empty($missingChapters)) {
                        $isPending = true;
                        $pendingReason = 'Required chapters not yet completed/ongoing';
                    }
                }
            }
            
            // Add pending status to mock test object
            $mockTest->is_pending = $isPending;
            $mockTest->pending_reason = $pendingReason;
            
            return $mockTest;
        })->values();
        
        return response()->json([
            'success' => true,
            'data' => $mockTests
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in getMockTests: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error fetching mock tests: ' . $e->getMessage()
        ], 500);
    }
}

public function toggleMockStatus(Request $request){
    try {
        $mockId = $request->mock_id;
        $status = $request->status;
        $batchId = $request->batch_id;
        
        // Update the status in batch_mock_tests table
        $updateData = ['is_active' => $status];
        $message = 'Mock test status updated successfully';
        
        if ($status == 1) {
            $updateData['scheduled_at'] = null; // Clear schedule when manually activated
            
            // Set end_date to today's midnight (11:59 PM) when manually activating without schedule
            $updateData['end_date'] = \Carbon\Carbon::now()->endOfDay();
            
            $message = 'Mock test activated and is now visible to students until 11:59 PM today';
            
            // Send notifications when mock test is activated
            $this->sendMockTestNotifications($mockId, $batchId);
        } else {
            // When deactivating, also clear scheduled_at to fully hide the mock
            $updateData['scheduled_at'] = null;
            $message = 'Mock test deactivated and hidden from students';
        }
        
        $updated = DB::table('batch_mock_tests')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mockId)
            ->update($updateData);
        
        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update mock test status'
            ], 400);
        }
    } catch (\Exception $e) {
        \Log::error('Error in toggleMockStatus: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error updating mock test status: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Send notifications to students when a mock test is assigned/activated
 */
private function sendMockTestNotifications($mockId, $batchId)
{
    try {
        \Log::info('============================================');
        \Log::info('STARTING MOCK TEST NOTIFICATION PROCESS');
        \Log::info('============================================');
        
        // Get mock test details
        $mockTest = MockList::find($mockId);
        if (!$mockTest) {
            \Log::error('Mock test not found: ' . $mockId);
            return;
        }
        
        // Get batch details
        $batch = Batch::find($batchId);
        if (!$batch) {
            \Log::error('Batch not found: ' . $batchId);
            return;
        }
        
        \Log::info('Mock Test: ' . $mockTest->name . ' (ID: ' . $mockId . ')');
        \Log::info('Batch: ' . $batch->name . ' (ID: ' . $batchId . ')');
        
        // Create in-platform notification
        $notificationMessage = "Dear Student,<br><b>" . $mockTest->name . "</b> Mock Test has been assigned to your batch <b>" . $batch->name . "</b>. Kindly visit the classes module to attempt the test.";
        
        $notification = new Notification;
        $notification->title = $mockTest->name . " Mock Test Assigned";
        $notification->message = $notificationMessage;
        $notification->batch_type = 'selected';
        $notification->user_type = 'student';
        $notification->batch_list = json_encode([$batchId]);
        $notification->created_by = \Auth::user()->id;
        $notification->save();
        $notificationId = $notification->id;
        
        \Log::info('In-platform notification created (ID: ' . $notificationId . ')');
        
        // Get all students in the batch
        $studentBatches = StudentTeacherBatch::where('bid', $batchId)->get();
        $totalStudents = $studentBatches->count();
        $whatsappSentCount = 0;
        $whatsappSkippedCount = 0;
        $alreadyCompletedCount = 0;
        
        \Log::info('Total students in batch: ' . $totalStudents);
        \Log::info('--------------------------------------------');
        
        foreach ($studentBatches as $index => $stb) {
            // Check if student has already completed this mock test
            $alreadyCompleted = \App\Models\MyExam::where('user_id', $stb->uid)
                ->where('exam_id', $mockId)
                ->where('status', 'completed')
                ->exists();
            
            if ($alreadyCompleted) {
                $alreadyCompletedCount++;
                $user = User::find($stb->uid);
                \Log::info('Student #' . ($index + 1) . ': ' . ($user ? $user->name : 'ID: ' . $stb->uid));
                \Log::info('  ⊗ SKIPPED - Already completed this mock test');
                continue; // Skip notification for students who already completed
            }
            
            // Create user notification
            $userNotification = new UserNotification;
            $userNotification->notification_id = $notificationId;
            $userNotification->user_id = $stb->uid;
            $userNotification->status = '0';
            $userNotification->save();
            
            // Send WhatsApp notification via AISensy
            $user = User::find($stb->uid);
            if ($user && $user->phone) {
                \Log::info('Student #' . ($index + 1) . ': ' . $user->name . ' (ID: ' . $user->id . ')');
                \Log::info('  Phone: +91' . $user->phone);
                
                // Check student commitment
                $commit = StudentCommitment::where("batch_id", $batchId)
                    ->where("student_id", $stb->uid)
                    ->first();
                
                // Send WhatsApp if:
                // 1. No commitment record exists (student is in batch, send notification)
                // 2. OR commitment exists AND (no completion date OR completion date is in future)
                $shouldSendWhatsApp = !$commit || ($commit->completion_date == null || date("Y-m-d") <= $commit->completion_date);
                
                if ($shouldSendWhatsApp) {
                    $whatsappPayload = [
                        'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                        'campaignName' => 'mock_test',
                        'destination' => '+91' . $user->phone,
                        'userName' => ucwords(trim($user->name)),
                        'source' => 'mock_test',
                        'templateParams' => [
                            ucwords(trim($user->name)),
                            trim($mockTest->name),
                            'for',
                            date('d M Y'),
                            date('d M Y')
                        ],
                        'tags' => ['mock_test', 'mock_test_assigned'],
                        'attributes' => ['user_id' => $user->id, 'mock_id' => $mockId, 'batch_id' => $batchId],
                    ];
                    
                    AiSensy::send($whatsappPayload);
                    $whatsappSentCount++;
                    \Log::info('  ✓ WhatsApp notification SENT' . (!$commit ? ' (No commitment record)' : ''));
                } else {
                    $whatsappSkippedCount++;
                    $reason = 'Course completed (completion_date: ' . $commit->completion_date . ')';
                    \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
                }
            } else {
                $whatsappSkippedCount++;
                $reason = !$user ? 'User not found' : 'No phone number';
                \Log::info('Student #' . ($index + 1) . ' (ID: ' . $stb->uid . ')');
                \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
            }
        }
        
        \Log::info('--------------------------------------------');
        \Log::info('NOTIFICATION SUMMARY:');
        \Log::info('  Total Students in Batch: ' . $totalStudents);
        \Log::info('  Already Completed Mock: ' . $alreadyCompletedCount);
        \Log::info('  Notifications Sent: ' . ($totalStudents - $alreadyCompletedCount));
        \Log::info('  WhatsApp Sent: ' . $whatsappSentCount);
        \Log::info('  WhatsApp Skipped: ' . $whatsappSkippedCount);
        \Log::info('============================================');
        \Log::info('MOCK TEST NOTIFICATION PROCESS COMPLETED');
        \Log::info('============================================');
    } catch (\Exception $e) {
        \Log::error('============================================');
        \Log::error('ERROR IN MOCK TEST NOTIFICATION PROCESS');
        \Log::error('Error: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        \Log::error('============================================');
    }
}

/**
 * Send notifications to students when a mock test is SCHEDULED for future date
 */
private function sendScheduledMockTestNotifications($mockId, $batchId, $scheduledDate)
{
    try {
        \Log::info('============================================');
        \Log::info('SCHEDULED MOCK TEST NOTIFICATION PROCESS (FUTURE DATE)');
        \Log::info('============================================');
        
        // Get mock test details
        $mockTest = MockList::find($mockId);
        if (!$mockTest) {
            \Log::error('Mock test not found: ' . $mockId);
            return;
        }
        
        // Get batch details
        $batch = Batch::find($batchId);
        if (!$batch) {
            \Log::error('Batch not found: ' . $batchId);
            return;
        }
        
        $scheduleDateFormatted = $scheduledDate->format('d M Y');
        
        \Log::info('Mock Test: ' . $mockTest->name . ' (ID: ' . $mockId . ')');
        \Log::info('Batch: ' . $batch->name . ' (ID: ' . $batchId . ')');
        \Log::info('Scheduled Date: ' . $scheduleDateFormatted);
        
        // Create in-platform notification with scheduled date
        $notificationMessage = "Dear Student,<br><b>" . $mockTest->name . "</b> Mock Test has been scheduled for your batch <b>" . $batch->name . "</b> on <b>" . $scheduleDateFormatted . "</b>. The test will be available on this date. Kindly visit the classes module to attempt the test.";
        
        $notification = new Notification;
        $notification->title = $mockTest->name . " Mock Test Scheduled";
        $notification->message = $notificationMessage;
        $notification->batch_type = 'selected';
        $notification->user_type = 'student';
        $notification->batch_list = json_encode([$batchId]);
        $notification->created_by = \Auth::user()->id;
        $notification->save();
        $notificationId = $notification->id;
        
        \Log::info('In-platform notification created (ID: ' . $notificationId . ')');
        
        // Get all students in the batch
        $studentBatches = StudentTeacherBatch::where('bid', $batchId)->get();
        $totalStudents = $studentBatches->count();
        $whatsappSentCount = 0;
        $whatsappSkippedCount = 0;
        $alreadyCompletedCount = 0;
        
        \Log::info('Total students in batch: ' . $totalStudents);
        \Log::info('--------------------------------------------');
        
        foreach ($studentBatches as $index => $stb) {
            // Check if student has already completed this mock test
            $alreadyCompleted = \App\Models\MyExam::where('user_id', $stb->uid)
                ->where('exam_id', $mockId)
                ->where('status', 'completed')
                ->exists();
            
            if ($alreadyCompleted) {
                $alreadyCompletedCount++;
                $user = User::find($stb->uid);
                \Log::info('Student #' . ($index + 1) . ': ' . ($user ? $user->name : 'ID: ' . $stb->uid));
                \Log::info('  ⊗ SKIPPED - Already completed this mock test');
                continue; // Skip notification for students who already completed
            }
            
            // Create user notification
            $userNotification = new UserNotification;
            $userNotification->notification_id = $notificationId;
            $userNotification->user_id = $stb->uid;
            $userNotification->status = '0';
            $userNotification->save();
            
            // Send WhatsApp notification via AISensy
            $user = User::find($stb->uid);
            if ($user && $user->phone) {
                \Log::info('Student #' . ($index + 1) . ': ' . $user->name . ' (ID: ' . $user->id . ')');
                \Log::info('  Phone: +91' . $user->phone);
                
                // Check student commitment
                $commit = StudentCommitment::where("batch_id", $batchId)
                    ->where("student_id", $stb->uid)
                    ->first();
                
                // Send WhatsApp if student is active
                $shouldSendWhatsApp = !$commit || ($commit->completion_date == null || date("Y-m-d") <= $commit->completion_date);
                
                if ($shouldSendWhatsApp) {
                    $whatsappPayload = [
                        'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                        'campaignName' => 'mock_test',
                        'destination' => '+91' . $user->phone,
                        'userName' => ucwords(trim($user->name)),
                        'source' => 'mock_test',
                        'templateParams' => [
                            ucwords(trim($user->name)),
                            trim($mockTest->name),
                            'on',
                            $scheduleDateFormatted,
                            $scheduleDateFormatted
                        ],
                        'tags' => ['mock_test', 'mock_test_scheduled'],
                        'attributes' => ['user_id' => $user->id, 'mock_id' => $mockId, 'batch_id' => $batchId, 'scheduled_date' => $scheduleDateFormatted],
                    ];
                    
                    AiSensy::send($whatsappPayload);
                    $whatsappSentCount++;
                    \Log::info('  ✓ WhatsApp notification SENT (Scheduled for: ' . $scheduleDateFormatted . ')' . (!$commit ? ' (No commitment record)' : ''));
                } else {
                    $whatsappSkippedCount++;
                    $reason = 'Course completed (completion_date: ' . $commit->completion_date . ')';
                    \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
                }
            } else {
                $whatsappSkippedCount++;
                $reason = !$user ? 'User not found' : 'No phone number';
                \Log::info('Student #' . ($index + 1) . ' (ID: ' . $stb->uid . ')');
                \Log::info('  ✗ WhatsApp notification SKIPPED - Reason: ' . $reason);
            }
        }
        
        \Log::info('--------------------------------------------');
        \Log::info('NOTIFICATION SUMMARY:');
        \Log::info('  Total Students in Batch: ' . $totalStudents);
        \Log::info('  Already Completed Mock: ' . $alreadyCompletedCount);
        \Log::info('  Notifications Sent: ' . ($totalStudents - $alreadyCompletedCount));
        \Log::info('  WhatsApp Sent: ' . $whatsappSentCount);
        \Log::info('  WhatsApp Skipped: ' . $whatsappSkippedCount);
        \Log::info('  Scheduled Date: ' . $scheduleDateFormatted);
        \Log::info('============================================');
        \Log::info('SCHEDULED MOCK TEST NOTIFICATION COMPLETED');
        \Log::info('============================================');
    } catch (\Exception $e) {
        \Log::error('============================================');
        \Log::error('ERROR IN SCHEDULED NOTIFICATION PROCESS');
        \Log::error('Error: ' . $e->getMessage());
        \Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
        \Log::error('============================================');
    }
}

public function scheduleMock(Request $request){
    try {
        $mockId = $request->mock_id;
        $batchId = $request->batch_id;
        $scheduledAt = $request->scheduled_at;
        
        // If scheduled_at is null, clear the schedule
        if ($scheduledAt === null || $scheduledAt === '') {
            $updated = DB::table('batch_mock_tests')
                ->where('batch_id', $batchId)
                ->where('mock_list_id', $mockId)
                ->update([
                    'scheduled_at' => null,
                    'is_active' => 0 // Set to inactive when clearing schedule
                ]);
            
            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Schedule cleared successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to clear schedule'
                ], 400);
            }
        }
        
        // Validate scheduled date is in the future
        $scheduledDateTime = \Carbon\Carbon::parse($scheduledAt);
        if ($scheduledDateTime->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Scheduled date must be in the future'
            ], 400);
        }
        
        // Update the scheduled_at (publish_at) in batch_mock_tests table
        // Status stays as 0 (inactive/scheduled) until the scheduled time is reached
        // Students will see it automatically when scheduled_at <= now()
        // end_date is same as scheduled_at (available only on that day, 00:00 to 23:59)
        $endDate = $scheduledDateTime->copy(); // Same day, not +1
        
        $updated = DB::table('batch_mock_tests')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mockId)
            ->update([
                'scheduled_at' => $scheduledDateTime,
                'end_date' => $endDate,
                'is_active' => 0 // Keep inactive, will auto-show based on date
            ]);
        
        if ($updated) {
            // Get mock test and batch details for notification
            $mockTest = MockList::find($mockId);
            $batch = Batch::find($batchId);
            $tutor = Auth::user();
            
            // Create notification for admin users
            $message = "Tutor <b>" . $tutor->name . "</b> has rescheduled the mock test <b>" . $mockTest->name . "</b> for batch <b>" . $batch->name . "</b>. New scheduled date: <b>" . $scheduledDateTime->format('d M Y, h:i A') . "</b>";
            
            $notification = new Notification;
            $notification->title = "Mock Test Rescheduled by Tutor";
            $notification->message = $message;
            $notification->batch_type = 'selected';
            $notification->user_type = 'admin';
            $notification->batch_list = json_encode([$batchId]);
            $notification->created_by = Auth::user()->id;
            $notification->save();
            
            // Get all admin users and create user notifications
            $adminUsers = User::whereHas('roles', function($query) {
                $query->where('name', 'administrator');
            })->get();
            
            foreach ($adminUsers as $admin) {
                $userNotification = new UserNotification;
                $userNotification->notification_id = $notification->id;
                $userNotification->user_id = $admin->id;
                $userNotification->status = '0';
                $userNotification->save();
            }
            
            \Log::info('Mock test rescheduled - Admin notification sent', [
                'mock_id' => $mockId,
                'batch_id' => $batchId,
                'tutor' => $tutor->name,
                'scheduled_at' => $scheduledDateTime->format('Y-m-d H:i'),
                'admins_notified' => $adminUsers->count()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Mock test scheduled successfully for ' . $scheduledDateTime->format('Y-m-d H:i') . '. It will auto-appear to students at the scheduled time.'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to schedule mock test'
            ], 400);
        }
    } catch (\Exception $e) {
        \Log::error('Error in scheduleMock: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Error scheduling mock test: ' . $e->getMessage()
        ], 500);
    }
}

public function mockTestQuestions(Request $request, $mock_id){
    try {
        $batchId = $request->batch_id;
        
        // Get mock test details
        $mockTest = DB::table('mock_list')
            ->where('id', $mock_id)
            ->first();
        
        if (!$mockTest) {
            return redirect()->back()->withFlashDanger('Mock test not found.');
        }
        
        // Verify this mock is assigned to the batch
        $batchMock = DB::table('batch_mock_tests')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mock_id)
            ->first();
        
        if (!$batchMock) {
            return redirect()->back()->withFlashDanger('Mock test is not assigned to this batch.');
        }
        
        // Check if there are any saved questions in batch_mock_questions table
        // Load saved questions regardless of is_active status
        $preservedCount = DB::table('batch_mock_questions')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mock_id)
            ->count();
        $hasPreservedQuestions = $preservedCount > 0;
        
        \Log::info('Checking for preserved questions:', [
            'batch_id' => $batchId,
            'mock_id' => $mock_id,
            'preserved_count' => $preservedCount,
            'has_preserved' => $hasPreservedQuestions
        ]);
        
        // Decode sections and section_questions from JSON
        $sections = json_decode($mockTest->sections, true);
        $sectionQuestions = json_decode($mockTest->section_questions, true);
        
        // Handle double-encoded JSON (if sections is a string, decode again)
        if (is_string($sections)) {
            $sections = json_decode($sections, true);
        }
        if (is_string($sectionQuestions)) {
            $sectionQuestions = json_decode($sectionQuestions, true);
        }
        
        \Log::info('Mock Test Sections:', ['sections' => $sections, 'section_questions' => $sectionQuestions]);
        
        if (!is_array($sections) || empty($sections)) {
            \Log::error('Sections is not array or empty', ['sections' => $sections, 'type' => gettype($sections)]);
            return redirect()->back()->withFlashDanger('Mock test sections configuration is incomplete.');
        }
        
        if (!is_array($sectionQuestions) || empty($sectionQuestions)) {
            \Log::error('Section questions is not array or empty', ['section_questions' => $sectionQuestions, 'type' => gettype($sectionQuestions)]);
            return redirect()->back()->withFlashDanger('Mock test questions configuration is incomplete.');
        }
        
        // Prepare data structure for sections and questions
        $sectionsData = [];
        
        if ($hasPreservedQuestions) {
            // Load preserved questions for active mock
            $preservedQuestions = DB::table('batch_mock_questions')
                ->where('batch_id', $batchId)
                ->where('mock_list_id', $mock_id)
                ->orderBy('question_order')
                ->get();
            
            // Group by section_id
            $questionsBySectionId = [];
            foreach ($preservedQuestions as $pq) {
                if (!isset($questionsBySectionId[$pq->section_id])) {
                    $questionsBySectionId[$pq->section_id] = [];
                }
                $questionsBySectionId[$pq->section_id][] = $pq->question_id;
            }
            
            // Build sections data
            foreach ($questionsBySectionId as $sectionId => $questionIds) {
                // Get section name
                $section = DB::table('subjects')->where('id', $sectionId)->first();
                if (!$section) continue;
                
                // Get questions in preserved order
                $questions = Question::whereIn('id', $questionIds)
                    ->orderByRaw('FIELD(id, ' . implode(',', $questionIds) . ')')
                    ->get();
                
                $sectionsData[] = [
                    'id' => $sectionId,
                    'name' => $section->name,
                    'questions' => $questions
                ];
            }
        } else {
            // Show random questions for inactive mock or when no preserved questions
        
        foreach ($sections as $sectionId) {
            // Get section name from subjects table
            $section = DB::table('subjects')->where('id', $sectionId)->first();
            
            if (!$section) {
                \Log::warning('Section not found:', ['section_id' => $sectionId]);
                continue;
            }
            
            $sectionData = [
                'id' => $sectionId,
                'name' => $section->name,
                'difficulty' => $section->difficulty ?? null,
                'questions' => []
            ];
            
            // Get chapter questions for this section
            if (isset($sectionQuestions[$sectionId]) && is_array($sectionQuestions[$sectionId])) {
                $chapterQuestions = $sectionQuestions[$sectionId];
                
                foreach ($chapterQuestions as $chapterId => $questionCount) {
                    if ($questionCount > 0) {
                        // Fetch random questions from questions table, filtered by difficulty
                        $query = Question::where('chapter_id', $chapterId);
                        
                        // Filter by difficulty level if specified for this section
                        if (!empty($section->difficulty)) {
                            $query->where('difficulty', $section->difficulty);
                            \Log::info('Filtering questions by difficulty:', [
                                'section_id' => $sectionId,
                                'chapter_id' => $chapterId,
                                'difficulty' => $section->difficulty
                            ]);
                        }
                        
                        $questions = $query->inRandomOrder()
                            ->limit($questionCount)
                            ->get();
                        
                        \Log::info('Fetched questions:', ['chapter_id' => $chapterId, 'count' => $questions->count()]);
                        
                        // Add questions to section data
                        foreach ($questions as $question) {
                            $sectionData['questions'][] = $question;
                        }
                    }
                }
            }
            
            $sectionsData[] = $sectionData;
        }
        }
        
        return view('backend.myclass.mock-questions', compact('mockTest', 'sectionsData', 'batchId'));
        
    } catch (\Exception $e) {
        \Log::error('Error in mockTestQuestions: ' . $e->getMessage());
        return redirect()->back()->withFlashDanger('Error loading mock test questions: ' . $e->getMessage());
    }
}

public function submitMock(Request $request, $mock_id){
    try {
        $batchId = $request->batch_id;
        $questionsData = $request->questions_data; // JSON string of current questions
        // Use current date if no date is provided
        $scheduledAt = $request->scheduled_at ?? \Carbon\Carbon::now()->format('Y-m-d'); // Date from form
        
        \Log::info('submitMock called - RAW REQUEST DATA', [
            'mock_id' => $mock_id,
            'batch_id' => $batchId,
            'questions_data_raw' => $questionsData,
            'questions_data_length' => strlen($questionsData ?? ''),
            'questions_data_is_empty' => empty($questionsData),
            'scheduled_at' => $scheduledAt,
            'is_ajax' => $request->ajax(),
            'all_request_data' => $request->all()
        ]);
        
        // Determine if should activate now or schedule
        $today = \Carbon\Carbon::now()->startOfDay();
        $scheduledDate = $scheduledAt ? \Carbon\Carbon::parse($scheduledAt)->startOfDay() : $today;
        $shouldActivateNow = $scheduledDate->lte($today);
        
        \Log::info('Scheduling logic:', [
            'today' => $today->toDateString(),
            'scheduled_date' => $scheduledDate->toDateString(),
            'should_activate_now' => $shouldActivateNow
        ]);
        
        // Check if batch mock exists
        $batchMock = DB::table('batch_mock_tests')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mock_id)
            ->first();
        
        // Parse questions data
        $questions = null;
        if (!empty($questionsData) && $questionsData !== '') {
            $questions = json_decode($questionsData, true);
            \Log::info('Successfully parsed questions data:', ['questions' => $questions, 'count' => count($questions ?? [])]);
        } else {
            \Log::info('Questions data is empty or null');
        }
        
        // If questions data is empty or invalid, generate from mock configuration
        if (!$questions || !is_array($questions) || empty($questions)) {
            \Log::info('No valid questions data provided, generating from mock configuration');
            
            // Get the mock test configuration
            $mockTest = \App\Models\MockList::find($mock_id);
            if (!$mockTest) {
                $message = 'Mock test not found';
                \Log::error($message);
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 404);
                }
                return redirect()->back()->withFlashDanger($message);
            }
            
            // Get section_questions configuration
            $sectionQuestions = is_string($mockTest->section_questions) 
                ? json_decode($mockTest->section_questions, true) 
                : $mockTest->section_questions;
            
            if (empty($sectionQuestions) || !is_array($sectionQuestions)) {
                $message = 'No section questions configured for this mock test';
                \Log::warning($message);
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }
                return redirect()->back()->withFlashDanger($message);
            }
            
            // Generate questions from configuration
            $questions = [];
            foreach ($sectionQuestions as $sectionId => $chapters) {
                if (!is_array($chapters)) {
                    continue;
                }
                
                foreach ($chapters as $chapterId => $questionCount) {
                    // Get random questions from this chapter
                    $chapterQuestions = \App\Models\Question::where('chapter_id', $chapterId)
                        ->inRandomOrder()
                        ->limit($questionCount)
                        ->pluck('id')
                        ->toArray();
                    
                    if (!isset($questions[$sectionId])) {
                        $questions[$sectionId] = [];
                    }
                    
                    $questions[$sectionId] = array_merge($questions[$sectionId], $chapterQuestions);
                }
            }
            
            \Log::info('Generated questions from configuration:', ['questions' => $questions]);
        }
        
        // Start transaction
        DB::beginTransaction();
        
        // Delete existing questions for this batch+mock combination (always replace)
        $deletedCount = DB::table('batch_mock_questions')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mock_id)
            ->delete();
        
        \Log::info('Deleted old questions:', ['count' => $deletedCount]);
        
        // Insert new questions (including any refreshed ones)
        $order = 0;
        $insertedCount = 0;
        foreach ($questions as $sectionId => $questionIds) {
            foreach ($questionIds as $questionId) {
                DB::table('batch_mock_questions')->insert([
                    'batch_id' => $batchId,
                    'mock_list_id' => $mock_id,
                    'question_id' => $questionId,
                    'section_id' => $sectionId,
                    'question_order' => $order++,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $insertedCount++;
                \Log::info('Inserted question:', ['question_id' => $questionId, 'section_id' => $sectionId, 'order' => $order - 1]);
            }
        }
        
        \Log::info('Total questions inserted:', ['count' => $insertedCount]);
        
        // Update the mock test status and schedule date
        $updateData = [];
        
        if ($shouldActivateNow) {
            // Today's date - activate immediately, but keep the scheduled_at for record keeping
            // Set end_date to end of today (11:59 PM) so it expires after midnight
            $updateData['is_active'] = 1;
            $updateData['scheduled_at'] = $scheduledDate->toDateString(); // Keep the date for record
            $updateData['end_date'] = $scheduledDate->copy()->endOfDay()->format('Y-m-d H:i:s'); // Available until 11:59 PM today
            $statusMessage = 'Mock test activated immediately and questions saved successfully! (Available until ' . $scheduledDate->format('M d, Y') . ' 11:59 PM)';
        } else {
            // Future date - schedule for later
            // Set end_date to end of scheduled day (11:59 PM) so it expires after midnight
            $updateData['is_active'] = 0;
            $updateData['scheduled_at'] = $scheduledDate->toDateString();
            $updateData['end_date'] = $scheduledDate->copy()->endOfDay()->format('Y-m-d H:i:s'); // Available until 11:59 PM on scheduled day
            $statusMessage = 'Mock test scheduled for ' . $scheduledDate->format('M d, Y') . ' and questions saved successfully! (Available until ' . $scheduledDate->format('M d, Y') . ' 11:59 PM)';
        }
        
        DB::table('batch_mock_tests')
            ->where('batch_id', $batchId)
            ->where('mock_list_id', $mock_id)
            ->update($updateData);
        
        \Log::info('Mock test status updated:', $updateData);
        
        DB::commit();
        
        // Send notifications immediately (whether activated now or scheduled for future)
        if ($shouldActivateNow) {
            // Send "active now" notification
            $this->sendMockTestNotifications($mock_id, $batchId);
        } else {
            // Send "scheduled for future date" notification
            $this->sendScheduledMockTestNotifications($mock_id, $batchId, $scheduledDate);
        }
        
        \Log::info('Mock test submitted successfully');
        
        // Return JSON for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $statusMessage,
                'questions_count' => $insertedCount,
                'is_active' => $updateData['is_active'],
                'scheduled_at' => $updateData['scheduled_at'] ?? null
            ]);
        }
        
        // Redirect based on user role
        if (auth()->user()->hasRole('administrator')) {
            return redirect()->route('admin.myclass.mockTestsPage', $batchId)->with('success', $statusMessage);
        } else {
            return redirect()->route('admin.myclass')->with('success', $statusMessage);
        }
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Error in submitMock: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
        
        // Return JSON error for AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting mock test: ' . $e->getMessage()
            ], 500);
        }
        
        return redirect()->back()->withFlashDanger('Error submitting mock test: ' . $e->getMessage());
    }
}

public function refreshQuestion(Request $request){
    try {
        $chapterId = $request->chapter_id;
        $sectionId = $request->section_id;
        $excludeQuestionIds = $request->exclude_question_ids ?? [];
        
        \Log::info('Refresh question for chapter_id: ' . $chapterId);
        \Log::info('Section ID: ' . $sectionId);
        \Log::info('Excluding question IDs: ' . json_encode($excludeQuestionIds));
        
        // Get a random question from the same chapter, excluding already displayed questions
        $query = Question::where('chapter_id', $chapterId);
        
        // Filter by difficulty level if section has it specified
        if ($sectionId) {
            $section = DB::table('subjects')->where('id', $sectionId)->first();
            if ($section && !empty($section->difficulty)) {
                $query->where('difficulty', $section->difficulty);
                \Log::info('Filtering by difficulty: ' . $section->difficulty);
            }
        }
        
        // Exclude questions that are already displayed on the page
        if (!empty($excludeQuestionIds) && is_array($excludeQuestionIds)) {
            $query->whereNotIn('id', $excludeQuestionIds);
        }
        
        $question = $query->inRandomOrder()->first();
        
        if ($question) {
            // Ensure options is an array
            $questionData = $question->toArray();
            if (isset($questionData['options']) && is_string($questionData['options'])) {
                $questionData['options'] = json_decode($questionData['options'], true);
            }
            
            // Parse nested options structure like {"1":{"en":"text"}}
            if (isset($questionData['options']) && is_array($questionData['options'])) {
                $optionsArray = [];
                foreach ($questionData['options'] as $key => $value) {
                    if (is_array($value) && isset($value['en'])) {
                        $optionsArray[] = $value['en'];
                    } elseif (is_string($value)) {
                        $optionsArray[] = $value;
                    }
                }
                $questionData['options'] = $optionsArray;
            }
            
            // Parse question_text if it's JSON
            if (isset($questionData['question_text']) && is_string($questionData['question_text'])) {
                $decoded = json_decode($questionData['question_text'], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                    $questionData['question_text'] = $decoded['en'];
                } else if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    // Sometimes the decoded value is just an array without 'en' key
                    $questionData['question_text'] = reset($decoded); // Get first value
                }
                // If JSON decode failed or result is empty, keep original string
                if (empty($questionData['question_text'])) {
                    $questionData['question_text'] = $question->question_text;
                }
            }
            
            \Log::info('After parsing - Question text length: ' . strlen($questionData['question_text']));
            \Log::info('After parsing - Question text preview: ' . substr($questionData['question_text'], 0, 200));
            
            // Fix image URLs in question_text - make them absolute (but skip if it has data: URIs which are already complete)
            if (isset($questionData['question_text'])) {
                // Only process if there are NO data: URIs (to avoid regex timeout on huge base64 strings)
                if (strpos($questionData['question_text'], 'data:image') === false) {
                    $questionData['question_text'] = preg_replace_callback(
                        '/<img([^>]+)src=["\']([^"\']+)["\']/',
                        function($matches) {
                            $src = $matches[2];
                            // If already absolute (starts with http, https, //, /), leave it
                            if (preg_match('/^(http|https|\/\/)/', $src) || $src[0] === '/') {
                                return '<img' . $matches[1] . 'src="' . $src . '"';
                            }
                            // Make it absolute from root
                            return '<img' . $matches[1] . 'src="/' . $src . '"';
                        },
                        $questionData['question_text']
                    );
                } else {
                    \Log::info('Skipping image URL fix - contains data: URI (base64)');
                }
            }
            
            // Parse solution if it's JSON
            if (isset($questionData['solution']) && is_string($questionData['solution'])) {
                $decoded = json_decode($questionData['solution'], true);
                if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                    $questionData['solution'] = $decoded['en'];
                }
            }
            
            // Fix image URLs in solution too (but skip if it has data: URIs)
            if (isset($questionData['solution'])) {
                // Only process if there are NO data: URIs
                if (strpos($questionData['solution'], 'data:image') === false) {
                    $questionData['solution'] = preg_replace_callback(
                        '/<img([^>]+)src=["\']([^"\']+)["\']/',
                        function($matches) {
                            $src = $matches[2];
                            // If already absolute (starts with http, https, //, /), leave it
                            if (preg_match('/^(http|https|\/\/)/', $src) || $src[0] === '/') {
                                return '<img' . $matches[1] . 'src="' . $src . '"';
                            }
                            // Make it absolute from root
                            return '<img' . $matches[1] . 'src="/' . $src . '"';
                        },
                        $questionData['solution']
                    );
                }
            }
            
            \Log::info('Question found: ' . $question->id);
            \Log::info('Raw question_text from DB (first 200 chars): ' . substr($question->question_text, 0, 200));
            \Log::info('Raw question_text length from DB: ' . strlen($question->question_text));
            \Log::info('Question text being sent (first 500 chars): ' . substr($questionData['question_text'], 0, 500));
            \Log::info('Question text being sent length: ' . strlen($questionData['question_text']));
            \Log::info('Contains img tag: ' . (strpos($questionData['question_text'], '<img') !== false ? 'YES' : 'NO'));
            
            return response()->json([
                'success' => true,
                'question' => $questionData
            ], 200, [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            \Log::warning('No questions found for chapter_id: ' . $chapterId);
            return response()->json([
                'success' => false,
                'message' => 'No other questions found for this chapter'
            ], 404);
        }
        
    } catch (\Exception $e) {
        \Log::error('Error in refreshQuestion: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'message' => 'Error refreshing question: ' . $e->getMessage()
        ], 500);
    }
}

public function reportQuestion(Request $request){
    try {
        $request->validate([
            'question_id' => 'required|integer',
            'message' => 'required|string|max:1000'
        ]);
        
        $report = new \App\Models\QuestionReport();
        $report->user_id = auth()->user()->id;
        $report->question_id = $request->question_id;
        $report->message = $request->message;
        $report->save();
        
        \Log::info('Question reported by user ' . auth()->user()->id . ' - Question ID: ' . $request->question_id);
        
        return response()->json([
            'success' => true,
            'message' => 'Question reported successfully'
        ]);
        
    } catch (\Exception $e) {
        \Log::error('Error in reportQuestion: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error reporting question: ' . $e->getMessage()
        ], 500);
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




public function testMissed(Request $request)
{
    $test_list = [];
    $batches = Batch::all();

    foreach ($batches as $batch) {
        $batchUsers = StudentTeacherBatch::where("bid", $batch->id)
            ->pluck('uid')
            ->toArray();

        foreach ($batchUsers as $bu) {
            $examUser = ExamUser::where("sync_id", $bu)->first();

            if (!$examUser) {
                continue;
            }

            $examBatch = ExamBatch::where("sync_id", $batch->id)->first();
            if (!$examBatch) {
                continue;
            }

            $examTests = ExamBatchTest::where("batch_id", $examBatch->id)
                ->orderBy("id", "desc")
                ->get();

            foreach ($examTests as $et) {
                $test = ExamTest::find($et->test_id);
                if (!$test) {
                    continue;
                }

                $mytest = ExamMyTest::where("user_id", $examUser->id)
                    ->where("test_id", $et->test_id)
                    ->first();

                $et->test = $test;
                $et->mytest = $mytest;

                if ($mytest) {
                    // convert will_start to Carbon
                    $willStart = $mytest->will_start instanceof Carbon
                        ? $mytest->will_start
                        : Carbon::parse(date("Y-m-d",$mytest->will_start));

                    $today = Carbon::today();

                    if (
                        $mytest->status !== 'submitted' &&
                        $today->equalTo($willStart->addDay()) &&
                        $mytest->notification_count < 2 &&
                        in_array($mytest->user_id, [ 2445])
                    ) {
                        $test_list[] = $et;
                    }
                }
            }
        }
    }

    dd($test_list);
}

public function testPages(Request $request,$id)
{
    
    try{
     $test_list=[];
     $mock_list=[];
        $batch = Batch::find($id);
        
    if(!$batch){
        return redirect()->back()->withFlashDanger("Batch not found.");
    }
    
    $examUser = ExamUser::where("sync_id",Auth::user()->id)->first();

    if($examUser){
        
        $examBatchUsers = ExamBatchUser::where("user_id",$examUser->id)->get()->pluck("batch_id")->toArray();
        $examBatch = ExamBatch::where("sync_id",$batch->id)->first();
         
        if($examBatch){
           $examTests = ExamBatchTest::whereIn("batch_id",[$examBatch->id])->orderBy("id","desc")->get();
         
           foreach ($examTests as $et){
               
               $test = ExamTest::find($et->test_id);
               
               // Get the entry from my_tests table for this user and test_id
               // Priority: If any entry has status='submitted', use that; otherwise use the latest entry
               $mytest = ExamMyTest::where("user_id",$examUser->id)
                   ->where("test_id",$et->test_id)
                   ->where("status","submitted")
                   ->first();
               
               // If no submitted entry found, get the latest entry
               if(!$mytest){
                   $mytest = ExamMyTest::where("user_id",$examUser->id)
                       ->where("test_id",$et->test_id)
                       ->orderBy("id","desc")
                       ->first();
               }
               
               $et->mytest = $mytest;
               $et->test = $test;
              
               $test_list[] = $et;
           }
        }
       
    }
    
    // Fetch mock tests for this batch
    $allMockTests = DB::table('batch_mock_tests as bmt')
        ->join('mock_list as ml', 'bmt.mock_list_id', '=', 'ml.id')
        ->where('bmt.batch_id', $batch->id)
        ->whereNotNull('bmt.mock_list_id')
        ->select('ml.*', 'bmt.id as batch_mock_test_id', 'bmt.batch_id', 'bmt.scheduled_at', 'bmt.end_date', 'bmt.is_active', 'bmt.mock_series_id')
        ->orderBy('bmt.sort_order')
        ->get();
    
    // Calculate test status for each mock
    foreach ($allMockTests as $mockTest) {
        $now = Carbon::now();
        $mockTest->test_status = 'not_available'; // default
        $mockTest->status_message = '';
        
        // Check if user has attempted/submitted this mock
        $myExam = \App\Models\MyExam::where('batch_mock_test_id', $mockTest->batch_mock_test_id)
            ->where('user_id', Auth::user()->id)
            ->where('exam_id', $mockTest->id)
            ->first();
        
        $mockTest->myExam = $myExam;
        // Check for both 'submitted' and 'completed' status for backward compatibility
        $mockTest->is_submitted = $myExam && in_array($myExam->status, ['submitted', 'completed']);
        
        // If already submitted, mark as completed regardless of schedule
        if ($mockTest->is_submitted) {
            $mockTest->test_status = 'completed';
            $mockTest->status_message = 'Completed';
            $mock_list[] = $mockTest;
            continue;
        }
        
        // Determine test status based on schedule and end_date
        if ($mockTest->is_active == 1) {
            // Manually activated - check if available until end_date
            if (!empty($mockTest->end_date)) {
                $endDate = Carbon::parse($mockTest->end_date, config('app.timezone'))->endOfDay();
                if ($now->lte($endDate)) {
                    // Still within available window (available until midnight of end date)
                    $mockTest->test_status = $mockTest->is_submitted ? 'completed' : 'available';
                    $mockTest->status_message = 'Available until ' . $endDate->format('d M Y') . ' 11:59 PM';
                } else {
                    // Deadline passed (after midnight)
                    $mockTest->test_status = $mockTest->is_submitted ? 'completed' : 'missed';
                    $mockTest->status_message = 'Was available until ' . $endDate->format('d M Y') . ' 11:59 PM';
                }
            } else {
                // No end_date set yet
                $mockTest->test_status = $mockTest->is_submitted ? 'completed' : 'available';
                $mockTest->status_message = 'Available now';
            }
        } elseif (!empty($mockTest->scheduled_at)) {
            $scheduledDate = Carbon::parse($mockTest->scheduled_at, config('app.timezone'))->startOfDay();
            // Mock tests available only for the scheduled date (until midnight)
            $endDate = $scheduledDate->copy()->endOfDay();
            
            if ($now->lt($scheduledDate)) {
                // Future test (before scheduled date)
                $mockTest->test_status = 'upcoming';
                $mockTest->status_message = 'Available on ' . $scheduledDate->format('d M Y');
            } elseif ($now->lte($endDate)) {
                // Within available window (from start of scheduled day to end of day at midnight)
                $mockTest->test_status = $mockTest->is_submitted ? 'completed' : 'available';
                $mockTest->status_message = 'Available until ' . $endDate->format('d M Y') . ' 11:59 PM';
            } else {
                // Missed - scheduled day has passed (after midnight)
                $mockTest->test_status = $mockTest->is_submitted ? 'completed' : 'missed';
                $mockTest->status_message = 'Was available on ' . $scheduledDate->format('d M Y');
            }
        }
        
        $mock_list[] = $mockTest;
    }
    
    // dd($examBatch);
     
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
    
 

    $course = Course::find($batch->cid);



    return view('backend.myclass.test-list',compact('test_list','mock_list','course','batch'));
}
catch(\Exception $e){
    
    return redirect()->back()->withFlashDanger("Something went wrong. Please try again later.");
}

}



public function waitingExam($batch_id,$user_id,$test_id,$mytest){
    
    
   
      $examBatch = ExamBatch::where("sync_id",$batch_id)->first();
      
       $test = ExamBatchTest::find($mytest);
     
    
     
    return view('backend.myclass.examwaiting',compact('test','batch_id','user_id','test_id'));
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
//  $x=$el->eClassJson("getPastMeetings",$in);
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
    // $list=Recording::with('lesson')->where("parent",$batch->parent_api_class_id)->orderBy("id","desc")->get();
    
    
    $commit = StudentCommitment::where("batch_id",$id)->where("student_id",Auth::user()->id)->first();
    
    if($commit){
       $batch->total_class = $commit->total_class;
       $batch->total_test = $commit->total_test; 
       
        
    }
    
   $listQuery = Recording::where("parent", $batch->parent_api_class_id)
    ->orderBy("id", "desc");

// Apply joining date filter if available
if ($commit && !empty($commit->joining_date)) {
    $listQuery->whereDate('created_at', '>=', $commit->joining_date);
}

if ($commit && !empty($commit->completion_date)) {
    $listQuery->whereDate('recording_date', '<=', $commit->completion_date);
}
$list = $listQuery->get();


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

// Try to save student join record (wrapped in try-catch to handle missing columns)
try {
    $sj=new StudentJoin;
    $sj->user_id=auth()->user()->id;
    $sj->batch_id=$id;
    $sj->status='joined';
    
    if(!$sj->save()){
      \Log::error('Failed to save StudentJoin in web route', ['user_id' => auth()->user()->id, 'batch_id' => $id]);
    } else {
      \Log::info('StudentJoin saved via web', ['student_join_id' => $sj->id, 'user_id' => auth()->user()->id, 'batch_id' => $id]);
    }
} catch (\Exception $e) {
    \Log::warning('StudentJoin tracking skipped - table columns may be missing', [
        'user_id' => auth()->user()->id, 
        'batch_id' => $id,
        'error' => $e->getMessage()
    ]);
}

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
        if($request->type=='single'){
        $demo = DemoRequest::find($demo_id);
        }else{
              $demo = DemoBatch::find($demo_id);
        }
          $meetid=$demo_id."-".time();
          $e=new Elearn;
          
          
          if($demo->api_class_id){
              
$status=$e->eClass("isMeetingRunning",array("meetingID"=>$demo->api_class_id));

if($status['running']=='false'){
   $demo->api_class_id = null;
   $demo->update();
    //   dd($demo);
}
  if($request->type=='single'){
        $demo = DemoRequest::find($demo_id);
        }else{
              $demo = DemoBatch::find($demo_id);
        }

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
 if($request->type=='single'){
    $b = DemoRequest::find($demo_id);
        }else{
              $b = DemoBatch::find($demo_id);
        }
            $b->api_class_id=$mid;
            $b->demo_status='started';
            $b->update();

            // $dr = DemoHistory::where("demo_id",$demo_id)->orderBy("id","desc")->first();
            // // $dr->demo_id = $demo_id;
            // $dr->internal_id = $internal;
            // // $dr->teacher_id = Auth::user()->id;
            // $dr->save();



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
    
    
    
    
    $batchUsers = StudentTeacherBatch::where("bid",$bid)->get();
    
    
    foreach ($batchUsers as $bu){
        
        $bUser = User::find($bu->uid);
        
         $commit = StudentCommitment::where("batch_id",$bid)->where("student_id",$bu->uid)->first();
    
    if($commit->completion_date==null || date("Y-m-d")<=$commit->completion_date){
        if($bUser){
            
              $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'class_started_v11',
            'destination' => '+91'.$bUser->phone,
            'userName' => $bUser->name,
            'source' => 'class_started_v11',
            'templateParams' => [strtoupper(explode(" ",$bUser->name)[0])],
            'tags' => ['demo', 'new-demo'],
            'attributes' => ['user_id' => $bUser->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
        }
        
    }
    }
    
    
    
    
    
    
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


    public function waitingArea(Request $request, $id){

        return view('backend.wait',compact('id'));
    }
    
    public function meetingLink(Request $request){

        $batch = Batch::find($request->bid);
        if(!$batch){
            return abort(404);
        }

        $user = User::find(auth()->user()->id);
        if(!$user){
            return abort(404);
        }
        
        // $stb = StudentTeacherBatch::where('bid',$batch->id)->where('uid',$user->id)->first();
        // dd($stb,$batch,$user);
        // if(!$stb){
        //     return abort(404);
        // }

        $meetid=Recording::where("parent",$batch->parent_api_class_id)->where("created_at",">=",date("Y-m-d 00:00:00"))->orderBy("id","desc")->first();
        
       
       if($meetid)
       {
        $url = route('myclass.slaunch',['id'=>$batch->id,'meetid'=>$meetid->api_class_id]);
        return response()->json(['success' => true, 'url' => $url]);
       }
        
       return response()->json(['success' => false, 'url' => "No meeting found for today."]);

        // $meetid = $batch->parent_api_class_id;

        // $lin=array(
        //     "meetingID"=>$meetid,
        //     "password"=>"ap",
        //     "fullName"=>$user->first_name." ".$user->last_name,
        //     "redirect"=>'true',
        // );

        // $e=new Elearn;
        // $launch=$e->getLaunch($lin);

        // if($launch["status"]){
        //     return redirect($launch["url"]);
        // }else{
        //     return redirect()->back()->withFlashDanger("Something went wrong.");
        // }
        
    }

    // Mock Results Methods
    public function mockResults($batch_id){
        $batch = Batch::findOrFail($batch_id);
        
        // Check if user has access to this batch
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->hasRole('teacher')) {
            return abort(403);
        }
        
        // If teacher, verify they teach this batch
        if ($user->hasRole('teacher')) {
            $teacherBatch = TeacherBatch::where('bid', $batch_id)
                ->where('tid', $user->id)
                ->first();
            
            if (!$teacherBatch) {
                return abort(403, 'You do not have access to this batch.');
            }
        }
        
        // Get course separately
        $course = Course::find($batch->cid);
        
        return view('backend.myclass.mock-results', compact('batch', 'course'));
    }
    
    public function getStudentsList($batch_id){
        try {
            $batch = Batch::findOrFail($batch_id);
            
            // Check access
            $user = auth()->user();
            if (!$user->isAdmin() && !$user->hasRole('teacher')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            // Get students from student_teacher_batches table
            $studentBatches = StudentTeacherBatch::where('bid', $batch_id)->get();
            
            $students = [];
            foreach ($studentBatches as $stb) {
                $student = User::find($stb->uid);
                if ($student) {
                    $students[] = [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . $student->last_name,
                        'email' => $student->email
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'students' => $students
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getStudentsList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getMockTestsList($batch_id){
        try {
            $batch = Batch::findOrFail($batch_id);
            
            // Check access
            $user = auth()->user();
            if (!$user->isAdmin() && !$user->hasRole('teacher')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            // Get mock tests using BatchMockTest model with relationships
            $mockTests = \App\Models\BatchMockTest::where('batch_id', $batch_id)
                ->with(['mockList:id,name,description,total_questions,duration', 'mockSeries:id,name'])
                ->get()
                ->map(function($item) {
                    $mockList = $item->mockList;
                    $mockSeries = $item->mockSeries;
                    
                    if (!$mockList) {
                        return null;
                    }
                    
                    return [
                        'id' => $mockList->id,
                        'mock_list_id' => $mockList->id,
                        'name' => $mockList->name,
                        'series_name' => $mockSeries ? $mockSeries->name : 'N/A',
                        'description' => $mockList->description,
                        'total_questions' => $mockList->total_questions,
                        'duration' => $mockList->duration,
                        'is_active' => $item->is_active
                    ];
                })
                ->filter() // Remove null entries
                ->values();
            
            return response()->json([
                'success' => true,
                'mockTests' => $mockTests
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getMockTestsList: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching mock tests: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getStudentMockResult($student_id, $mock_id){
        try {
            // Check access
            $user = auth()->user();
            if (!$user->isAdmin() && !$user->hasRole('teacher')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $student = User::findOrFail($student_id);
            
            // Find the exam record for this student and mock test
            $exam = \App\Models\MyExam::where('user_id', $student_id)
                ->where('exam_id', $mock_id)
                ->where('status', 'completed')
                ->latest()
                ->first();
            
            if (!$exam) {
                return response()->json([
                    'success' => false,
                    'message' => 'This student has not attempted this mock test yet.'
                ], 404);
            }
            
            // Get mock test details
            $mockTest = \App\Models\MockList::find($mock_id);
            
            if (!$mockTest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mock test not found'
                ], 404);
            }
            
            // Calculate result using the same logic as answer key
            $questions = json_decode($exam->questions, true);
            $userAnswers = json_decode($exam->answers, true) ?? [];
            
            $totalQuestions = 0;
            $correctAnswers = 0;
            $wrongAnswers = 0;
            $unattempted = 0;
            
            // Calculate subject-wise performance
            $subjectWiseData = [];
            
            foreach ($questions as $sectionId => $questionIds) {
                foreach ($questionIds as $qid) {
                    $totalQuestions++;
                    $question = \App\Models\Question::find($qid);
                    
                    if ($question) {
                        $subjectId = $question->subject_id;
                        
                        if (!isset($subjectWiseData[$subjectId])) {
                            $subject = \App\Models\Subject::find($subjectId);
                            $subjectWiseData[$subjectId] = [
                                'subject_name' => $subject ? $subject->name : 'Unknown',
                                'correct' => 0,
                                'incorrect' => 0,
                                'skipped' => 0,
                                'marks' => 0
                            ];
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
                                $subjectWiseData[$subjectId]['correct']++;
                                $subjectWiseData[$subjectId]['marks'] += $question->marks ?? 1;
                            } else {
                                $wrongAnswers++;
                                $subjectWiseData[$subjectId]['incorrect']++;
                            }
                        } else {
                            $unattempted++;
                            $subjectWiseData[$subjectId]['skipped']++;
                        }
                    }
                }
            }
            
            $totalMarks = $mockTest->total_marks ?? $totalQuestions;
            $obtainedMarks = array_sum(array_column($subjectWiseData, 'marks'));
            $percentage = ($totalMarks > 0) ? ($obtainedMarks / $totalMarks) * 100 : 0;
            
            return response()->json([
                'success' => true,
                'result' => [
                    'exam_id' => $exam->id,
                    'student_name' => $student->first_name . ' ' . $student->last_name,
                    'mock_name' => $mockTest->name,
                    'total_marks' => $totalMarks,
                    'obtained_marks' => $obtainedMarks,
                    'percentage' => number_format($percentage, 2),
                    'attempted_at' => \Carbon\Carbon::parse($exam->exam_date_time)->format('d M Y, h:i A'),
                    'time_taken' => $exam->time_spent ? gmdate('H:i:s', $exam->time_spent) : 'N/A',
                    'subject_wise' => array_values($subjectWiseData)
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error in getStudentMockResult: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching result: ' . $e->getMessage()
            ], 500);
        }
    }
 
}