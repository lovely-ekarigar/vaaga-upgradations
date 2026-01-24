<?php

namespace App\Http\Controllers\Frontend;

use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestResponse;
use App\Models\MockTestResult;
use App\Models\StudentTeacherBatch;
use App\Models\Batch;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Auth;
use DB;

class StudentMockTestController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display student's mock test dashboard
     */
    public function dashboard()
    {
        // Class-based access:
        // - identify the student's classes (courses) dynamically
        // - show only mock tests assigned to those classes
        $courseIds = $this->getStudentCourseIds();
        $batchIds = $this->getStudentBatchIds($courseIds);

        // Get all scheduled mock tests for student's accessible batches
        $schedules = collect();
        if (!empty($batchIds) && !empty($courseIds)) {
            $schedules = MockTestSchedule::whereIn('batch_id', $batchIds)
                ->whereHas('mockTest.courses', function ($q) use ($courseIds) {
                    $q->whereIn('courses.id', $courseIds);
                })
                ->with(['mockTest.courses', 'mockTest.course', 'batch', 'results' => function($q) {
                    $q->where('student_id', Auth::user()->id);
                }])
                ->orderBy('scheduled_date', 'asc')
                ->get();
        }

        $upcomingTests = [];
        $availableTests = [];
        $completedTests = [];

        foreach ($schedules as $schedule) {
            $now = Carbon::now($schedule->timezone);
            $scheduledDate = Carbon::parse($schedule->scheduled_date, $schedule->timezone);
            $hasAttempted = $schedule->hasAttempted(Auth::user()->id);

            if ($hasAttempted) {
                $completedTests[] = $schedule;
            } elseif ($now->isSameDay($scheduledDate)) {
                $availableTests[] = $schedule;
            } elseif ($scheduledDate->isFuture()) {
                $upcomingTests[] = $schedule;
            }
        }

        $scheduledMockTestIds = $schedules->pluck('mock_test_id')->unique()->values()->all();

        // Published mock tests list (exclude those already scheduled to the student via batches)
        $publishedTests = [];
        $publishedTestsNote = null;
        $publishedScheduledIds = [];
        try {
            $publishedTests = MockTest::where('published', 1)
                ->when(!empty($courseIds), function ($q) use ($courseIds) {
                    $q->whereHas('courses', function ($c) use ($courseIds) {
                        $c->whereIn('courses.id', $courseIds);
                    });
                }, function ($q) {
                    // If student has no class mapping, don't show any tests.
                    $q->whereRaw('1=0');
                })
                ->when(!empty($scheduledMockTestIds), function ($q) use ($scheduledMockTestIds) {
                    $q->whereNotIn('id', $scheduledMockTestIds);
                })
                ->orderByDesc('id')
                ->with(['courses', 'course'])
                ->get();

            if ($publishedTests->isEmpty()) {
                $publishedTestsNote = 'No published mock tests are assigned to your class yet.';
            }

            if ($publishedTests->isNotEmpty()) {
                $publishedScheduledIds = MockTestSchedule::whereIn('mock_test_id', $publishedTests->pluck('id')->toArray())
                    ->distinct()
                    ->pluck('mock_test_id')
                    ->toArray();
            }
        } catch (\Throwable $e) {
            $publishedTests = [];
        }

        return view('frontend.mocktests.dashboard', compact(
            'upcomingTests',
            'availableTests',
            'completedTests',
            'publishedTests',
            'publishedTestsNote',
            'publishedScheduledIds'
        ));
    }

    /**
     * Attempt a mock test
     */
    public function attemptTest($scheduleId)
    {
        $schedule = MockTestSchedule::findOrFail($scheduleId);
        $mockTest = $schedule->mockTest;

        // Verify student can access the batch (either assigned to batch OR has purchased the batch's course)
        $isInBatch = false;
        if (\Schema::hasTable('student_teacher_batches')) {
            $isInBatch = StudentTeacherBatch::where('uid', Auth::user()->id)
                ->where('bid', $schedule->batch_id)
                ->exists();
        }

        if (!$isInBatch) {
            try {
                $courseIds = [];
                foreach (Auth::user()->purchasedCourses() as $c) {
                    if ($c && isset($c->id)) $courseIds[] = (int) $c->id;
                }
                foreach (Auth::user()->purchases() as $c) {
                    if ($c && isset($c->id)) $courseIds[] = (int) $c->id;
                }
                $courseIds = array_values(array_unique(array_filter($courseIds)));
                $batch = $schedule->batch ?: Batch::find($schedule->batch_id);
                if ($batch && isset($batch->cid) && in_array((int)$batch->cid, $courseIds, true)) {
                    $isInBatch = true;
                }
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if (!$isInBatch) {
            return redirect()->route('student.mocktests.dashboard')
                ->withFlashDanger('You do not have access to this mock test.');
        }

        // Enforce class authorization for direct access via URL (403 if not assigned)
        $courseIds = $this->getStudentCourseIds();
        if (!$this->isAuthorizedForMockTest($mockTest, $courseIds)) {
            return abort(403, 'You do not have access to this mock test.');
        }

        // Check if can attempt
        if (!$schedule->canAttempt(Auth::user()->id)) {
            $now = Carbon::now($schedule->timezone);
            $scheduledDate = Carbon::parse($schedule->scheduled_date, $schedule->timezone);

            if ($schedule->hasAttempted(Auth::user()->id)) {
                return redirect()->route('student.mocktests.dashboard')
                    ->withFlashWarning('You have already attempted this test. Retakes are not allowed.');
            }

            if (!$now->isSameDay($scheduledDate)) {
                return redirect()->route('student.mocktests.dashboard')
                    ->withFlashWarning('This test is only available on ' . $scheduledDate->format('d M Y'));
            }

            return redirect()->route('student.mocktests.dashboard')
                ->withFlashWarning('This test is no longer available.');
        }

        // Get questions in sequence
        $questions = $mockTest->questions()->with('options')->get();

        // Calculate time limit (if midnight cutoff)
        $now = Carbon::now($schedule->timezone);
        $midnight = Carbon::parse($schedule->scheduled_date, $schedule->timezone)->endOfDay();
        $timeRemaining = $now->diffInSeconds($midnight);

        return view('frontend.mocktests.attempt', compact('schedule', 'mockTest', 'questions', 'timeRemaining'));
    }

    /**
     * Submit mock test responses
     */
    public function submitTest(Request $request)
    {
        $this->validate($request, [
            'schedule_id' => 'required|exists:mock_test_schedules,id',
            'responses' => 'required|array'
        ]);

        $schedule = MockTestSchedule::findOrFail($request->schedule_id);

        // Verify student can submit
        if (!$schedule->canAttempt(Auth::user()->id)) {
            return redirect()->route('student.mocktests.dashboard')
                ->withFlashDanger('You cannot submit this test.');
        }

        DB::beginTransaction();
        try {
            $mockTest = $schedule->mockTest;
            $questions = $mockTest->questions;

            $totalQuestions = $questions->count();
            $totalCorrect = 0;
            $totalIncorrect = 0;
            $totalUnattempted = 0;

            // Save individual responses
            foreach ($questions as $question) {
                $responseOptionId = $request->responses[$question->id] ?? null;
                $isCorrect = 0;

                if ($responseOptionId) {
                    $option = QuestionsOption::find($responseOptionId);
                    if ($option && $option->correct) {
                        $isCorrect = 1;
                        $totalCorrect++;
                    } else {
                        $totalIncorrect++;
                    }
                } else {
                    $totalUnattempted++;
                }

                MockTestResponse::create([
                    'mock_test_schedule_id' => $schedule->id,
                    'student_id' => Auth::user()->id,
                    'question_id' => $question->id,
                    'response_option_id' => $responseOptionId,
                    'is_correct' => $isCorrect,
                    'time_taken' => $request->time_taken[$question->id] ?? null
                ]);
            }

            // Calculate score and percentage
            $score = $totalCorrect;
            $percentage = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0;

            // Create result
            $result = MockTestResult::create([
                'mock_test_schedule_id' => $schedule->id,
                'student_id' => Auth::user()->id,
                'total_questions' => $totalQuestions,
                'total_correct' => $totalCorrect,
                'total_incorrect' => $totalIncorrect,
                'total_unattempted' => $totalUnattempted,
                'score' => $score,
                'percentage' => $percentage,
                'completed_at' => now()
            ]);

            DB::commit();

            // Send notification
            $this->notificationService->sendMockTestResultGenerated($result);

            return redirect()->route('student.mocktests.result', ['resultId' => $result->id])
                ->withFlashSuccess('Test submitted successfully! Your result has been generated.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Mock test submission error: ' . $e->getMessage());
            
            return redirect()->route('student.mocktests.dashboard')
                ->withFlashDanger('An error occurred while submitting the test. Please try again.');
        }
    }

    /**
     * View detailed result
     */
    public function viewResult($resultId)
    {
        $result = MockTestResult::findOrFail($resultId);

        // Verify ownership
        if ($result->student_id !== Auth::user()->id) {
            return abort(403, 'You do not have access to this result.');
        }

        $schedule = $result->schedule;
        $mockTest = $schedule->mockTest;

        // Get responses with questions
        $responses = MockTestResponse::where('mock_test_schedule_id', $schedule->id)
            ->where('student_id', Auth::user()->id)
            ->with(['question.options'])
            ->get();

        return view('frontend.mocktests.result', compact('result', 'mockTest', 'schedule', 'responses'));
    }

    private function getStudentCourseIds(): array
    {
        $courseIds = [];

        try {
            foreach (Auth::user()->purchasedCourses() as $c) {
                if ($c && isset($c->id)) $courseIds[] = (int) $c->id;
            }
        } catch (\Throwable $e) {}

        try {
            foreach (Auth::user()->purchases() as $c) {
                if ($c && isset($c->id)) $courseIds[] = (int) $c->id;
            }
        } catch (\Throwable $e) {}

        try {
            $courseIds = array_merge($courseIds, Auth::user()->courses()->pluck('courses.id')->toArray());
        } catch (\Throwable $e) {}

        // Also include the course IDs inferred from the student's batch enrollments.
        try {
            if (\Schema::hasTable('student_teacher_batches')) {
                $bids = StudentTeacherBatch::where('uid', Auth::user()->id)->pluck('bid')->toArray();
                if (!empty($bids)) {
                    $courseIds = array_merge($courseIds, Batch::whereIn('id', $bids)->pluck('cid')->toArray());
                }
            }
        } catch (\Throwable $e) {}

        $courseIds = array_values(array_unique(array_filter(array_map('intval', $courseIds))));

        return $courseIds;
    }

    private function getStudentBatchIds(array $courseIds): array
    {
        $batchIds = [];

        try {
            if (\Schema::hasTable('student_teacher_batches')) {
                $batchIds = array_merge($batchIds, StudentTeacherBatch::where('uid', Auth::user()->id)->pluck('bid')->toArray());
            }
        } catch (\Throwable $e) {}

        if (!empty($courseIds)) {
            try {
                $batchIds = array_merge($batchIds, Batch::whereIn('cid', $courseIds)->pluck('id')->toArray());
            } catch (\Throwable $e) {}
        }

        $batchIds = array_values(array_unique(array_filter(array_map('intval', $batchIds))));

        return $batchIds;
    }

    private function isAuthorizedForMockTest(MockTest $mockTest, array $courseIds): bool
    {
        if (empty($courseIds)) {
            return false;
        }

        return $mockTest->courses()
            ->whereIn('courses.id', $courseIds)
            ->exists();
    }
}
