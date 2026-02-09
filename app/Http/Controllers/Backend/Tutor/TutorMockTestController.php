<?php

namespace App\Http\Controllers\Backend\Tutor;

use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestQuestionReport;
use App\Models\MockTestResult;
use App\Models\Batch;
use App\Models\TeacherBatch;
use App\Models\StudentTeacherBatch;
use App\Models\Auth\User;
use App\Services\NotificationService;
use App\Events\Backend\MockTestRescheduled;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class TutorMockTestController extends Controller
{
    protected $notificationService;

    public function __construct(\App\Services\NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    

    /**
     * Display available mock tests for tutor's batches
     */
    public function availableTests()
    {
        // Get tutor's batches
        $bids = TeacherBatch::where("tid", Auth::user()->id)->pluck('bid')->toArray();
        
        // Get all mock tests for courses that tutor teaches (draft, reviewed, published so tutor can preview/approve or schedule)
        $mockTests = MockTest::whereHas('courses', function($q) {
            $q->whereHas('teachers', function($t) {
                $t->where('course_user.user_id', '=', Auth::user()->id);
            });
        })
        ->when(\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status'), function ($q) {
            $q->whereIn('status', [MockTest::STATUS_DRAFT, MockTest::STATUS_REVIEWED, MockTest::STATUS_PUBLISHED]);
        }, function ($q) {
            $q->where('published', 1);
        })
        ->with(['courses', 'course', 'questions'])
        ->get();

        $batches = Batch::whereIn('id', $bids)->get();

        return view('backend.tutor.mocktests.available', compact('mockTests', 'batches'));
    }

    /**
     * Preview mock test questions
     */
    public function previewTest($id)
    {
        $mockTest = MockTest::with(['courses'])->findOrFail($id);
        
        // Verify tutor has access to at least one assigned course
        $hasCourseAccess = $mockTest->courses()
            ->whereHas('teachers', function ($t) {
                $t->where('course_user.user_id', '=', Auth::user()->id);
            })
            ->exists();

        if (!$hasCourseAccess) {
            return abort(403, 'You do not have access to this mock test.');
        }

        $questions = $mockTest->questions()->with('options')->get();

        return view('backend.tutor.mocktests.preview', compact('mockTest', 'questions'));
    }

    /**
     * Tutor approves a mock test (sets status to reviewed so it can be scheduled).
     */
    public function approveTest(Request $request, $id)
    {
        $mockTest = MockTest::findOrFail($id);

        $hasCourseAccess = $mockTest->courses()
            ->whereHas('teachers', function ($t) {
                $t->where('course_user.user_id', '=', Auth::user()->id);
            })
            ->exists();

        if (!$hasCourseAccess) {
            return abort(403, 'You do not have access to this mock test.');
        }

        if (!\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status')) {
            return redirect()->back()->withFlashWarning('Status column not available. Run migrations.');
        }

        $mockTest->status = MockTest::STATUS_REVIEWED;
        $mockTest->save();

        return redirect()->route('tutor.mocktests.preview', $id)
            ->withFlashSuccess('Mock test approved. You can now schedule it for a batch.');
    }

    /**
     * Show schedule form
     */
    public function scheduleForm($id)
    {
        $mockTest = MockTest::with(['courses'])->findOrFail($id);
        
        // Get tutor's batches for this course
        $bids = TeacherBatch::where("tid", Auth::user()->id)->pluck('bid')->toArray();
        $courseIds = $mockTest->courses->pluck('id')->toArray();
        if (empty($courseIds) && $mockTest->course_id) {
            $courseIds = [(int) $mockTest->course_id];
        }
        $batches = Batch::whereIn('id', $bids)
            ->when(!empty($courseIds), function ($q) use ($courseIds) {
                $q->whereIn('cid', $courseIds);
            })
            ->get();

        return view('backend.tutor.mocktests.schedule', compact('mockTest', 'batches'));
    }

    /**
     * Schedule a mock test for a batch
     */
    public function scheduleTest(Request $request)
    {
        $this->validate($request, [
            'mock_test_id' => 'required|exists:mock_tests,id',
            'batch_id' => 'required|exists:batches,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'timezone' => 'nullable|string'
        ]);

        $mockTest = MockTest::find($request->mock_test_id);
        if (!$mockTest) {
            return redirect()->back()->withFlashDanger('Mock test not found.');
        }
        if (\Illuminate\Support\Facades\Schema::hasColumn('mock_tests', 'status')) {
            $allowed = [\App\Models\MockTest::STATUS_PUBLISHED, \App\Models\MockTest::STATUS_REVIEWED];
            if (!in_array($mockTest->status, $allowed, true)) {
                return redirect()->back()->withFlashDanger('Mock test must be approved (or published by admin) before scheduling. Use "Approve" on the preview page.');
            }
        }

        // Verify tutor has access to this batch
        $hasBatch = TeacherBatch::where('tid', Auth::user()->id)
            ->where('bid', $request->batch_id)
            ->exists();

        if (!$hasBatch) {
            return redirect()->back()->withFlashDanger('You do not have access to this batch.');
        }

        // Check if already scheduled
        $existing = MockTestSchedule::where('mock_test_id', $request->mock_test_id)
            ->where('batch_id', $request->batch_id)
            ->first();

        if ($existing) {
            return redirect()->back()->withFlashWarning('This mock test is already scheduled for this batch.');
        }

        // Create schedule
        $schedule = MockTestSchedule::create([
            'mock_test_id' => $request->mock_test_id,
            'batch_id' => $request->batch_id,
            'scheduled_date' => $request->scheduled_date,
            'timezone' => $request->timezone ?? 'Asia/Kolkata',
            'status' => 'scheduled'
        ]);

        // Send notifications
        $this->notificationService->sendMockTestScheduled($schedule);

        return redirect()->route('tutor.mocktests.available')
            ->withFlashSuccess('Mock test scheduled successfully. Students have been notified.');
    }

    /**
     * Show reschedule form
     */
    public function rescheduleForm($scheduleId)
    {
        $schedule = MockTestSchedule::findOrFail($scheduleId);
        
        // Verify tutor has access
        $hasBatch = TeacherBatch::where('tid', Auth::user()->id)
            ->where('bid', $schedule->batch_id)
            ->exists();

        if (!$hasBatch) {
            return abort(403, 'You do not have access to this schedule.');
        }

        return view('backend.tutor.mocktests.reschedule', compact('schedule'));
    }

    /**
     * Reschedule a mock test
     */
    public function rescheduleTest(Request $request)
    {
        $this->validate($request, [
            'schedule_id' => 'required|exists:mock_test_schedules,id',
            'scheduled_date' => 'required|date|after_or_equal:today',
            'reschedule_reason' => 'required|string|max:500'
        ]);

        $schedule = MockTestSchedule::findOrFail($request->schedule_id);

        // Verify tutor has access
        $hasBatch = TeacherBatch::where('tid', Auth::user()->id)
            ->where('bid', $schedule->batch_id)
            ->exists();

        if (!$hasBatch) {
            return redirect()->back()->withFlashDanger('You do not have access to this schedule.');
        }

        // Check if any student has already attempted
        if ($schedule->results()->count() > 0) {
            return redirect()->back()->withFlashWarning('Cannot reschedule. Some students have already attempted this test.');
        }

        // Update schedule
        $schedule->scheduled_date = $request->scheduled_date;
        $schedule->rescheduled_at = now();
        $schedule->rescheduled_by = Auth::user()->id;
        $schedule->reschedule_reason = $request->reschedule_reason;
        $schedule->save();

        event(new MockTestRescheduled($schedule, $request->reschedule_reason, true));

        return redirect()->route('tutor.mocktests.available')
            ->withFlashSuccess('Mock test rescheduled successfully. Students and admin have been notified.');
    }

    /**
     * View batch results for a specific schedule
     */
    public function batchResults($scheduleId)
    {
        $schedule = MockTestSchedule::findOrFail($scheduleId);
        
        // Verify tutor has access
        $hasBatch = TeacherBatch::where('tid', Auth::user()->id)
            ->where('bid', $schedule->batch_id)
            ->exists();

        if (!$hasBatch) {
            return abort(403, 'You do not have access to these results.');
        }

        $mockTest = $schedule->mockTest;
        $batch = $schedule->batch;

        $uids = StudentTeacherBatch::where('bid', $batch->id)->pluck('uid')->toArray();
        $students = User::whereIn("id", $uids)->get();
        $users = [];
        
        foreach($students as $st){ 
            $result = $schedule->results()->where('student_id', $st->id)->first();
            
            if ($result) {
                $st->isAttempted = true;
                $st->totalQuestion = $result->total_questions;
                $st->totalCorrect = $result->total_correct;
                $st->totalIncorrect = $result->total_incorrect;
                $st->totalUnattempted = $result->total_unattempted;
                $st->score = $result->score;
                $st->percentage = $result->percentage;
                $st->result_id = $result->id;
            } else {
                $st->isAttempted = false;
                $st->totalQuestion = 0;
                $st->totalCorrect = 0;
                $st->totalIncorrect = 0;
                $st->totalUnattempted = 0;
                $st->score = 0;
                $st->percentage = 0;
            }

            $users[] = $st;
        }

        return view('backend.tutor.mocktests.batch-results', compact('mockTest', 'schedule', 'users'));
    }

    /**
     * Report a question issue
     */
    public function reportQuestion(Request $request)
    {
        $this->validate($request, [
            'question_id' => 'required|exists:questions,id',
            'report_reason' => 'required|string|max:1000'
        ]);

        MockTestQuestionReport::create([
            'question_id' => $request->question_id,
            'reported_by' => Auth::user()->id,
            'report_reason' => $request->report_reason,
            'status' => 'pending'
        ]);

        return redirect()->back()->withFlashSuccess('Question issue reported successfully. Admin will review it.');
    }

    /**
     * View list of scheduled tests for tutor
     */
    public function scheduledTests()
    {
        // Get tutor's batches
        $bids = TeacherBatch::where("tid", Auth::user()->id)->pluck('bid')->toArray();
        
        $schedules = MockTestSchedule::whereIn('batch_id', $bids)
            ->with(['mockTest', 'batch', 'results'])
            ->orderBy('scheduled_date', 'desc')
            ->get();

        return view('backend.tutor.mocktests.scheduled', compact('schedules'));
    }
