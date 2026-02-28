<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MockSeries;
use App\Models\MockList;
use App\Models\Course;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;

class MockSeriesController extends Controller
{
    public function index(Request $request){
        
         if($request->order){
            $mock = Course::find($request->id);
            if($mock){
                $mock->mock_sort_order = $request->order;
                $mock->update();
            }
        }
        
        
      $mockSeries = MockSeries::with('course')
        ->when($request->course_id, function($query) use ($request) {
            return $query->where('course_id', $request->course_id);
        })
        ->orderBy("id","desc")
        ->get();
    
    $courses = Course::where('published', '=', 1)->get();
        return view('admin.mock.index',compact('mockSeries','courses'));
        
    }
    
    
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:550',
            'course_id' => 'required|exists:courses,id',
            'total_test' => 'required|integer|min:0',
        ]);

        MockSeries::create($request->all());

        return response()->json(['success' => 'Mock series created successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:550',
            'course_id' => 'required|exists:courses,id',
            'total_test' => 'required|integer|min:0',
        ]);

        $mockSeries = MockSeries::findOrFail($id);
        $mockSeries->update($request->all());

        return response()->json(['success' => 'Mock series updated successfully.']);
    }

    public function destroy($id)
    {
        $mockSeries = MockSeries::findOrFail($id);
        $mockSeries->delete();

        return response()->json(['success' => 'Mock series deleted successfully.']);
    }
    
    public function testList($id)
    {
        // Handle sort order update
        if(request()->has('order') && request()->has('id')){
            $mock = MockList::find(request()->id);
            if($mock){
                $mock->sort_order = request()->order;
                $mock->update();
                return response()->json(['success' => true]);
            }
        }
        
        $mockLists = MockList::where('mock_series_id', $id)
                            ->orderBy('sort_order', 'asc')
                            ->orderBy('id', 'desc')
                            ->get();
        
        return view('admin.mock-list.index', compact('mockLists', 'id'));
    }
    
    public function addTest($id)
    {
        return view('admin.mock-list.add', compact('id'));
    }
    
    public function saveTest(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:550',
            'total_questions' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        $mock = new MockList();
        $mock->mock_series_id = $id;
        $mock->name = $request->name;
        $mock->description = $request->description;
        $mock->total_questions = $request->total_questions;
        $mock->duration = $request->duration;
        $mock->status = $request->status;
        $mock->is_prev_year = $request->is_prev_year ?? '0';
        $mock->sort_order = 0;
        $mock->save();

        return redirect()->route('mockseries.testlist', $id)->with('success', 'Mock added successfully.');
    }
    
    public function editTest($id)
    {
        $mock = MockList::findOrFail($id);
        
        // Get all sections for this mock
        $sections = \App\Models\Subject::where('mock_id', $id)
            ->with(['chapterlist.lesson'])
            ->get();
        
        // Get selected chapters from mock's sections field
        $selectedChapters = $mock->sections ?? [];
        
        // Get chapter questions from mock's section_questions field
        $chapterQuestions = $mock->section_questions ?? [];
        
        \Log::info('Edit Test - Loading Data:', [
            'mock_id' => $id,
            'selectedChapters' => $selectedChapters,
            'chapterQuestions' => $chapterQuestions
        ]);
        
        return view('admin.mock-list.edit', compact('mock', 'sections', 'selectedChapters', 'chapterQuestions'));
    }
    
    public function updateTest(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:550',
            'total_questions' => 'required|integer|min:1',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
        ]);

        \Log::info('Update Test - Request Data:', [
            'chapters' => $request->chapters,
            'chapter_questions' => $request->chapter_questions
        ]);

        $mock = MockList::findOrFail($id);
        $mock->name = $request->name;
        $mock->description = $request->description;
        $mock->total_questions = $request->total_questions;
        $mock->duration = $request->duration;
        $mock->is_prev_year = $request->is_prev_year ?? '0';
        $mock->sections = $request->chapters;
        $mock->section_questions = $request->chapter_questions;
        $mock->status = $request->status;
        
        \Log::info('Update Test - Before Save:', [
            'sections' => $mock->sections,
            'section_questions' => $mock->section_questions
        ]);
        
        $mock->save();
        
        \Log::info('Update Test - After Save:', [
            'id' => $mock->id,
            'sections' => $mock->sections,
            'section_questions' => $mock->section_questions
        ]);

        return redirect()->route('mockseries.testlist', $mock->mock_series_id)->with('success', 'Mock test updated successfully.');
    }
    
    public function deleteTest($id)
    {
        $mock = MockList::findOrFail($id);
        $mock->delete();
        
        return redirect()->back()->with('success', 'Mock deleted successfully.');
    }
    
    public function subjects($id)
    {
        $mock = MockList::findOrFail($id);
        $subjects = \App\Models\Subject::where('mock_id', $id)->get();
        
        return view('admin.mock-list.subjects', compact('mock', 'subjects'));
    }
    
    public function storeSubject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:active,inactive',
            'difficulty' => 'nullable|in:easy,medium,hard',
        ]);

        \App\Models\Subject::create([
            'mock_id' => $id,
            'name' => $request->name,
            'status' => $request->status,
            'difficulty' => $request->difficulty,
        ]);

        return redirect()->route('mockseries.subjects', $id)->with('success', 'Section added successfully.');
    }
    
    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:255',
            'status' => 'required|in:active,inactive',
            'difficulty' => 'nullable|in:easy,medium,hard',
        ]);

        $subject = \App\Models\Subject::findOrFail($id);
        $subject->update([
            'name' => $request->name,
            'status' => $request->status,
            'difficulty' => $request->difficulty,
        ]);

        return redirect()->route('mockseries.subjects', $subject->mock_id)->with('success', 'Section updated successfully.');
    }
    
    public function destroySubject($id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $mockId = $subject->mock_id;
        $subject->delete();

        return redirect()->route('mockseries.subjects', $mockId)->with('success', 'Section deleted successfully.');
    }
    
    public function chapters($id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $mock = MockList::findOrFail($subject->mock_id);
        
        // Get all lessons/chapters from the course associated with the mock series
        $mockSeries = $mock->mockSeries;
        $chapters = \App\Models\Lesson::where('course_id', $mockSeries->course_id)
                    ->where('published', 1)
                    ->orderBy('position', 'asc')
                    ->get();
        
        // Get already assigned chapters
        $assigned = \App\Models\SubjectChapter::where('subject_id', $id)
                    ->pluck('lesson_id')
                    ->toArray();
        
        return view('admin.mock-list.chapters', compact('subject', 'chapters', 'assigned', 'mock'));
    }
    
    public function storeChapters(Request $request, $id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        
        // Delete existing chapter assignments
        \App\Models\SubjectChapter::where('subject_id', $id)->delete();
        
        // Add new chapter assignments
        if ($request->has('chapters') && is_array($request->chapters)) {
            foreach ($request->chapters as $chapterId) {
                \App\Models\SubjectChapter::create([
                    'subject_id' => $id,
                    'lesson_id' => $chapterId,
                ]);
            }
        }
        
        return redirect()->route('mockseries.chapters', $id)->with('success', 'Chapters assigned successfully.');
    }
    
    /**
     * Display a listing of mock series for the authenticated user
     * based on their batch enrollments.
     *
     * @return \Illuminate\View\View
     */
    public function myMockSeries()
    {
        // Get all batches the student is enrolled in
        $userBatches = \App\Models\StudentTeacherBatch::where('uid', \Auth::user()->id)
            ->pluck('bid')
            ->toArray();
        
        if (empty($userBatches)) {
            return view('frontend.mockseries.index', ['mockSeries' => collect([])]);
        }
        
        // Get all mock series assigned to these batches
        // Using a subquery to get distinct batch_id, mock_series_id combinations
        $mockSeries = \App\Models\BatchMockTest::with(['mockSeries', 'mockSeries.course', 'batch'])
            ->whereIn('batch_id', $userBatches)
            ->whereHas('mockSeries', function($query) {
                $query->where('status', '1'); // Only active mock series
            })
            ->get()
            ->unique(function($item) {
                return $item->batch_id . '-' . $item->mock_series_id;
            })
            ->sortByDesc('created_at')
            ->values();
        
        return view('frontend.mockseries.index', compact('mockSeries'));
    }
    
    /**
     * Display mock tests for a specific mock series
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function myMockList($id)
    {
        $batchMockTest = \App\Models\BatchMockTest::with(['mockSeries', 'mockSeries.course', 'batch'])
            ->where('id', $id)
            ->first();
        
        if (!$batchMockTest) {
            return redirect()->route('myMockSeries.index')
                ->with('error', 'Mock series not found.');
        }
        
        // Verify the user is enrolled in this batch
        $isEnrolled = \App\Models\StudentTeacherBatch::where('uid', \Auth::user()->id)
            ->where('bid', $batchMockTest->batch_id)
            ->exists();
        
        if (!$isEnrolled) {
            return redirect()->route('myMockSeries.index')
                ->with('error', 'You are not enrolled in this batch.');
        }
        
        // Get completed and ongoing (in progress) lesson/chapter IDs for this batch
        $completedLessonIds = \App\Models\LessionComplete::where('batch_id', $batchMockTest->batch_id)
            ->whereIn('status', ['completed', 'ongoing'])
            ->pluck('lession_id')
            ->toArray();
        
        // Get all mock tests for this mock series and batch
        // DATE-BASED VISIBILITY: Show ALL mocks to display status
        $allMockTests = \DB::table('batch_mock_tests as bmt')
            ->join('mock_list as ml', 'bmt.mock_list_id', '=', 'ml.id')
            ->where('bmt.batch_id', $batchMockTest->batch_id)
            ->where('bmt.mock_series_id', $batchMockTest->mock_series_id)
            ->whereNotNull('bmt.mock_list_id')
            ->select('ml.*', 'bmt.id as batch_mock_test_id', 'bmt.batch_id', 'bmt.scheduled_at', 'bmt.end_date', 'bmt.is_active')
            ->orderBy('bmt.sort_order')
            ->get();
        
        // AUTO-SAVE QUESTIONS for newly active scheduled mocks
        // and calculate test status for each mock
        foreach ($allMockTests as $mockTest) {
            $now = \Carbon\Carbon::now();
            $mockTest->test_status = 'not_available'; // default
            $mockTest->status_message = '';
            
            // Determine test status based on schedule and end_date
            if ($mockTest->is_active == 1) {
                // Manually activated - check if available until end_date
                if (!empty($mockTest->end_date)) {
                    // Parse with explicit timezone to avoid timezone mismatch
                    $endDate = \Carbon\Carbon::parse($mockTest->end_date, config('app.timezone'));
                    // Use isBefore (lt) for comparison - if now is before end_date, it's available
                    if ($now->lt($endDate) || $now->isSameDay($endDate)) {
                        // Still within available window
                        $mockTest->test_status = 'available';
                        $mockTest->status_message = 'Available until ' . $endDate->copy()->startOfDay()->format('d M Y') . ' 11:59 PM (local time zone)';
                    } else {
                        // Deadline passed
                        $mockTest->test_status = 'missed';
                        $mockTest->status_message = 'Missed (was available until ' . $endDate->copy()->startOfDay()->format('d M Y') . ' 11:59 PM (local time zone))';
                    }
                } else {
                    // No end_date set yet - set to end of today (11:59 PM)
                    $endDateTime = $now->copy()->endOfDay();
                    \DB::table('batch_mock_tests')
                        ->where('id', $mockTest->batch_mock_test_id)
                        ->update(['end_date' => $endDateTime]);
                    
                    $mockTest->test_status = 'available';
                    $mockTest->status_message = 'Available until ' . $endDateTime->copy()->startOfDay()->format('d M Y') . ' 11:59 PM (local time zone)';
                    $mockTest->end_date = $endDateTime->format('Y-m-d H:i:s');
                }
            } elseif (!empty($mockTest->scheduled_at)) {
                $scheduledDate = \Carbon\Carbon::parse($mockTest->scheduled_at, config('app.timezone'))->startOfDay();
                $endDate = !empty($mockTest->end_date) 
                    ? \Carbon\Carbon::parse($mockTest->end_date, config('app.timezone'))->endOfDay() 
                    : $scheduledDate->copy()->endOfDay(); // Same day, 11:59 PM
                
                if ($now->lt($scheduledDate)) {
                    // Future test
                    $mockTest->test_status = 'upcoming';
                    $mockTest->status_message = 'Available on ' . $scheduledDate->format('d M Y');
                } elseif ($now->gte($scheduledDate) && $now->lte($endDate)) {
                    // Within same-day window (00:00 to 23:59)
                    $mockTest->test_status = 'available';
                    $mockTest->status_message = 'Available until ' . $endDate->copy()->startOfDay()->format('d M Y') . ' 11:59 PM (local time zone)';
                } else {
                    // Missed - window has passed
                    $mockTest->test_status = 'missed';
                    $mockTest->status_message = 'Missed (was available on ' . $scheduledDate->format('d M Y') . ')';
                }
            }
            
            // Auto-save questions if test is available
            if ($mockTest->test_status == 'available') {
                // Check if questions already exist for this batch+mock combination
                $questionsExist = \DB::table('batch_mock_questions')
                    ->where('batch_id', $mockTest->batch_id)
                    ->where('mock_list_id', $mockTest->id)
                    ->exists();
                
                if (!$questionsExist) {
                    // Auto-save questions for this mock
                    $this->autoSaveMockQuestions($mockTest->id, $mockTest->batch_id);
                }
            }
        }
        
        // Filter mock tests based on batch progress
        // Only show mock tests where ALL required chapters are completed or in progress (ongoing)
        $mockTests = $allMockTests->filter(function($mockTest) use ($completedLessonIds) {
            // Decode section_questions if it's a JSON string
            $sectionQuestions = is_string($mockTest->section_questions) 
                ? json_decode($mockTest->section_questions, true) 
                : $mockTest->section_questions;
            
            if (empty($sectionQuestions) || !is_array($sectionQuestions)) {
                // If no section_questions defined, show the mock test
                return true;
            }
            
            // Extract all chapter IDs from section_questions
            // Structure: {"752":{"792":"5","793":"3","794":"2"}}
            // Where 752 is section_id and 792, 793, 794 are chapter_ids
            $requiredChapterIds = [];
            foreach ($sectionQuestions as $sectionId => $chapters) {
                if (is_array($chapters)) {
                    foreach ($chapters as $chapterId => $questionCount) {
                        $requiredChapterIds[] = (int)$chapterId;
                    }
                }
            }
            
            // Check if all required chapters are completed
            if (empty($requiredChapterIds)) {
                // If no chapters defined, show the mock test
                return true;
            }
            
            // Only show mock test if ALL required chapters are completed or in progress
            foreach ($requiredChapterIds as $requiredChapterId) {
                if (!in_array($requiredChapterId, $completedLessonIds)) {
                    // At least one required chapter has not been started
                    return false;
                }
            }
            
            // All required chapters are completed or in progress
            return true;
        })->values(); // Reset array keys
        
        return view('frontend.mockseries.list', compact('batchMockTest', 'mockTests'));
    }
    
    /**
     * Show waiting page before starting mock exam
     *
     * @param int $mock_id
     * @param int $batch_mock_test_id
     * @return \Illuminate\View\View
     */
    public function waitingMockExam($mock_id, $batch_mock_test_id)
    {
        // Get batch mock test details
        $batchMockTest = DB::table('batch_mock_tests')
            ->where('id', $batch_mock_test_id)
            ->first();
        
        if (!$batchMockTest) {
            abort(404, 'Mock test not found.');
        }
        
        // Get mock test details
        $mock = \App\Models\MockList::findOrFail($mock_id);
        
        // Check if exam already exists and is started
        $myExam = \App\Models\MyExam::where("batch_mock_test_id", $batch_mock_test_id)
            ->where('user_id', \Auth::user()->id)
            ->where("exam_id", $mock_id)
            ->first();
        
        // If exam already exists (resuming), show shorter countdown (2 seconds)
        // If new exam, show full countdown (30 seconds)
        $secondsUntilStart = ($myExam && $myExam->status == 'started') ? 2 : 30;
        
        return view('backend.testseries.waiting-mock', compact('mock', 'secondsUntilStart', 'mock_id', 'batch_mock_test_id'));
    }
    
    /**
     * Handle mock test attempt - creates or resumes exam
     *
     * @param int $mock_id
     * @param int $batch_mock_test_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function myMockAttempt($mock_id, $batch_mock_test_id)
    {
        // SAFETY CHECK: Prevent direct URL access before scheduled time
        // Get batch_id and check if mock is available
        $batchMockTest = DB::table('batch_mock_tests')
            ->where('id', $batch_mock_test_id)
            ->first();
        
        if (!$batchMockTest) {
            abort(403, 'Mock test not found or not available.');
        }
        
        // Check if mock is available based on:
        // 1. Manual activation (is_active = 1) with 24hr window check OR
        // 2. Scheduled date window (between scheduled_at and end_date)
        $isAvailable = false;
        $now = \Carbon\Carbon::now();
        
        if ($batchMockTest->is_active == 1) {
            // Check window for manually activated tests (available until end_date)
            if (!empty($batchMockTest->end_date)) {
                $endDate = \Carbon\Carbon::parse($batchMockTest->end_date, config('app.timezone'));
                // Allow access if now is before end_date OR on the same day
                if ($now->lt($endDate) || $now->isSameDay($endDate)) {
                    $isAvailable = true;
                }
            } else {
                // No end_date yet, set it to end of today (11:59 PM)
                $endDateTime = $now->copy()->endOfDay();
                DB::table('batch_mock_tests')
                    ->where('id', $batch_mock_test_id)
                    ->update(['end_date' => $endDateTime]);
                $isAvailable = true;
            }
        } elseif ($batchMockTest->scheduled_at !== null) {
            $scheduledDate = \Carbon\Carbon::parse($batchMockTest->scheduled_at, config('app.timezone'))->startOfDay();
            $endDate = !empty($batchMockTest->end_date) 
                ? \Carbon\Carbon::parse($batchMockTest->end_date, config('app.timezone'))->endOfDay() 
                : $scheduledDate->copy()->endOfDay(); // Same day
            
            if ($now->gte($scheduledDate) && $now->lte($endDate)) {
                $isAvailable = true;
            }
        }
        
        if (!$isAvailable) {
            abort(403, 'Mock test is not available yet. Please wait for the scheduled date or contact your teacher.');
        }
        
        // Check if there's an active exam for this user and mock
        $myExam = \App\Models\MyExam::where("batch_mock_test_id", $batch_mock_test_id)
            ->where('status', 'started')
            ->where("user_id", \Auth::user()->id)
            ->where("exam_id", $mock_id)
            ->first();
        
        if (!$myExam) {
            // Get mock test details from mock_list (same as test series uses test_list)
            $mock = \App\Models\MockList::findOrFail($mock_id);
            
            // Use batch_id from previously fetched batchMockTest
            $batch_id = $batchMockTest->batch_id;
            
            // FETCH QUESTIONS FROM batch_mock_questions TABLE FOR THIS BATCH AND MOCK
            $batchMockQuestions = DB::table('batch_mock_questions')
                ->where('batch_id', $batch_id)
                ->where('mock_list_id', $mock_id)
                ->orderBy('question_order')
                ->get();
            
            if ($batchMockQuestions->isEmpty()) {
                return redirect()->back()->with('error', 'No questions have been assigned to this mock test for your batch. Please contact your teacher.');
            }
            
            // Group questions by section_id
            $questions = [];
            foreach ($batchMockQuestions as $bmq) {
                $sectionId = $bmq->section_id;
                if (!isset($questions[$sectionId])) {
                    $questions[$sectionId] = [];
                }
                $questions[$sectionId][] = $bmq->question_id;
            }
            
            if (empty($questions)) {
                return redirect()->back()->with('error', 'No questions could be loaded for this mock.');
            }
            
            // Create new exam (same flow as test series)
            $exam = new \App\Models\MyExam();
            $exam->batch_mock_test_id = $batch_mock_test_id;
            $exam->user_id = \Auth::user()->id;
            $exam->exam_id = $mock_id;
            $exam->duration = $mock->duration;
            $exam->questions = json_encode($questions);
            $exam->status = 'started';
            $exam->exam_date_time = date("Y-m-d H:i:s");
            $exam->last_ping = time();
            $exam->current_question = 1;
            $exam->save();
            
            return redirect()->to(url('user/mock-exam/' . $exam->id));
        } else {
            return redirect()->to(url('user/mock-exam/' . $myExam->id));
        }
    }
    
    /**
     * Show waiting page before starting mock exam (or redirect straight to exam)
     * If user lands here (e.g. bookmarked URL), redirect immediately to take exam.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function startMockExam($id)
    {
        $exam = \App\Models\MyExam::where("id", $id)
            ->where("user_id", \Auth::user()->id)
            ->first();
        
        if (!$exam) {
            return abort(404);
        }
        
        return redirect()->route('myMockSeries.takeExam', $exam->id);
    }
    
    /**
     * Take the mock exam
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function takeMockExam($id)
    {
        $exam = \App\Models\MyExam::where("id", $id)
            ->where("user_id", \Auth::user()->id)
            ->first();
        
        if (!$exam) {
            return abort(404);
        }
        
        $qlist = [];
        $qids = json_decode($exam->questions, true);
        $user = \App\Models\Auth\User::find(\Auth::user()->id);
        
        if (empty($qids)) {
            return redirect()->back()->with('error', 'No questions found for this exam. Please contact support.');
        }
        
        foreach ($qids as $sectionId => $qdata) {
            if (empty($qdata)) {
                continue;
            }
            $questions = \App\Models\Question::whereIn("id", $qdata)->get();
            
            foreach ($questions as $q) {
                $question_text = json_decode($q->question_text, true);
                $options = json_decode($q->options, true);
                
                $options_en = [];
                
                foreach ($options as $key => $opt) {
                    if (is_array($opt)) {
                        $options_en[] = $opt['en'] ?? $opt;
                    } else {
                        $options_en[] = $opt;
                    }
                }
                
                $qlist[$sectionId][] = [
                    'id' => $q->id,
                    'question_en' => is_array($question_text) ? ($question_text['en'] ?? $question_text) : $question_text,
                    'options_en' => $options_en,
                ];
            }
        }
        
        $subjects = [];
        $subjectList = [];
        
        foreach ($qlist as $k => $ql) {
            $subjectList[] = $k;
            $course = \App\Models\Subject::find($k);
            if ($course) {
                $subjects[$course->id] = ["id" => $course->id, "name" => $course->name, "questions" => $ql];
            } else {
                // Section not found - still add questions under section id so exam can render
                $subjects[(string) $k] = ["id" => (int) $k, "name" => "Section " . $k, "questions" => $ql];
            }
        }
        
        if (empty($subjects)) {
            return redirect()->back()->with('error', 'No questions could be loaded for this exam. Please contact support.');
        }
        
        $start = \Carbon\Carbon::parse($exam->exam_date_time);
        $end = $start->copy()->addMinutes($exam->duration);
        
        $examData = [
            'exam_id' => $id,
            'student' => [
                'name' => strtoupper($user->name),
                'id' => $user->auto_stu_id ?? $user->id,
                'my_exam_id' => $exam->id,
                'admit_card_photo' => $user->image,
                'database_photo' => $exam->photo ?? $user->picture
            ],
            'subjects' => $subjects,
            'exam_settings' => [
                'total_time' => $start->diffInSeconds($end),
                'max_tab_switches' => 30,
            ]
        ];
        
        return view('frontend.mockseries.take-exam', compact('examData'));
    }
    
    /**
     * Submit mock exam
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitMockExam(\Illuminate\Http\Request $request)
    {
        $exam = \App\Models\MyExam::find($request->student_id);
        $exam->answers = $request->answers;
        $exam->time_spent = $request->time_spent;
        $exam->status = 'completed';
        $exam->update();
        
        // Send WhatsApp notification about result
        try {
            $user = \App\Models\Auth\User::find($exam->user_id);
            $mockTest = \App\Models\MockList::find($exam->exam_id);
            
            if ($user && $user->phone && $mockTest) {
                \Log::info('============================================');
                \Log::info('MOCK TEST RESULT NOTIFICATION');
                \Log::info('Student: ' . $user->name . ' (ID: ' . $user->id . ')');
                \Log::info('Mock Test: ' . $mockTest->name . ' (ID: ' . $mockTest->id . ')');
                \Log::info('Phone: +91' . $user->phone);
                
                $whatsappPayload = [
                    'apiKey' => config('app.aisensy_api_key', env('AISENSY_API_KEY')),
                    'campaignName' => 'mock_test_result_v10',
                    'destination' => '+91' . $user->phone,
                    'userName' => ucwords(trim($user->name)),
                    'source' => 'mock_test_result',
                    'templateParams' => [
                        ucwords(trim($user->name)),
                        trim($mockTest->name),
                        base64_encode($exam->id),
                        base64_encode($exam->id)
                    ],
                    'tags' => ['mock_test', 'mock_test_result'],
                    'attributes' => ['user_id' => $user->id, 'exam_id' => $exam->id, 'mock_id' => $mockTest->id],
                ];
                
                \App\Models\AiSensy::send($whatsappPayload);
                \Log::info('✓ WhatsApp result notification SENT');
                \Log::info('============================================');
            }
        } catch (\Exception $e) {
            \Log::error('Error sending mock test result notification: ' . $e->getMessage());
        }
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Show mock exam result
     * PUBLIC ACCESS - Anyone can view results with the link (no login required)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function mockExamResult($id)
    {
        // Decode the ID if it's base64 encoded
        $decodedId = base64_decode($id, true);
        // If decoding was successful and result is numeric, use decoded ID, otherwise use original
        $examId = ($decodedId !== false && is_numeric($decodedId)) ? $decodedId : $id;
        
        // PUBLIC ACCESS: Remove user_id check to allow anyone to view the result
        $exam = \App\Models\MyExam::where("id", $examId)->first();
        
        if (!$exam) {
            return abort(404);
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
        
        return view('frontend.mockseries.result', compact('exam', 'totalQuestions', 'correctAnswers', 'wrongAnswers', 'unattempted', 'score', 'percentage'));
    }
    
    /**
     * Show mock exam answer key
     * PUBLIC ACCESS - Anyone can view answer key with the link (no login required)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function mockExamAnswerKey($id)
    {
        // PUBLIC ACCESS: Remove user authentication check to allow anyone to view the answer key
        $exam = \App\Models\MyExam::where("id", $id)->first();
        
        if (!$exam) {
            return abort(404);
        }
        
        // Get questions and answers
        $questions = json_decode($exam->questions, true);
        $userAnswers = json_decode($exam->answers, true) ?? [];
        
        // Build answer key data
        $answerKeyData = [];
        $questionNumber = 1;
        
        foreach ($questions as $sectionId => $questionIds) {
            $section = \App\Models\Subject::find($sectionId);
            $sectionName = $section ? $section->name : "Section " . $sectionId;
            
            foreach ($questionIds as $qid) {
                $question = \App\Models\Question::find($qid);
                
                if (!$question) {
                    continue;
                }
                
                // Parse question text - preserve base64 images
                $questionText = $question->question_text;
                if (is_string($questionText)) {
                    $decoded = json_decode($questionText, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                        $questionText = $decoded['en'];
                    }
                }
                if (is_array($questionText)) {
                    $questionText = $questionText['en'] ?? json_encode($questionText);
                }
                
                // Parse options - preserve base64 images
                $options = $question->options;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                $parsedOptions = [];
                $optionKeyMapping = []; // Map old keys to new numeric indices
                
                if (is_array($options)) {
                    $index = 0;
                    foreach ($options as $key => $opt) {
                        $optionKeyMapping[$key] = $index; // Map 'option1' => 0, 'option2' => 1, etc.
                        if (is_array($opt)) {
                            $parsedOptions[$index] = $opt['en'] ?? $opt;
                        } else {
                            $parsedOptions[$index] = $opt;
                        }
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
                
                $answerKeyData[] = [
                    'question_number' => $questionNumber,
                    'section_name' => $sectionName,
                    'question_text' => $questionText,
                    'options' => $parsedOptions,
                    'correct_answer' => $correctAnswer,
                    'user_answer' => $userAnswer,
                    'is_correct' => $isCorrect,
                    'is_attempted' => ($userAnswer !== null)
                ];
                
                $questionNumber++;
            }
        }
        
        return view('frontend.mockseries.answer-key', compact('exam', 'answerKeyData'));
    }
    
    /**
     * Thank you page after submitting mock
     *
     * @return \Illuminate\View\View
     */
    public function mockThankYou()
    {
        return view('frontend.mockseries.exam-thank');
    }
    
    /**
     * Show mock exam answer key for admin (in backend)
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function adminMockExamAnswerKey($id)
    {
        $exam = \App\Models\MyExam::findOrFail($id);
        
        // Get student and mock test details
        $student = \App\Models\Auth\User::findOrFail($exam->user_id);
        $mockTest = \App\Models\MockList::findOrFail($exam->exam_id);
        
        // Get questions and answers
        $questions = json_decode($exam->questions, true);
        $userAnswers = json_decode($exam->answers, true) ?? [];
        
        // Build answer key data
        $answerKeyData = [];
        $questionNumber = 1;
        
        foreach ($questions as $sectionId => $questionIds) {
            $section = \App\Models\Subject::find($sectionId);
            $sectionName = $section ? $section->name : "Section " . $sectionId;
            
            foreach ($questionIds as $qid) {
                $question = \App\Models\Question::find($qid);
                
                if (!$question) {
                    continue;
                }
                
                // Parse question text - preserve base64 images
                $questionText = $question->question_text;
                if (is_string($questionText)) {
                    $decoded = json_decode($questionText, true);
                    if (json_last_error() === JSON_ERROR_NONE && isset($decoded['en'])) {
                        $questionText = $decoded['en'];
                    }
                }
                if (is_array($questionText)) {
                    $questionText = $questionText['en'] ?? json_encode($questionText);
                }
                
                // Parse options - preserve base64 images
                $options = $question->options;
                if (is_string($options)) {
                    $options = json_decode($options, true);
                }
                $parsedOptions = [];
                $optionKeyMapping = []; // Map old keys to new numeric indices
                
                if (is_array($options)) {
                    $index = 0;
                    foreach ($options as $key => $opt) {
                        $optionKeyMapping[$key] = $index; // Map 'option1' => 0, 'option2' => 1, etc.
                        if (is_array($opt)) {
                            $parsedOptions[$index] = $opt['en'] ?? $opt;
                        } else {
                            $parsedOptions[$index] = $opt;
                        }
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
                
                $answerKeyData[] = [
                    'question_number' => $questionNumber,
                    'section_name' => $sectionName,
                    'question_text' => $questionText,
                    'options' => $parsedOptions,
                    'correct_answer' => $correctAnswer,
                    'user_answer' => $userAnswer,
                    'is_correct' => $isCorrect,
                    'is_attempted' => ($userAnswer !== null)
                ];
                
                $questionNumber++;
            }
        }
        
        return view('backend.batch.mock-answer-key', compact('exam', 'answerKeyData', 'student', 'mockTest'));
    }
    
    /**
     * Auto-save questions for a mock test when it becomes active
     * This is called when scheduled_at time is reached
     *
     * @param int $mockListId
     * @param int $batchId
     * @return void
     */
    private function autoSaveMockQuestions($mockListId, $batchId)
    {
        try {
            \Log::info('autoSaveMockQuestions called', [
                'mock_list_id' => $mockListId,
                'batch_id' => $batchId
            ]);
            
            // Get the mock test
            $mockTest = MockList::find($mockListId);
            if (!$mockTest) {
                \Log::error('Mock test not found', ['mock_list_id' => $mockListId]);
                return;
            }
            
            // Get section_questions configuration from mock_list
            $sectionQuestions = is_string($mockTest->section_questions) 
                ? json_decode($mockTest->section_questions, true) 
                : $mockTest->section_questions;
            
            if (empty($sectionQuestions) || !is_array($sectionQuestions)) {
                \Log::warning('No section_questions configured', ['mock_list_id' => $mockListId]);
                return;
            }
            
            \Log::info('Section questions config:', ['section_questions' => $sectionQuestions]);
            
            // Start transaction
            DB::beginTransaction();
            
            // Generate and save questions
            $order = 0;
            $insertedCount = 0;
            
            foreach ($sectionQuestions as $sectionId => $chapters) {
                if (!is_array($chapters)) {
                    continue;
                }
                
                foreach ($chapters as $chapterId => $questionCount) {
                    // Get random questions from this chapter
                    $questions = \App\Models\Question::where('chapter_id', $chapterId)
                        ->inRandomOrder()
                        ->limit($questionCount)
                        ->pluck('id')
                        ->toArray();
                    
                    // Insert each question
                    foreach ($questions as $questionId) {
                        DB::table('batch_mock_questions')->insert([
                            'batch_id' => $batchId,
                            'mock_list_id' => $mockListId,
                            'question_id' => $questionId,
                            'section_id' => $sectionId,
                            'question_order' => $order++,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        $insertedCount++;
                    }
                }
            }
            
            DB::commit();
            
            \Log::info('Auto-saved mock questions successfully', [
                'mock_list_id' => $mockListId,
                'batch_id' => $batchId,
                'questions_inserted' => $insertedCount
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error in autoSaveMockQuestions: ' . $e->getMessage());
        }
    }
}
 