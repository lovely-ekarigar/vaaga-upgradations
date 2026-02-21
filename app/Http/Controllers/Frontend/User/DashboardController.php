<?php

namespace App\Http\Controllers\Frontend\User;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Batch;
use App\Models\Course;
use App\Models\MockTestSchedule;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StudentTeacherBatch;
use App\Models\TestSeriesPurchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class DashboardController.
 * 
 * RESTORED: Enhanced with class-wise organization and Test Series data
 * Previously: Simple controller that only returned a basic view
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * 
     * RESTORED: Now properly fetches:
     * - Student's enrolled courses with progress
     * - Test Series data
     * - Mock Tests schedules
     * - Class-wise organization
     */
    public function index()
    {
        $user = Auth::user();
        
        // === RESTORED: Fetch enrolled courses with class-wise organization ===
        $courses = $this->getEnrolledCourses($user);
        
        // === RESTORED: Fetch Test Series data ===
        $testSeries = $this->getTestSeries($user);
        
        // === RESTORED: Fetch Mock Test schedules ===
        $mockSchedules = $this->getMockSchedules($user);
        
        // === RESTORED: Fetch batch/class data ===
        $studentBatches = $this->getStudentBatches($user);
        
        // === RESTORED: Calculate dashboard statistics ===
        $stats = [
            'total_courses' => count($courses),
            'active_courses' => collect($courses)->where('is_expired', false)->count(),
            'completed_lessons' => collect($courses)->sum('completed_lessons'),
            'total_test_series' => $testSeries->count(),
            'upcoming_mock_tests' => $mockSchedules->count(),
            'total_batches' => count($studentBatches),
        ];
        
        return view('frontend.user.dashboard', compact(
            'courses',
            'testSeries',
            'mockSchedules',
            'studentBatches',
            'stats'
        ));
    }
    
    /**
     * RESTORED: Get enrolled courses for the student
     * This method was missing from the upgraded code
     * 
     * @param User $user
     * @return array
     */
    private function getEnrolledCourses($user)
    {
        $courses = [];
        $courseIds = [];
        
        // Get orders for the user
        $orders = Order::where('status', '=', 1)
            ->where('user_id', '=', $user->id)
            ->get();
        
        foreach ($orders as $order) {
            $orderItems = OrderItem::where("order_id", $order->id)
                ->where('item_type', '=', "App\\Models\\Course")
                ->get();
            
            foreach ($orderItems as $item) {
                if (!in_array($item->item_id, $courseIds)) {
                    $course = Course::with('category')->find($item->item_id);
                    
                    if ($course) {
                        $courseIds[] = $item->item_id;
                        
                        // Calculate course progress
                        $totalLessons = $course->lessons()->count();
                        $completedLessons = \App\Models\ChapterStudent::where('user_id', $user->id)
                            ->where('course_id', $course->id)
                            ->count();
                        
                        $progress = $totalLessons > 0 
                            ? intval(($completedLessons / $totalLessons) * 100) 
                            : 0;
                        
                        // Check if course is expired
                        $courseObj = new Course;
                        $isExpired = $courseObj->isCourseExpired($course->id, $user->id);
                        
                        // Add computed properties
                        $course->progress = $progress;
                        $course->is_expired = $isExpired;
                        $course->total_lessons = $totalLessons;
                        $course->completed_lessons = $completedLessons;
                        
                        $courses[] = $course;
                    }
                }
            }
        }
        
        return $courses;
    }
    
    /**
     * RESTORED: Get Test Series data for the student
     * This was missing from the upgraded code
     * 
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    private function getTestSeries($user)
    {
        return TestSeriesPurchase::with(['course', 'testSeries'])
            ->where("user_id", $user->id)
            ->where("payment_status", "paid")
            ->orderBy("id", "desc")
            ->get();
    }
    
    /**
     * RESTORED: Get Mock Test schedules for the student
     * This was missing from the upgraded code
     * 
     * @param User $user
     * @return \Illuminate\Support\Collection
     */
    private function getMockSchedules($user)
    {
        $batchIds = StudentTeacherBatch::where('uid', $user->id)
            ->pluck('bid')
            ->toArray();
        
        if (empty($batchIds)) {
            return collect();
        }
        
        return MockTestSchedule::whereIn('batch_id', $batchIds)
            ->with(['mockTest', 'batch.course'])
            ->where('scheduled_date', '>=', Carbon::now()->subDay())
            ->orderBy('scheduled_date', 'asc')
            ->limit(5)
            ->get();
    }
    
    /**
     * RESTORED: Get Student's Batches/Classes
     * This was missing from the upgraded code
     * 
     * @param User $user
     * @return array
     */
    private function getStudentBatches($user)
    {
        $studentBatches = StudentTeacherBatch::where('uid', $user->id)
            ->with(['batch.course', 'teacher'])
            ->get();
        
        $batches = [];
        foreach ($studentBatches as $stb) {
            if ($stb->batch) {
                $batch = $stb->batch;
                $batch->teacher_name = $stb->teacher ? $stb->teacher->full_name : 'N/A';
                $batch->course_name = $batch->course ? $batch->course->title : 'N/A';
                $batches[] = $batch;
            }
        }
        
        return $batches;
    }
}
