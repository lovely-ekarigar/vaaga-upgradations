<?php

namespace App\Http\Controllers\Frontend;

use App\Models\MockTest;
use App\Models\MockTestSchedule;
use App\Models\MockTestResponse;
use App\Models\MockTestResult;
use App\Models\StudentTeacherBatch;
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
        // Get student's batches (if your installation uses batches)
        $batchIds = [];
        if (\Schema::hasTable('student_teacher_batches')) {
            $batchIds = StudentTeacherBatch::where('uid', Auth::user()->id)->pluck('bid')->toArray();
        }

        // Get all scheduled mock tests for student's batches
        $schedules = collect();
        if (!empty($batchIds)) {
            $schedules = MockTestSchedule::whereIn('batch_id', $batchIds)
                ->with(['mockTest', 'batch', 'results' => function($q) {
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

        // Fallback: show published mock tests for student's courses even if not scheduled to batches yet.
        $publishedTests = [];
        $publishedTestsNote = null;
        try {
            $courseIds = [];

            // 1) If you use course_user pivot
            try {
                $courseIds = array_merge($courseIds, Auth::user()->courses()->pluck('courses.id')->toArray());
            } catch (\Throwable $e) {
                // ignore
            }

            // 2) If courses are determined via Orders (this is what student dashboard uses in many setups)
            try {
                foreach (Auth::user()->purchasedCourses() as $c) {
                    if ($c && isset($c->id)) {
                        $courseIds[] = (int) $c->id;
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }

            // 3) If purchases() is used
            try {
                foreach (Auth::user()->purchases() as $c) {
                    if ($c && isset($c->id)) {
                        $courseIds[] = (int) $c->id;
                    }
                }
            } catch (\Throwable $e) {
                // ignore
            }

            $courseIds = array_values(array_unique(array_filter($courseIds)));

            $publishedTests = MockTest::where('published', 1)
                ->when(!empty($courseIds), function ($q) use ($courseIds) {
                    $q->whereIn('course_id', $courseIds);
                })
                ->orderByDesc('id')
                ->get();

            // If no match for the student's course mapping, still show published tests (visibility only; attempts still need schedule).
            if ($publishedTests->isEmpty()) {
                $publishedTestsNote = 'Showing all published tests (not yet scheduled).';
                $publishedTests = MockTest::where('published', 1)->orderByDesc('id')->get();
            }
        } catch (\Throwable $e) {
            $publishedTests = [];
        }

        return view('frontend.mocktests.dashboard', compact('upcomingTests', 'availableTests', 'completedTests', 'publishedTests', 'publishedTestsNote'));
    }

    /**
     * Attempt a mock test
     */
    public function attemptTest($scheduleId)
    {
        $schedule = MockTestSchedule::findOrFail($scheduleId);
        $mockTest = $schedule->mockTest;

        // Verify student is in the batch
        $isInBatch = StudentTeacherBatch::where('uid', Auth::user()->id)
            ->where('bid', $schedule->batch_id)
            ->exists();

        if (!$isInBatch) {
            return redirect()->route('student.mocktests.dashboard')
                ->withFlashDanger('You do not have access to this mock test.');
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
}
