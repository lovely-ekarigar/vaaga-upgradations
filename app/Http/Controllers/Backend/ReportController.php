<?php

namespace App\Http\Controllers\Backend;

use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order;
use App\Models\Subscription;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getSalesReport()
    {

        $courses = Course::ofTeacher()->pluck('id');
        $bundles = Bundle::ofTeacher()->pluck('id');


        $bundle_earnings = OrderItem::where('item_type','=',Bundle::class)
            ->whereIn('item_id',$bundles)
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status','=',1);


        $bundle_sales = $bundle_earnings->count();
        $bundle_earnings = $bundle_earnings->sum('orders.amount');

        $course_earnings = OrderItem::where('item_type','=',Course::class)
            ->whereIn('item_id',$courses)
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status','=',1)
            ->where("orders.course_mode","like","%full%");

        $course_earnings_subs = OrderItem::where('item_type','=',Course::class)
            ->whereIn('item_id',$courses)
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status','=',1)
            ->where("orders.course_mode","like","%monthly%");

        $course_sales = $course_earnings->count();
        $course_earnings = $course_earnings->sum('orders.amount');

        $course_sales_subs = $course_earnings_subs->count();
        $course_earnings_subs = $course_earnings_subs->sum('orders.amount');
        $sbs_earn = Subscription::where("status","1")->where("cycle_no",">",1)->sum("amount");

        $course_earnings_subs = $course_earnings_subs + $sbs_earn;

        $total_earnings = $course_earnings+$bundle_earnings;
        $total_sales = $course_sales+$bundle_sales;



        return view('backend.reports.sales',compact('total_earnings','total_sales','course_earnings_subs','course_sales_subs'));
    }

    public function getStudentsReport()
    {
        return view('backend.reports.students');
    }


public function getCourseDataSubs(Request $request){

    $courses = Course::ofTeacher()->pluck('id');

    $course_orders = OrderItem::where('item_type','=',Course::class)
        ->whereIn('item_id',$courses)
        ->join('courses', 'order_items.item_id', '=', 'courses.id')
        ->join('orders', 'orders.id', '=', 'order_items.order_id')
        ->where('orders.status','=',1)
        ->where('orders.course_mode','like','%monthly%')
        ->select('item_id', DB::raw('count(*) as orders, sum(orders.amount) as earnings, courses.title as name, courses.id as cid, courses.slug'))
        ->groupBy('item_id', 'courses.title', 'courses.id', 'courses.slug')
        ->get();

    return \DataTables::of($course_orders)
        ->addIndexColumn()
        ->editColumn('name', function ($q) {
            $cr = new Course();
            return $cr->getCouseNameWithCat($q->cid);
        })
        ->addColumn('course', function ($q) {
            $course_name = $q->name ?? '';
            $course_slug = $q->slug ?? '';
            $link = "<a href='".route('courses.show', [$course_slug])."' target='_blank'>".$course_name."</a>";
            return $link;
        })
        ->rawColumns(['course'])
        ->make();
}
    public function getCourseData(Request $request)
    {

        $courses = Course::ofTeacher()->pluck('id');

        $course_orders = OrderItem::where('item_type','=',Course::class)
            ->whereIn('item_id',$courses)
            ->join('courses', 'order_items.item_id', '=', 'courses.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status','=',1)
            ->where('orders.course_mode','like','%full%')
            ->select('item_id', DB::raw('count(*) as orders, sum(orders.amount) as earnings, courses.title as name, courses.id as cid, courses.slug'))
            ->groupBy('item_id', 'courses.title', 'courses.id', 'courses.slug')
            ->get();

        

        return \DataTables::of($course_orders)
            ->addIndexColumn()
            ->editColumn('name', function ($q) {
             $cr = new Course();
                return $cr->getCouseNameWithCat($q->cid);
            })
            ->editColumn('orders', function ($q) {
                $link = "<a href='".route('admin.reports.course_purchase_details', [$q->cid])."'>".$q->orders."</a>";
                return $link;
            })
            ->addColumn('course', function ($q) {
                $course_name = $q->name ?? '';
                $course_slug = $q->slug ?? '';
                $link = "<a href='".route('courses.show', [$course_slug])."' target='_blank'>".$course_name."</a>";
                return $link;
            })
            ->rawColumns(['course', 'orders'])
            ->make();
    }

    public function getBundleData(Request $request)
    {
        $bundles = Bundle::ofTeacher()->has('students','>',0)->withCount('students')->pluck('id');

        $bundle_orders = OrderItem::whereHas('order',function ($q){
            $q->where('status','=',1);
        })->where('item_type','=',Bundle::class)
            ->whereIn('item_id',$bundles)
            ->join('bundles', 'order_items.item_id', '=', 'bundles.id')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->select('item_id', DB::raw('count(*) as orders, sum(orders.amount) as earnings, bundles.title as name, bundles.slug'))
            ->groupBy('item_id')
            ->get();


        return \DataTables::of($bundle_orders)
            ->addIndexColumn()
            ->addColumn('bundle', function ($q) {
                $bundle_name = $q->title;
                $bundle_slug = $q->slug;
                $link = "<a href='".route('bundles.show', [$bundle_slug])."' target='_blank'>".$bundle_name."</a>";
                return $link;
            })
            ->addColumn('students',function ($q){
                return $q->students_count;
            })
            ->rawColumns(['bundle'])
            ->make();
    }

    public function getStudentsData(Request $request){
        $courses = Course::ofTeacher()->has('students','>',0)->withCount('students','teachers','batches')->get();

        return \DataTables::of($courses)
            ->addIndexColumn()
            ->editColumn('title', function ($q) {
                $cr = new Course();

                return $cr->getCouseNameWithCat($q->id);

            })
            ->addColumn('completed', function ($q) {
                $count = 0;
                if(count($q->students) > 0){
                    foreach ($q->students as $student){
                        $completed_lessons =  $student->chapters()->where('course_id', $q->id)->get()->pluck('model_id')->toArray();
                        if (count($completed_lessons) > 0) {
                            $progress = intval(count($completed_lessons) / $q->courseTimeline->count() * 100);
                            if($progress == 100){
                                $count++;
                            }
                        }
                    }
                }
                return $count;

            })
            ->make();
    }

    public function getCoursePurchaseDetails($course_id)
    {
        $course = Course::findOrFail($course_id);
        $cr = new Course();
        $course_name = $cr->getCouseNameWithCat($course_id);

        return view('backend.reports.course_purchase_details', compact('course', 'course_name'));
    }

    public function getCoursePurchaseDetailsData(Request $request, $course_id)
    {
        $purchases = Order::with(['user'])
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('order_items.item_type', '=', Course::class)
            ->where('order_items.item_id', $course_id)
            ->where('orders.status', '=', 1)
            ->where('orders.course_mode', 'like', '%full%')
            ->select('orders.*')
            ->get();

        return \DataTables::of($purchases)
            ->addIndexColumn()
            ->addColumn('student_name', function ($q) {
                return $q->user ? $q->user->name : 'N/A';
            })
            ->addColumn('reference_no', function ($q) {
                return $q->reference_no ?? $q->order_id ?? 'N/A';
            })
            ->addColumn('purchase_date', function ($q) {
                return $q->created_at->format('d M, Y');
            })
            ->addColumn('amount', function ($q) {
                return $q->amount;
            })
            ->make();
    }
}
