<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Jenssegers\Agent\Agent;


use App\Models\Category;
use App\Models\Course;
use App\Models\Board;
use App\Models\TestSeries;
use App\Models\TestSeriesPurchase;
use App\Models\Lesson;
use App\Models\TestList;
use App\Models\MyExam;
use App\Models\Question;
use App\Models\Subject;
use App\Models\SubjectChapter;
use App\Models\QuestionReport;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

use App\Mail\Frontend\Demo\StudentTestByEmail;
use App\Models\AiSensy;
use Session;
use DateTime;
/**
 * 
 * Class HomeController.
 */
class TestSeriesController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

    private $path;
    
    
    
    
     public function questionReport(){

        $reports = QuestionReport::with('user')->orderBy("id","desc")->paginate(25);
        
           return view('admin.reports', compact('reports'));

        
    }
    
    
    public function purchaseList(){

        $purchaseList = TestSeriesPurchase::with('user','course')->where('payment_status','paid')->orderBy("id","desc")->paginate(25);
        
           return view('admin.purchase', compact('purchaseList'));

        
    }
    
    
    
    
    
    
    
    
     //shruti
    
    public function studyMaterial($testSeriesId)
    {
        // Get the test series
        $testSeries = TestSeries::findOrFail($testSeriesId);

        // Get course_id from test series
        $courseId = $testSeries->course_id;

        // Fetch all lessons for that course
        $lessons = Lesson::where('course_id', $courseId)
                         ->orderBy('id')
                         ->get();

        return view('backend.testseries.study-material', compact('lessons', 'testSeries'));
    }


    ///shruti
    public function videos($testSeriesId)
{
    $testSeries = TestSeries::findOrFail($testSeriesId);

    $courseId = $testSeries->course_id;

    $lessons = Lesson::where('course_id', $courseId)
                     ->orderBy('id')
                     ->get();

    return view('backend.testseries.lesson-videos-list', compact('lessons', 'testSeries'));
}

    // Show media for a lesson for  test series
  public function lessonMaterials($lessonId)
{
    $lesson = Lesson::findOrFail($lessonId);
    // dd($lessonId);
    $lessonId = (int)$lessonId;
    // Fetch all files attached to this lesson
    $media = Media::where('model_id', $lessonId)
        ->where('model_type', 'App\Models\Lesson')
        ->where(function ($q) {
            $q->where('type', 'lesson_pdf')
              ->orWhere('type', 'application/pdf');
        })
        ->orderBy('id', 'desc')
        ->get(); // <-- very important to actually execute the query

    // dd($media); // debug to check if it returns records

    return view('backend.testseries.lesson-materials', compact('media', 'lesson'));
}
//return blade to show videos for a lesson for  test series
public function lessonVideos($lessonId)
{
    $lesson = Lesson::findOrFail($lessonId);

    $videos = Media::where('model_id', (int)$lessonId)
        ->where('model_type', 'App\Models\Lesson')
        ->where('type', 'youtube')
        ->latest('id')
        ->get();

    return view('backend.testseries.lesson-videos', compact('videos', 'lesson'));
}
    
    
    
    
    
    
    
   public function reportQuestion(Request $request)
{
    // Validate if needed
    $validated = $request->validate([
        'exam_id'     => 'required|integer',
        'question_id' => 'required|integer',
        'question_no' => 'required|integer',
        'message'     => 'required|string|max:1000',
    ]);

    // Save data
    $report = new QuestionReport();
    $report->exam_id     = $validated['exam_id'];
    $report->question_id = $validated['question_id'];
    $report->question_no = $validated['question_no'];
    $report->user_id = Auth::user()->id;
    $report->message     = $validated['message'];
    $report->save();

    return response()->json([
        'status'  => true,
        'message' => 'Report submitted successfully',
        
    ]);
}

    
    public function chapterSave($id, Request $request){
        
        $chapters = $request->chapters;
        
        SubjectChapter::where("subject_id",$id)->delete();
         $subject = Subject::find($id);
          $testSeries = TestList::find($subject->test_id);
          
         if($chapters){
        foreach($chapters as $ch){
            
            $sbc = new SubjectChapter();
            $sbc->subject_id=$id;
            $sbc->lesson_id = $ch;
            $sbc->test_id = $testSeries->id;
            $sbc->save();
        }}
        
        return redirect()->back()->with("success","Chapters updated.");
        
    }
    
    public function subjects($id){
        $subjects = Subject::where("test_id",$id)->get();
        $course = TestList::find($id);
        
       // $chapters = Lesson::where("course_id",$id)->get();
       
      return view('admin.test-list.subjects', compact('subjects','course'));
        
        
    }
    
    public function chapters($id){
        
        $assigned = SubjectChapter::where("subject_id",$id)->get()->pluck("lesson_id")->toArray();
        $subject = Subject::with('test')->find($id);
        
       $test = TestList::find($subject->test_id);
       $testSeries = TestSeries::find($test->test_series_id);
    //   dd($testSeries);
        $chapters = Lesson::where("course_id",$testSeries->course_id)->get();
         return view('admin.test-list.chapters', compact('subject','chapters','assigned','testSeries','test'));
         
    }
    
    
    
     public function storeSubject(Request $request,$id)
    {
        $request->validate([
            'course_id' => 'required|exists:test_list,id',
            'name'      => 'required|string|max:255',
            'status'    => 'required|in:active,inactive',
        ]);

        Subject::create([
            'test_id' => $request->course_id,
            'name'      => $request->name,
            'status'    => $request->status,
            'difficulty'    => $request->difficulty,
        ]);

        return redirect()->back()->with('success', 'Subject added successfully.');
    }

    
    public function updateSubject(Request $request, $id)
    {
        try {
            $request->validate([
                'name'       => 'required|string|max:255',
                'status'     => 'required|in:active,inactive',
                'difficulty' => 'nullable|in:easy,medium,hard',
            ]);

            $subject = Subject::findOrFail($id);
            $subject->update([
                'name'       => $request->name,
                'status'     => $request->status,
                'difficulty' => $request->difficulty,
            ]);

            return redirect()->back()->with('success', 'Subject updated successfully.');
        } catch (\Throwable $e) {
            Log::error('TestSeriesController@updateSubject failed', [
                'subject_id' => $id,
                'user_id' => Auth::user() ? Auth::user()->id : null,
                'payload' => $request->all(),
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Remove the specified subject.
     */
    public function destroySubject($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->delete();

        return redirect()->back()->with('success', 'Subject deleted successfully.');
    }
    
    
    
  
    
    public function myAttempt($id,$tsid){
        
        $test_series_purchase_id = $tsid;
        $myExam = MyExam::where("test_series_purchase_id",$test_series_purchase_id)->where('status','started')->where("user_id",Auth::user()->id)->where("exam_id",$id)->first();
        if(!$myExam){
        $questions = [];
        $test = TestList::find($id);
        
        // dd($test);
        $chapters = json_decode($test->sections,true);
        $chapter_questions = json_decode($test->section_questions,true);
          $questions = [];



         foreach ($chapter_questions as $sectionId => $chapterData) {
    
          if (!isset($questions[$sectionId])) {
              $questions[$sectionId] = [];
            }
          foreach($chapterData as $chapterId=>$questionCount){
        
        
            if ($questionCount > 0) {
            $section = Subject::find($sectionId);
             $chapterQuestions = Question::where("chapter_id", $chapterId)
            ->where("verification_status", 'approved')
            ->where('is_prev_year',$test->is_prev_year)
            ->where('difficulty',$section->difficulty)
            ->inRandomOrder()
            ->take($questionCount)
            ->get()
            ->pluck("id")
            ->toArray();
        
        $questions[$sectionId] = array_merge($questions[$sectionId], $chapterQuestions);
    }
    
    
    }
    
}
// dd($questions);



// Shuffle the final questions array to mix questions from different chapters
// shuffle($questions);

 
$exam = new MyExam();
$exam->test_series_purchase_id = $test_series_purchase_id;
$exam->user_id = Auth::user()->id;
$exam->exam_id = $id;
$exam->duration = $test->duration;
$exam->questions = json_encode($questions);
$exam->status='started';
$exam->exam_date_time =date("Y-m-d h:i:s");
$exam->last_ping = time();
$exam->current_question = 1;
$exam->save();

return redirect("/user/attempt/".$exam->id);
}else{
    
return redirect("/user/attempt/".$myExam->id);
}

    //   dd($questions); 
        // test_series_purchase_id
        
    }
    
    
    
    public function startExam($id){
        
        $exam = MyExam::where("id",$id)->where("user_id",Auth::user()->id)->first();
        if(!$exam){
            return abort(404);
        }
        
        $secondsUntilStart =2;
        
        return view('backend.testseries.waiting',compact('exam','secondsUntilStart'));
    }
    
     public function thankYou(){
        
       
        
        return view('backend.testseries.exam-thank');
    }
    
    
    
   public function submitExam(Request $request){
        
     $exam = MyExam::find($request->student_id);
     $exam->answers = $request->answers;
     $exam->time_spent = $request->time_spent;
     $exam->status ='completed';
     $exam->update();
     
     return response()->json(['success'=>true]);
     
    }
    
    /**
     * Handle ping-pong for live exam tracking
     * Updates last_ping and current_question for real-time monitoring
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pingPong(Request $request)
    {
        $examId = $request->eid;
        $currentQuestion = $request->qn;
        
        $exam = MyExam::find($examId);
        
        if ($exam && $exam->user_id == Auth::user()->id) {
            $exam->last_ping = time();
            $exam->current_question = $currentQuestion;
            $exam->update();
            
            return response()->json([
                'success' => true,
                'ping' => Auth::user()->id,
                'eid' => $examId,
                'timestamp' => time()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Exam not found or unauthorized'
        ], 404);
    } 
    
      public function takeExam($id){
        
        $exam = MyExam::where("id",$id)->where("user_id",Auth::user()->id)->first();
        if(!$exam){
            return abort(404);
        }
          $qlist=[];
        $qids = json_decode($exam->questions,true);
        // dd($qids);
           $user=User::find(Auth::user()->id);
        foreach($qids as $sectionId=>$qdata){
                   $questions = Question::whereIn("id",$qdata)->get();
          foreach ($questions as $q) {
    $question_text = json_decode($q->question_text, true);
    $options = json_decode($q->options, true);

    $options_en = [];
    $options_hi = [];

foreach ($options as $key => $opt) {
    if (!isset($opt['en'])) {
        // dd("Missing keys in option:", $options);
    }  
    // dd($q);

    $options_en[] = $opt['en'];
}


    $qlist[$sectionId][] = [
        'id'          => $q->id,
        'question_en' => $question_text['en'],
        'options_en'  => $options_en,
    ];
}
        }
        
        
 
     
        
//   dd($qlist);    
        
       
$subjects=[];
$subjectList=[];
foreach($qlist as $k=>$ql){
    $subjectList[] = $k;
    $course = Subject::find($k);
    $subjects[$course->id] = ["name"=>$course->name,"questions"=>$ql]; 
}
// dd($subjects); 
// $batchExam = BatchExam::find($exam->batch_exam_id);


$start = Carbon::parse($exam->exam_date_time);
$end   = $start->copy()->addMinutes($exam->duration);



// $diffInMinutes = $start->diffInMinutes($end);

     $examData = [
         'exam_id'=>$id,
    'student' => [
        'name' => strtoupper($user->name),
        'id' => $user->auto_stu_id,
        'my_exam_id'=>$exam->id,
        'admit_card_photo' => $user->image,
        'database_photo' => $exam->photo
    ],
    'subjects' => $subjects,
    'exam_settings' => [
        'total_time' => $start->diffInSeconds($end), // 60 minutes in seconds
        'max_tab_switches' => 30,
        'anti_cheating_enabled' => true
    ]
];

// dd(array_keys($examData['subjects']),$examData['subjects']);
         $agent = new Agent();
    $isMobile = $agent->isMobile();
   if($isMobile){
        return view('backend.testseries.take-mobile',compact('exam','examData','subjectList'));
   }else{
       return view('backend.testseries.take-exam',compact('exam','examData','subjectList'));
   }
    }
    
    
      public function examResult($id){
        
       $exam = MyExam::with('exam','user')->findOrFail($id);

    // Decode stored questions & answers
    $qidsx = json_decode($exam->questions, true);
    
    $qids=[];
    
    foreach($qidsx as $sectionId=>$qData){
        $qids = array_merge($qids,$qData);
    }

    // Some exams can accidentally contain duplicate question IDs across sections.
    // That inflates totalQuestions (e.g., 44 showing as 45). Deduplicate safely.
    $qids = array_values(array_unique($qids));

    $questions = Question::whereIn("id", $qids)->get()->keyBy('id');
    $answers = json_decode($exam->answers, true);

    // Letter → index mapping
    $optionMap = [
        'A' => 0,
        'B' => 1,
        'C' => 2,
        'D' => 3,
        'E' => 3,
    ];

    // --- Normalization helpers (keeps counts consistent with result highlighting) ---
    $coerceScalar = function ($value) {
        if ($value === null) return null;
        if (is_array($value)) $value = $value[0] ?? null;
        if ($value === null) return null;
        if (is_string($value) && str_contains($value, ',')) $value = trim(explode(',', $value)[0]);
        return $value;
    };

    $letterToIndex = function ($value) {
        if (!is_string($value)) return null;
        $v = strtoupper(trim($value));
        $letters = ['A' => 0, 'B' => 1, 'C' => 2, 'D' => 3];
        return $letters[$v] ?? null;
    };

    $detectOptionKeyMode = function (array $opts) {
        $keys = array_keys($opts);
        $hasLetters = false;
        $nums = [];

        foreach ($keys as $k) {
            if (is_string($k)) {
                $t = trim($k);
                if (preg_match('/^[A-Da-d]$/', $t)) { $hasLetters = true; continue; }
                if (preg_match('/^\d+$/', $t)) { $nums[] = (int) $t; continue; }
            }
            if (is_int($k) || is_float($k)) $nums[] = (int) $k;
        }

        if ($hasLetters) return 'letter';
        if (empty($nums)) return 'unknown';

        $min = min($nums);
        $max = max($nums);
        if ($min >= 1 && $max <= 4) return 'one';   // 1..4
        if ($min >= 0 && $max <= 3) return 'zero';  // 0..3
        return ($min >= 1) ? 'one' : 'zero';
    };

    $normalizeUserAnswerIndex = function ($value) use ($coerceScalar, $letterToIndex, $optionMap) {
        $value = $coerceScalar($value);
        if ($value === null || $value === '') return null;

        // Usually stored by UI as 0..3, but handle legacy 1..4 too.
        if (is_numeric($value)) {
            $n = (int) $value;
            if ($n >= 0 && $n <= 3) return $n;
            if ($n >= 1 && $n <= 4) return $n - 1;
        }

        $li = $letterToIndex($value);
        if ($li !== null) return $li;

        if (is_string($value) && array_key_exists($value, $optionMap) && is_numeric($optionMap[$value])) {
            $m = (int) $optionMap[$value];
            if ($m >= 0 && $m <= 3) return $m;
            if ($m >= 1 && $m <= 4) return $m - 1;
        }

        return null;
    };

    $normalizeCorrectAnswerIndex = function ($value, string $mode) use ($coerceScalar, $letterToIndex, $optionMap) {
        $value = $coerceScalar($value);
        if ($value === null || $value === '') return null;

        $li = $letterToIndex($value);
        if ($li !== null) return $li;

        if (is_numeric($value)) {
            $n = (int) $value;
            if ($mode === 'one' && $n >= 1 && $n <= 4) return $n - 1;
            if ($mode === 'zero' && $n >= 0 && $n <= 3) return $n;
            if ($n >= 1 && $n <= 4) return $n - 1;
            if ($n >= 0 && $n <= 3) return $n;
        }

        if (is_string($value) && array_key_exists($value, $optionMap) && is_numeric($optionMap[$value])) {
            $m = (int) $optionMap[$value];
            if ($mode === 'one' && $m >= 1 && $m <= 4) return $m - 1;
            if ($mode === 'zero' && $m >= 0 && $m <= 3) return $m;
            if ($m >= 1 && $m <= 4) return $m - 1;
            if ($m >= 0 && $m <= 3) return $m;
        }

        return null;
    };

    $correct = 0;
    $wrong = 0;
    $notAttempted = 0;
    $score = 0;
    $maxScore = 0;

    foreach ($qids as $qid) {
        $question = $questions[$qid] ?? null;
        $studentAnswer = $answers[$qid] ?? null;

        if (!$question) continue;

        // Max score is sum of all question marks
        $maxScore += $question->marks;

        // Detect per-question option key mode and normalize both sides to 0..3
        $opts = json_decode($question->options, true) ?? [];
        $mode = $detectOptionKeyMode(is_array($opts) ? $opts : []);
        $userIndex = $normalizeUserAnswerIndex($studentAnswer);
        $correctIndex = $normalizeCorrectAnswerIndex($question->correct_answer, $mode);

        if ($studentAnswer === null || $studentAnswer === '') {
            $notAttempted++;
        } elseif (!is_null($userIndex) && !is_null($correctIndex) && $userIndex === $correctIndex) {
            $correct++;
            $score += $question->marks; // add marks for correct answer
        } else {
            $wrong++;
            // if you want negative marking, e.g., -1/4th:
            // $score -= $question->marks * 0.25;
        }
    }

    $totalQuestions = count($qids);

        return view('backend.testseries.exam-result', [
        'exam' => $exam,
        'questions' => $questions,
        'answers' => $answers,
        'correct' => $correct,
        'wrong' => $wrong,
        'notAttempted' => $notAttempted,
        'totalQuestions' => $totalQuestions,
        'score' => $score,
        'maxScore' => $maxScore,
        'optionMap'=>$optionMap
    ]);  
    } 
    
    public function myTestList($id){
        
        
        $tp = TestSeriesPurchase::where("id",$id)->where("payment_status",'paid')->where("user_id",Auth::user()->id)->first();
        if(!$tp){
            return abort(404);
        }
        
        $testList = TestList::where("test_series_id",$tp->test_series_id)->orderBy("sort_order","asc")->where('status','active')->get();
        foreach($testList as $test){
             $myExam = MyExam::where("test_series_purchase_id",$id)->where("user_id",Auth::user()->id)->where("exam_id",$test->id)->where('status','completed')->orderBy("id","desc")->first();
            $test->myExam = $myExam;
        }
        
        // dd($testList);
        
     	return view('backend.testseries.list',compact('testList','tp'));
        
    }
    
    public function myTestSeries(){
        
        $testSeries = TestSeriesPurchase::with('course','testSeries')->where("user_id",Auth::user()->id)->where("payment_status","paid")->orderBy("id","desc")->get();
        // dd($testSeries);
     	return view('backend.testseries.index',compact('testSeries'));
    }
    
    
    public function paySuccess(){
        // dd();
        if(Session::has('test_success')){
            // Session::forget('test_success');
             return view('rzp-test-success');
             
        }else{
            return redirect('/user/dashboard');
        }
    }
    
    public function payConfirm($id, Request $request){
        
          $keyId = env('RZP_KEY');
    $keySecret = env('RZP_SECRET');
        $razorpay_payment_id = $request->razorpay_payment_id;
        
        $razorpay_order_id = $request->razorpay_order_id;
        $razorpay_signature = $request->razorpay_signature;
        
        $tp = TestSeriesPurchase::where("rzp_order_id",$razorpay_order_id)->first();
        
        if(!$tp){
            return abort(404);
        }
        
       $order_id = $tp->rzp_order_id;

    // API endpoint to fetch payments of a specific order
    $url = "https://api.razorpay.com/v1/orders/$order_id/payments";

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret); // Basic Auth
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // Execute request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        exit;
    }

    curl_close($ch);

    $result = json_decode($response, true);


  

//dd($clean);
    if (isset($result['items']) && count($result['items']) > 0) {
        $payment = $result['items'][0];
        $status = $payment['status'];

        if ($status === 'captured') {
           
          $tp->rzp_payment_id = $payment['id'];
        $tp->payment_status = 'paid';
        $tp->status='active';
        $tp->update();
        
          $phone = $result['items'][0]['contact'];
$clean = preg_replace('/^\+91/', '', $phone);



        
        $user = User::find($tp->user_id);
        
        if(!$user->phone){
            $user->phone = $clean;
            $user->update();
        }
        $course = Course::find($tp->course_id);
        
          $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'test_series_enrollment_v1',
            'destination' => '+91'.$user->phone,
            'userName' => $user->name,
            'source' => 'test_series_enrollment_v1',
            'templateParams' => [$course->title],
            'tags' => ['test_series_enrollment_v1', 'new-test_series_enrollment_v1'],
            'attributes' => ['user_id' => $user->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
         
          Mail::to($user->email)->send(new StudentTestByEmail($course));
           
           

        } else {
            $tp->cron_checked='1';
        $tp->update();
        }
    } else {
       $tp->cron_checked='1';
        $tp->update();
    }
         
        Session::put("test_success",true);
        return redirect('/purchase/success');
    }
    
    
    public function deleteTest($id)
{
    $test = TestList::findOrFail($id);
    $testSeriesId = $test->test_series_id; // store series id before deletion
    $test->delete();

    return redirect()
        ->route('admin.testseries.testlist', $testSeriesId)
        ->with('success', 'Test deleted successfully!');
}

    
      public function editTest($id)
    {
        $test = TestList::findOrFail($id);
        
        // dd($test);
        $ts = TestSeries::findOrFail($test->test_series_id);
        $chapters = Lesson::where('course_id', $ts->course_id)->get();
        $sections = Subject::with('chapterlist.lesson')->where('test_id',$id)->get();
        
        // print_r($sections->toArray());
        // die();
        // dd($sections); 
        // Decode selected chapters for prefill
        $selectedChapters = $test->sections ? json_decode($test->sections, true) : [];
        $chapterQuestions =  $test->section_questions ? json_decode($test->section_questions, true) : [];
        return view('admin.test-list.edit', compact('test', 'chapters', 'selectedChapters','chapterQuestions','sections'));
    }

    // Update test
    public function updateTest(Request $request, $id)
    {
        
        // dd($request->all());
        $request->validate([
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'total_questions'  => 'required|integer|min:1',
            'duration'         => 'required|integer|min:1',
            
            'status'           => 'required|in:active,inactive',
        ]);

        $test = TestList::findOrFail($id);
        $test->name            = $request->name;
        $test->description     = $request->description;
        $test->total_questions = $request->total_questions;
        $test->duration        = $request->duration;
        
    $test->is_prev_year        = $request->is_prev_year;
        $test->sections        = $request->chapters ? json_encode($request->chapters) : null;
        
    $test->section_questions        = $request->chapter_questions ? json_encode($request->chapter_questions) : null;
        $test->status          = $request->status;
        $test->save();

        return redirect()
            ->route('admin.testseries.testlist', $test->test_series_id)
            ->with('success', 'Test updated successfully!');
    }
    
    
    public function saveTest(Request $request,$id)
{
    // Validate request
    $request->validate([
       
        'name'             => 'required|string|max:255',
        'description'      => 'nullable|string',
        'total_questions'  => 'required|integer|min:1',
        'duration'         => 'required|integer|min:1',
       
        'status'           => 'required|in:active,inactive',
    ]);
    
    // dd(json_encode($request->chapter_questions));

    // Save test
    $test = new TestList();
    $test->test_series_id  = $id;
    $test->name            = $request->name;
    $test->description     = $request->description;
    $test->total_questions = $request->total_questions;
    $test->duration        = $request->duration;
    $test->is_prev_year        = $request->is_prev_year;
    
    // $test->chapters        = $request->chapters ? json_encode($request->chapters) : null;
    // $test->chapter_questions        = $request->chapter_questions ? json_encode($request->chapter_questions) : null;
    $test->status          = $request->status;
    $test->save();

    return redirect()
        ->route('admin.testseries.testlist', $id)
        ->with('success', 'Test created successfully!');
}


    public function addTest($id){
        $ts = TestSeries::find($id);
        $chapters = Lesson::where('course_id',$ts->course_id)->get();
         return view('admin.test-list.add', compact('chapters','id'));
    }
    
    public function testList($id,Request $request){
        
        
        if($request->order){
            $test = TestList::find($request->id);
            if($test){
                $test->sort_order = $request->order;
                $test->update();
            }
        }
        
        $testLists = TestList::with('testSeries')
            ->where('test_series_id',$id)
            ->orderBy('id', 'desc')
            ->get();
            
        
        return view('admin.test-list.index', compact('testLists','id'));
    }
    
    
    public function index(Request $request){
        
         if($request->order){
            $test = Course::find($request->id);
            if($test){
                $test->test_sort_order = $request->order;
                $test->update();
            }
        }
        
        
      $testSeries = TestSeries::with('course')
        ->when($request->course_id, function($query) use ($request) {
            return $query->where('course_id', $request->course_id);
        })
        ->orderBy("id","desc")
        ->get();
    
    $courses = Course::where('published', '=', 1)->get();
        return view('admin.test.index',compact('testSeries','courses'));
        
    }
    
    
    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|max:550',
        'course_id' => 'required|exists:courses,id',
        'price' => 'required|numeric|min:0',
        'offer_price' => 'nullable|numeric|min:0',
        'total_test' => 'required|integer|min:0',
    ]);

    TestSeries::create($request->all());

    return response()->json(['success' => 'Test series created successfully.']);
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|max:550',
        'course_id' => 'required|exists:courses,id',
        'price' => 'required|numeric|min:0',
        'offer_price' => 'nullable|numeric|min:0',
        'total_test' => 'required|integer|min:0',
    ]);

    $testSeries = TestSeries::findOrFail($id);
    $testSeries->update($request->all());

    return response()->json(['success' => 'Test series updated successfully.']);
}

public function destroy($id)
{
    $testSeries = TestSeries::findOrFail($id);
    $testSeries->delete();

    return response()->json(['success' => 'Test series deleted successfully.']);
}
    
    
    
    
    
    
    // AuthController.php
public function ajaxLogin(Request $request){
    if(Auth::attempt($request->only('email','password'))){
        return response()->json(['success'=>true]);
    }
    return response()->json(['success'=>false, 'message'=>'Invalid credentials']);
}

public function ajaxRegister(Request $request){
    $request->validate([
        'first_name'=>'required',
        'last_name'=>'required',
        'email'=>'required|email|unique:users,email',
        'phone'=>'required',
        'password'=>'required|confirmed|min:6'
    ]);

    $user = User::create([
        'first_name'=>$request->first_name,
        'last_name'=>$request->last_name,
        'email'=>$request->email,
        'phone'=>$request->phone,
        'password'=>bcrypt($request->password)
    ]);
    $user->assignRole('student');
    Auth::login($user);

    return response()->json(['success'=>true]);
}




public function buyTest($id){
    
    $id = base64_decode($id);
    $testSeries = TestSeries::findOrFail($id);
  $tp = new TestSeriesPurchase();
  $tp->user_id = Auth::user()->id;
  $tp->test_series_id = $id;
  $tp->course_id = $testSeries->course_id;
  $tp->amount = $testSeries->offer_price;
  $tp->save();
  
   $rzp_id=   $this->createRzpOrder("ts_".$tp->id,$testSeries->offer_price);
    
    $tp->rzp_order_id = $rzp_id;
    $tp->update();
    
    
    return view('rzp-test',compact('testSeries','rzp_id','tp'));
}
    
    
public function purchaseCron(){
    $keyId = env('RZP_KEY');
    $keySecret = env('RZP_SECRET');


$purchases = TestSeriesPurchase::where('cron_checked','0')->where('payment_status','unpaid')->get();

// $purchases = TestSeriesPurchase::where('cron_checked','1')->where('payment_status','paid')->get();
foreach ($purchases as $tp){
    // The order_id you want to verify
    $order_id = $tp->rzp_order_id;

    // API endpoint to fetch payments of a specific order
    $url = "https://api.razorpay.com/v1/orders/$order_id/payments";

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $keyId . ":" . $keySecret); // Basic Auth
    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // Execute request
    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'cURL error: ' . curl_error($ch);
        exit;
    }

    curl_close($ch);

    $result = json_decode($response, true);

//dd($result);
  

//dd($clean);
    if (isset($result['items']) && count($result['items']) > 0) {
        $payment = $result['items'][count($result['items'])-1];
        $status = $payment['status'];

        if ($status === 'captured') {
           
          $tp->rzp_payment_id = $payment['id'];
        $tp->payment_status = 'paid';
        $tp->status='active';
        $tp->cron_checked='1';
        $tp->update();
        
          $phone = $result['items'][0]['contact'];
$clean = preg_replace('/^\+91/', '', $phone);



        
        $user = User::find($tp->user_id);
        
        if(!$user->phone){
            $user->phone = $clean;
            $user->update();
        }
        $course = Course::find($tp->course_id);
        
          $whatsappPayload = [
            'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
            'campaignName' => 'test_series_enrollment_v1',
            'destination' => '+91'.$user->phone,
            'userName' => $user->name,
            'source' => 'test_series_enrollment_v1',
            'templateParams' => [$course->title],
            'tags' => ['test_series_enrollment_v1', 'new-test_series_enrollment_v1'],
            'attributes' => ['user_id' => $user->id],
        ];
        
         $xt= AiSensy::send($whatsappPayload);
         
          Mail::to($user->email)->send(new StudentTestByEmail($course));
           
           

        } else {
            $tp->cron_checked='1';
        $tp->update();
        }
    } else {
       $tp->cron_checked='1';
        $tp->update();
    }
}

}

      public function createRzpOrder($rep,$amount){
        $ch = curl_init();
$data=array(
    "amount"=>$amount*100,
    "currency"=>"INR",
    "receipt"=>$rep
    );
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERPWD, env('RZP_KEY') . ':' . env('RZP_SECRET'));

$headers = array();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$rd = json_decode($result,true);

return $rd["id"];
    }
    
    
    
      public function category($slug){

     $course = Course::where('slug',$slug)->first();

     $courses=Course::where('published', '=', 1)->orderBy("test_sort_order","asc")->get();
     
     $testSeries=[];
     if($course){
         $testSeries = TestSeries::where('course_id',$course->id)->orderBy("offer_price","desc")->get();
     }
     $alreadyPurchased=[];
    if(Auth::user()){
        
        $alreadyPurchased = TestSeriesPurchase::where("user_id",Auth::user()->id)->where("payment_status","paid")->where('status','active')->get()->pluck("test_series_id")->toArray();
    }

    return view('category-or-course-series',compact('courses','course','testSeries','alreadyPurchased'));
     
     

     
  }
} 