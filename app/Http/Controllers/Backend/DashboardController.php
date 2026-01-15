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
use App\Models\Affiliate;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Schema;
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

                // User Statistics
                $totalUsers = User::count();
                $totalActiveUsers = User::where('active', 1)->count();
                $totalInActiveUsers = User::where('active', 0)->count();
                $totalPaidUsers = User::whereHas('orders', function($q) {
                    $q->where('status', 1);
                })->orWhereHas('subscriptions', function($q) {
                    $q->where('status', 1);
                })->count();

                // Subscription Statistics
                try {
                    $totalSubscriptions = Subscription::count();
                    $totalActiveSubscriptions = Subscription::where('status', 1)->count();
                    $totalPendingSubscriptions = Subscription::where('status', 0)->count();
                    $totalExpiredSubscriptions = Subscription::where(function($q) {
                        $q->where('status', 2)->orWhere(function($q2) {
                            $q2->whereNotNull('end_date')->where('end_date', '<', now());
                        });
                    })->count();
                    $newSubscriptionsThisMonth = Subscription::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)->count();
                    $expiringSubscriptionsThisMonth = Subscription::whereMonth('end_date', now()->month)
                        ->whereYear('end_date', now()->year)->where('status', 1)->count();
                    
                    // Get user's latest subscription if authenticated
                    $latest_subscription = auth()->check() ? Subscription::where('user_id', auth()->id())->latest()->first() : null;
                    $subscriptions = auth()->check() ? Subscription::where('user_id', auth()->id())->get() : collect([]);

                    // Daily subscription growth (last 30 days)
                    $dailySubscriptionGrowth = Subscription::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                        ->where('created_at', '>=', now()->subDays(30))
                        ->groupBy('date')
                        ->orderBy('date')
                        ->get();
                } catch (\Exception $e) {
                    $totalSubscriptions = 0;
                    $totalActiveSubscriptions = 0;
                    $totalPendingSubscriptions = 0;
                    $totalExpiredSubscriptions = 0;
                    $newSubscriptionsThisMonth = 0;
                    $expiringSubscriptionsThisMonth = 0;
                    $latest_subscription = null;
                    $subscriptions = collect([]);
                    $dailySubscriptionGrowth = collect([]);
                }

                // Affiliate Statistics
                try {
                    $totalAffiliates = Affiliate::count();
                    $totalActiveAffiliates = Affiliate::where(function($q) {
                        // Check if status column exists, otherwise consider all as active
                        if (Schema::hasColumn('affiliates', 'status')) {
                            $q->where('status', 'active');
                        } else {
                            $q->whereRaw('1=1'); // Return all if no status column
                        }
                    })->count();
                    $totalInActiveAffiliates = Affiliate::where(function($q) {
                        if (Schema::hasColumn('affiliates', 'status')) {
                            $q->where('status', 'inactive');
                        } else {
                            $q->whereRaw('1=0'); // Return none if no status column
                        }
                    })->count();
                    $newAffiliatesThisMonth = Affiliate::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)->count();
                    $affiliates = auth()->check() ? Affiliate::where('user_id', auth()->id())->first() : null;
                    // If no user_id relationship, try to find by email
                    if (!$affiliates && auth()->check()) {
                        $affiliates = Affiliate::where('email', auth()->user()->email)->first();
                    }
                } catch (\Exception $e) {
                    $totalAffiliates = 0;
                    $totalActiveAffiliates = 0;
                    $totalInActiveAffiliates = 0;
                    $newAffiliatesThisMonth = 0;
                    $affiliates = null;
                }

                // Course/Quiz completion statistics
                try {
                    $courseCompletions = Course::withCount('students')
                        ->select('id', 'title as course_name')
                        ->get()
                        ->map(function($course) {
                            return [
                                'course_name' => $course->course_name,
                                'completions_count' => $course->students_count
                            ];
                        });
                } catch (\Exception $e) {
                    $courseCompletions = collect([]);
                }

                try {
                    // Quiz completions - adjust based on your quiz model
                    $quizCompletions = collect([]);
                } catch (\Exception $e) {
                    $quizCompletions = collect([]);
                }
            } else {
                // Initialize variables for non-administrator roles to prevent undefined variable errors
                $totalUsers = 0;
                $totalActiveUsers = 0;
                $totalInActiveUsers = 0;
                $totalPaidUsers = 0;
                $totalSubscriptions = 0;
                $totalActiveSubscriptions = 0;
                $totalPendingSubscriptions = 0;
                $totalExpiredSubscriptions = 0;
                $newSubscriptionsThisMonth = 0;
                $expiringSubscriptionsThisMonth = 0;
                $latest_subscription = null;
                $subscriptions = collect([]);
                $dailySubscriptionGrowth = collect([]);
                $totalAffiliates = 0;
                $totalActiveAffiliates = 0;
                $totalInActiveAffiliates = 0;
                $newAffiliatesThisMonth = 0;
                $affiliates = null;
                $courseCompletions = collect([]);
                $quizCompletions = collect([]);
            }
        }

        // Initialize batch count
        try {
            $batch = Batch::count();
        } catch (\Exception $e) {
            $batch = 0;
        }

        // dd($purchased_courses->count());

        // dd($purchased_courses);
        if (\Auth::check() && auth()->user()->hasRole('student')) {

            $demo_request = DemoRequest::with('course')->where('user_id', auth()->user()->id)->whereIn("demo_status", array("na", "scheduled", "started"))->get();

            $orders = Order::where('user_id', auth()->user()->id)->where('status', '1')->where('course_mode', 'like', '%monthly%')->get();

            return view('backend.user_dashboard', compact('purchased_courses', 'students_count', 'recent_reviews', 'threads', 'purchased_bundles', 'teachers_count', 'courses_count', 'recent_orders', 'recent_contacts', 'pending_orders', 'demo_request', 'orders'));
        } else {
            // Initialize variables if not set (for non-administrator roles)
            if (!isset($totalUsers)) {
                $totalUsers = 0;
                $totalActiveUsers = 0;
                $totalInActiveUsers = 0;
                $totalPaidUsers = 0;
                $totalSubscriptions = 0;
                $totalActiveSubscriptions = 0;
                $totalPendingSubscriptions = 0;
                $totalExpiredSubscriptions = 0;
                $newSubscriptionsThisMonth = 0;
                $expiringSubscriptionsThisMonth = 0;
                $latest_subscription = null;
                $subscriptions = collect([]);
                $dailySubscriptionGrowth = collect([]);
                $totalAffiliates = 0;
                $totalActiveAffiliates = 0;
                $totalInActiveAffiliates = 0;
                $newAffiliatesThisMonth = 0;
                $affiliates = null;
                $courseCompletions = collect([]);
                $quizCompletions = collect([]);
            }
            
            return view('backend.dashboard', compact(
                'purchased_courses', 'teacher_ppt_video', 'students_count', 'recent_reviews', 'threads', 
                'purchased_bundles', 'teachers_count', 'courses_count', 'recent_orders', 'recent_contacts', 
                'pending_orders', 'course_purchased_count', 'course_subs_purchased_count', 'enquiry_count', 
                'teacher_payment_count', 'teacher_profile_list', 'balance', 'batch',
                'totalUsers', 'totalActiveUsers', 'totalInActiveUsers', 'totalPaidUsers',
                'totalSubscriptions', 'totalActiveSubscriptions', 'totalPendingSubscriptions', 
                'totalExpiredSubscriptions', 'newSubscriptionsThisMonth', 'expiringSubscriptionsThisMonth',
                'latest_subscription', 'subscriptions', 'dailySubscriptionGrowth',
                'totalAffiliates', 'totalActiveAffiliates', 'totalInActiveAffiliates', 
                'newAffiliatesThisMonth', 'affiliates',
                'courseCompletions', 'quizCompletions'
            ));
        }
    }
}
