<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Earning;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\TeacherPayment;
use App\Models\TeacherProfile;
use App\Models\DemoRequest;
use App\Models\Subscription;
use App\Models\StudentTeacherBatch;
use App\Models\TeacherPpt;
use App\Models\Batch;
use App\Models\MockTest;
use App\Models\MockTestSchedule;

use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;
use DB;

/**
 * Class DashboardController. 
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

    public function index()
    {

        $demo_request = NULL;
        $purchased_courses = NULL;
        $students_count = NULL;
        $recent_reviews = NULL;
        $threads = NULL;
        $teachers_count = NULL;
        $courses_count = NULL;
        $pending_orders = NULL;
        $recent_orders = NULL;
        $recent_contacts = NULL;
        $purchased_bundles = NULL;
        $course_purchased_count = NULL;
        $enquiry_count = NULL;
        $teacher_payment_count = NULL;
        $teacher_profile_list = NULL;
        $balance = 0;
        $batch = 0;
        $course_subs_purchased_count = NULL;
        $teacher_ppt_video = NULL;

        if (\Auth::check()) {
            // $student_ourses=DB::where()->get();
            $purchased_courses = auth()->user()->purchasedCourses();
            $purchased_bundles = auth()->user()->purchasedBundles();
            $pending_orders = auth()->user()->pendingOrders();
            // $demo_request = auth()->user()->demoRequest();


            // dd($demo_request);

            if (auth()->user()->hasRole('teacher')) {
                //IF logged in user is teacher
                // $students_count = Course::whereHas('teachers', function ($query) {
                //     $query->where('user_id', '=', auth()->user()->id);
                // })
                //     ->withCount('students')
                //     ->get()
                //     ->sum('students_count');

                $students_count = StudentTeacherBatch::where("tid", auth()->user()->id)->count();
                $teacher_ppt_video = TeacherPpt::where('teacher_id', auth()->user()->id)->get();

                $courses_id = auth()->user()->courses()->has('reviews')->pluck('id')->toArray();
                $teacher_profile_list = TeacherProfile::where('user_id', auth()->user()->id)->first();
                $recent_reviews = Review::where('reviewable_type', '=', 'App\Models\Course')
                    ->whereIn('reviewable_id', $courses_id)
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();

                    

                $unreadThreads = [];
                $threads = [];
                if (auth()->user()->threads) {
                    foreach (auth()->user()->threads as $item) {
                        if ($item->unreadMessagesCount > 0) {
                            $unreadThreads[] = $item;
                        } else {
                            $threads[] = $item;
                        }
                    }
                    $threads = Collection::make(array_merge($unreadThreads, $threads))->take(10);
                }
            } elseif (auth()->user()->hasRole('administrator')) {
                $students_count = User::role('student')->count();
                $teachers_count = User::role('teacher')->count();
                $course_purchased_count = Order::where("status", "1")->get();
                try {
                    $course_subs_purchased_count = Subscription::where("status", "1")->get();
                } catch (\Exception $e) {
                    $course_subs_purchased_count = collect([]);
                }
                try {
                    $enquiry_count = DemoRequest::all()->count();
                } catch (\Exception $e) {
                    $enquiry_count = 0;
                }
                try {
                    $teacher_payment_count = TeacherPayment::all();
                } catch (\Exception $e) {
                    $teacher_payment_count = collect([]);
                }
                $courses_count = \App\Models\Course::where('published', 1)->count() + \App\Models\Bundle::where('published', 1)->count();
                $recent_orders = Order::where("status", "1")->with('user')->orderBy('created_at', 'desc')->take(10)->get();
                try {
                    $recent_contacts = Contact::orderBy('created_at', 'desc')->take(10)->get();
                } catch (\Exception $e) {
                    $recent_contacts = collect([]);
                }
                $teachers = User::role('teacher')->get();
                foreach ($teachers as $th) {
                    try {
                        $er = new Earning;
                        $balance += $er->totalBalance($th->id);
                    } catch (\Exception $e) {
                        // Skip if earnings calculation fails
                    }
                }
            } else {
                // Fallback for other roles (author, moderator, etc.) - load basic admin data
                $students_count = User::role('student')->count();
                $teachers_count = User::role('teacher')->count();
                $courses_count = \App\Models\Course::where('published', 1)->count() + \App\Models\Bundle::where('published', 1)->count();
                $recent_orders = collect([]);
                $recent_contacts = collect([]);
            }
        }

        // dd($purchased_courses->count());

        // dd($purchased_courses);
        $batch= Batch::count();
        if (auth()->user()->hasRole('student')) {

            $demo_request = DemoRequest::with('course')->where('user_id', auth()->user()->id)->whereIn("demo_status", array("na", "scheduled", "started"))->get();

            $orders = Order::where('user_id', auth()->user()->id)->where('status', '1')->where('course_mode', 'like', '%monthly%')->get();

            // Mock tests to display on student dashboard (safe fallbacks, no hard dependency on scheduling)
            $availableMockTests = [];
            $upcomingMockTests = [];
            $completedMockTests = [];
            $publishedMockTests = collect();
            $publishedMockTestsNote = null;
            $publishedMockTestsScheduledIds = [];

            try {
                $batchIds = [];
                if (\Schema::hasTable('student_teacher_batches')) {
                    $batchIds = StudentTeacherBatch::where('uid', auth()->user()->id)->pluck('bid')->toArray();
                }

                // Also consider batches for purchased courses (batches.cid)
                $courseIds = [];
                try {
                    foreach (auth()->user()->purchasedCourses() as $c) {
                        if ($c && isset($c->id)) {
                            $courseIds[] = (int) $c->id;
                        }
                    }
                } catch (\Throwable $e) {}
                try {
                    foreach (auth()->user()->purchases() as $c) {
                        if ($c && isset($c->id)) {
                            $courseIds[] = (int) $c->id;
                        }
                    }
                } catch (\Throwable $e) {}
                try {
                    $courseIds = array_merge($courseIds, auth()->user()->courses()->pluck('courses.id')->toArray());
                } catch (\Throwable $e) {}
                $courseIds = array_values(array_unique(array_filter($courseIds)));

                // Include course IDs inferred from explicit batch enrollments.
                try {
                    if (!empty($batchIds)) {
                        $courseIds = array_merge($courseIds, Batch::whereIn('id', $batchIds)->pluck('cid')->toArray());
                    }
                } catch (\Throwable $e) {}
                $courseIds = array_values(array_unique(array_filter($courseIds)));

                if (!empty($courseIds)) {
                    $batchIds = array_merge($batchIds, Batch::whereIn('cid', $courseIds)->pluck('id')->toArray());
                }
                $batchIds = array_values(array_unique(array_filter($batchIds)));

                if (!empty($batchIds)) {
                    $schedules = MockTestSchedule::whereIn('batch_id', $batchIds)
                        ->when(!empty($courseIds), function ($q) use ($courseIds) {
                            $q->whereHas('mockTest.courses', function ($c) use ($courseIds) {
                                $c->whereIn('courses.id', $courseIds);
                            });
                        })
                        ->with(['mockTest.courses', 'mockTest.course', 'results' => function ($q) {
                            $q->where('student_id', auth()->user()->id);
                        }])
                        ->orderBy('scheduled_date', 'asc')
                        ->get();

                    foreach ($schedules as $schedule) {
                        $now = Carbon::now($schedule->timezone);
                        $scheduledDate = Carbon::parse($schedule->scheduled_date, $schedule->timezone);
                        $hasAttempted = $schedule->hasAttempted(auth()->user()->id);

                        if ($hasAttempted) {
                            $completedMockTests[] = $schedule;
                        } elseif ($now->isSameDay($scheduledDate)) {
                            $availableMockTests[] = $schedule;
                        } elseif ($scheduledDate->isFuture()) {
                            $upcomingMockTests[] = $schedule;
                        }
                    }
                }

                // Published tests (even if not scheduled yet)
                $scheduledMockTestIds = isset($schedules) ? $schedules->pluck('mock_test_id')->unique()->values()->all() : [];
                $publishedMockTests = MockTest::where('published', 1)
                    ->with(['courses', 'course'])
                    ->when(!empty($courseIds), function ($q) use ($courseIds) {
                        $q->whereHas('courses', function ($c) use ($courseIds) {
                            $c->whereIn('courses.id', $courseIds);
                        });
                    }, function ($q) {
                        $q->whereRaw('1=0');
                    })
                    ->when(!empty($scheduledMockTestIds), function ($q) use ($scheduledMockTestIds) {
                        $q->whereNotIn('id', $scheduledMockTestIds);
                    })
                    ->orderByDesc('id')
                    ->get();

                if ($publishedMockTests->isEmpty()) {
                    $publishedMockTestsNote = 'No published mock tests are assigned to your class yet.';
                }

                if ($publishedMockTests->isNotEmpty()) {
                    $publishedMockTestsScheduledIds = MockTestSchedule::whereIn('mock_test_id', $publishedMockTests->pluck('id')->toArray())
                        ->distinct()
                        ->pluck('mock_test_id')
                        ->toArray();
                }
            } catch (\Throwable $e) {
                // keep dashboard safe
            }

            return view('backend.user_dashboard', compact(
                'purchased_courses',
                'students_count',
                'recent_reviews',
                'threads',
                'purchased_bundles',
                'teachers_count',
                'courses_count',
                'recent_orders',
                'recent_contacts',
                'pending_orders',
                'demo_request',
                'orders',
                'availableMockTests',
                'upcomingMockTests',
                'completedMockTests',
                'publishedMockTests',
                'publishedMockTestsNote'
                ,'publishedMockTestsScheduledIds'
            ));
        } else {
            return view('backend.dashboard', compact('purchased_courses', 'teacher_ppt_video', 'students_count', 'recent_reviews', 'threads', 'purchased_bundles', 'teachers_count', 'courses_count', 'recent_orders', 'recent_contacts', 'pending_orders', 'course_purchased_count', 'course_subs_purchased_count', 'enquiry_count', 'teacher_payment_count',
             'teacher_profile_list', 'balance','batch'));
        }
    }

    /**
     * Get tutor details
     */
    public function Details()
    {
        return response()->json(['details' => []]);
    }
}
