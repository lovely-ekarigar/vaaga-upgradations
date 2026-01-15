<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\General\EarningHelper;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Response;
use App\Models\Affiliate;
use App\Models\Config;
use App\Models\Auth\User;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Mail\Frontend\Demo\SubscriptionDueEmail;
use Illuminate\Support\Facades\Schema;
use Mail;
use Auth;
class OrderController extends Controller
{

    /**
     * Display a listing of Orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::get();

        return view('backend.orders.index', compact('orders'));
    }

    public function triggerEmail(Request $request,$id){

        $order = Order::find($id);

        $items ='';

                foreach ($order->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $crs->getCouseNameWithCat($item->item->id) . ", ";
                    }

                }

                    $message = 'Dear '.$order->user->first_name.',<br><br>';

                    if(isset($order->end_date) && !empty($order->end_date)) {
                        $message .= 'Your subscription for <strong>'.$items.'</strong> is pending for the month of <strong>'.date("d M Y",strtotime("-1 Months",strtotime($order->end_date))).' - '.date("d M Y",strtotime($order->end_date)).'</strong>. Renew at the earliest for uninterrepted learning.<br><br>';
                    } else {
                        $message .= 'Your subscription for <strong>'.$items.'</strong> is pending. Renew at the earliest for uninterrepted learning.<br><br>';
                    }

                    $message .= 'Best regards,<br>
Team VaaGa';

        $not = new Notification;
        $not->title = "Monthly subscription reminder";
        $not->message = $message;
        $not->batch_type = 'selected';
        $not->user_type = 'student';
        $not->batch_list = json_encode([$request->batch_id]);
        $not->created_by = Auth::user()->id;
        $not->save();
        $id = $not->id;
            
          

            $un = new UserNotification;
            $un->notification_id = $id;
            $un->user_id = $order->user->id;
            $un->status = '0';
            $un->save();
        


            $user = User::find($order->user->id);
           Mail::to($user)->send(new SubscriptionDueEmail($order,$items));

           return redirect()->back()->withFlashSuccess("Email and SMS has been sent successfully.");
    }

    public function subscriptionDetails(Request $request, $id){
        $order = Order::find($id);
        $subscriptions = Subscription::where('order_id',$id)->where('status','1')->orderBy("id","asc")->get();

         return view('backend.subscriptions.reports-info', compact('order','subscriptions'));
    }

    public function gstReport(Request $request){

        $order =null;
        $date="";
        $orders = null;
        $subscriptions = null;
        
        // Check if gst columns exist
        $hasOrdersGstColumn = Schema::hasColumn('orders', 'gst');
        $hasSubscriptionsGstColumn = Schema::hasColumn('subscriptions', 'gst');
        
        if($request->start){
            $date = date("m/d/Y",strtotime($request->start))." - ".date("m/d/Y",strtotime($request->end));
            $orders = Order::where('status','1')->where('created_at','>=',date("Y-m-d 00:00:00",strtotime($request->start)))->where('created_at','<=',date("Y-m-d 23:59:59",strtotime($request->end)));

             $subscriptions = Subscription::where('status','1')->where('created_at','>=',date("Y-m-d 00:00:00",strtotime($request->start)))->where('created_at','<=',date("Y-m-d 23:59:59",strtotime($request->end)));

        }else{
            $start = date("Y-m-01");
            $end = date("Y-m-d");
           $date = date("m/d/Y",strtotime($start))." - ".date("m/d/Y",strtotime($end)); 

             $orders = Order::where('status','1')->where('created_at','>=',date("Y-m-d 00:00:00",strtotime($start)))->where('created_at','<=',date("Y-m-d 23:59:59",strtotime($end)));

             $subscriptions = Subscription::where('status','1')->where('created_at','>=',date("Y-m-d 00:00:00",strtotime($start)))->where('created_at','<=',date("Y-m-d 23:59:59",strtotime($end)));
        }


       return view('backend.subscriptions.gst', compact('date','orders','subscriptions','hasOrdersGstColumn','hasSubscriptionsGstColumn')); 
    }

    public function subscriptionReports(Request $request){

           // Check if required columns exist
           $hasEndDate = Schema::hasColumn('orders', 'end_date');
           $hasTotalCycle = Schema::hasColumn('orders', 'total_cycle');
           $hasPaidCycle = Schema::hasColumn('orders', 'paid_cycle');

           $orders = Order::where("course_mode","like","%_monthly%")->where('status','1')->orderBy('updated_at', 'desc');
           
           // Only apply cycle filter if columns exist
           if($hasTotalCycle && $hasPaidCycle) {
               $orders->whereRaw("total_cycle > paid_cycle");
           }

        if($hasEndDate) {
            if($request->type=='7days'){
                $orders->where("end_date","<=",date("Y-m-d",strtotime("+7 Days",time())));
            }
            if($request->type=='15days'){
                $orders->where("end_date","<=",date("Y-m-d",strtotime("+15 Days",time())));
            }
            if($request->type=='pending'){
                $orders->where("end_date","<=",date("Y-m-d"));
            }
        }

 return view('backend.subscriptions.reports',compact('orders','hasEndDate'));
    }


    public function subscriptions(){
     
        return view('backend.subscriptions.index');

    }

    public function subscriptionReportsData(Request $request){
        // Check if required columns exist
        $hasEndDate = Schema::hasColumn('orders', 'end_date');
        $hasTotalCycle = Schema::hasColumn('orders', 'total_cycle');
        $hasPaidCycle = Schema::hasColumn('orders', 'paid_cycle');
        
        $orders = Order::where("course_mode","like","%_monthly%")->where('status','1')->orderBy('updated_at', 'desc');

        if($hasEndDate && $hasTotalCycle && $hasPaidCycle) {
            if($request->type=='7days'){
                $orders->where("end_date","<=",date("Y-m-d",strtotime("+7 Days",time())))->whereRaw("total_cycle > paid_cycle");
            }
            if($request->type=='15days'){
                $orders->where("end_date","<=",date("Y-m-d",strtotime("+15 Days",time())))->whereRaw("total_cycle > paid_cycle");
            }
            if($request->type=='pending'){
                $orders->where("end_date","<=",date("Y-m-d"))->whereRaw("total_cycle > paid_cycle");
            }
        }


        $orders = $orders->get();

         return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($request) {
                $view = "";

                $view = view('backend.datatable.action-view')
                    ->with(['route' => route('admin.subscription.detailsInfo', ['order' => $q->id])])->render();

               

                return $view;

            })
             ->addColumn('items', function ($q) {
                $items = "";
                foreach ($q->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $key . '. ' . $crs->getCouseNameWithCat($item->item->id) . "<br>";
                    }

                }
                return $items;
            })
            ->addColumn('due_amount', function ($q) {
                 return $q->amount;
            })
            ->addColumn('due_cycle', function ($q) use ($hasTotalCycle, $hasPaidCycle) {
                 if($hasTotalCycle && $hasPaidCycle && isset($q->total_cycle) && isset($q->paid_cycle)) {
                     return $q->total_cycle - $q->paid_cycle;
                 }
                 return 'N/A';
            })
             ->addColumn('subs_date', function ($q) {
                return $q->created_at->format('d M, Y');
            })
              ->addColumn('course_mode', function ($q) {
                return getCourseType($q->course_mode);
            })
             ->addColumn('total_amount', function ($q) use ($hasTotalCycle) {
                 if($hasTotalCycle && isset($q->total_cycle)) {
                     return $q->total_cycle * $q->amount;
                 }
                 return $q->amount;
            })
              ->addColumn('paid_amount', function ($q) use ($hasPaidCycle) {
                 if($hasPaidCycle && isset($q->paid_cycle)) {
                     return $q->amount * $q->paid_cycle;
                 }
                 return 0;
            })
            ->addColumn('name', function ($q) {
                return $q->user ? $q->user->name : '';
            })
            ->addColumn('due_date', function ($q) use ($hasEndDate) {
                if($hasEndDate && isset($q->end_date) && !empty($q->end_date)) {
                    return date("d M Y",strtotime("+0 Months",strtotime($q->end_date)));
                }
                return 'N/A';
            })
             ->addColumn('course_mode', function ($q) {
                return getCourseType($q->course_mode);
            })
             ->editColumn('reference_no', function ($q) {
                return 'ORD-' .$q->id;
            })
            ->addColumn('user_email', function ($q) {
                return $q->user ? $q->user->email : '';
            })
           
           
            ->rawColumns(['items', 'actions'])
            ->make();
    }

    public function getDataSubscription(Request $request){
        if (request('offline_requests') == 1) {

            $orders = Order::where('payment_type', '=', 3)->where('status','1')->where("course_mode","like","%_monthly%")->orderBy('updated_at', 'desc')->get();
        } else {
            $orders = Order::where("course_mode","like","%_monthly%")->orderBy('updated_at', 'desc')->get();
        }

        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($request) {
                $view = "";

                $view = view('backend.datatable.action-view')
                    ->with(['route' => route('admin.orders.show', ['order' => $q->id])])->render();

                if ($q->status == 0) {
                    $complete_order = view('backend.datatable.action-complete-order')
                        ->with(['route' => route('admin.orders.complete', ['order' => $q->id])])
                        ->render();
                    $view .= $complete_order;
                }

                if ($q->status == 0) {
                    $delete = view('backend.datatable.action-delete')
                    ->with(['route' => route('admin.orders.destroy', ['order' => $q->id])])
                    ->render();

                    $view .= $delete;
                }

                return $view;

            })
            ->addColumn('items', function ($q) {
                $items = "";
                foreach ($q->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $key . '. ' . $crs->getCouseNameWithCat($item->item->id) . "<br>";
                    }

                }
                return $items;
            })
            ->addColumn('name', function ($q) {
                return $q->user ? $q->user->name : '';
            })
             ->addColumn('course_mode', function ($q) {
                return getCourseType($q->course_mode);
            })
            ->addColumn('user_email', function ($q) {
                return $q->user ? $q->user->email : '';
            })
            ->addColumn('date', function ($q) {
                return $q->updated_at->format('d M, Y | h:i A');
            })
            
            ->addColumn('payment', function ($q) {
                if ($q->status == 0) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.pending');
                } elseif ($q->status == 1) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.completed');
                } else {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.failed');
                }
                return $payment_status;
            })
            ->editColumn('price', function ($q) {
                return '$' . floatval($q->price);
            })
            ->editColumn('reference_no', function ($q) {
                return 'ORD-' .$q->id;
            })
            ->rawColumns(['items', 'actions'])
            ->make();
    }

    /**
     * Display a listing of Orders via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        if (request('offline_requests') == 1) {

            $orders = Order::where('payment_type', '=', 3)->orderBy('updated_at', 'desc')->get();
        } else {
            $orders = Order::orderBy('updated_at', 'desc')->get();
        }

        return DataTables::of($orders)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($request) {
                $view = "";

                $view = view('backend.datatable.action-view')
                    ->with(['route' => route('admin.orders.show', ['order' => $q->id])])->render();

                if ($q->status == 0) {
                    $complete_order = view('backend.datatable.action-complete-order')
                        ->with(['route' => route('admin.orders.complete', ['order' => $q->id])])
                        ->render();
                    $view .= $complete_order;
                }

                if ($q->status == 0) {
                    $delete = view('backend.datatable.action-delete')
                    ->with(['route' => route('admin.orders.destroy', ['order' => $q->id])])
                    ->render();

                    $view .= $delete;
                }

                return $view;

            })
            ->addColumn('items', function ($q) {
                $items = "";
                foreach ($q->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $key . '. ' . $crs->getCouseNameWithCat($item->item->id) . "<br>";
                    }

                }
                return $items;
            })
            ->addColumn('name', function ($q) {
                return $q->user ? $q->user->name : '';
            })
            ->addColumn('user_email', function ($q) {
                return $q->user ? $q->user->email : '';
            })
             ->addColumn('course_mode', function ($q) {
                return getCourseType($q->course_mode);
            })
            ->addColumn('date', function ($q) {
                return $q->updated_at->format('d M, Y | h:i A');
            })
            ->addColumn('payment', function ($q) {
                if ($q->status == 0) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.pending');
                } elseif ($q->status == 1) {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.completed');
                } else {
                    $payment_status = trans('labels.backend.orders.fields.payment_status.failed');
                }
                return $payment_status;
            })
            ->editColumn('price', function ($q) {
                return '$' . floatval($q->price);
            })
             ->editColumn('reference_no', function ($q) {
                return 'ORD-'. $q->id;
            })
            ->rawColumns(['items', 'actions'])
            ->make();
    }

    /**
     * Complete Order manually once payment received.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function complete(Request $request)
    {
        $order = Order::findOrfail($request->order);
        $order->status = 1;
        $order->save();
 // $aff = Affiliate::where("code",$order->aff_code)->first();
 //                if($aff){
 //                    $per = Config::where("key","aff_commision")->first();
 //                    $amount  = $per->value*$order->amount/100;
 //                    $af  = Affiliate::find($aff->id);
 //                    $af->total_earnings += $amount;
 //                    $af->update();
 //                }
       //  (new EarningHelper)->insert($order);

       //  //Generating Invoice
       // generateInvoice($order);

  if(str_contains($order->course_mode,"monthly")){
    $order->end_date= date("Y-m-d",strtotime("+1 Months",time()));
    $order->update();
}

        foreach ($order->items as $orderItem) {
            //Bundle Entries
            if($orderItem->item_type == Bundle::class){
               foreach ($orderItem->item->courses as $course){
                   $course->students()->attach($order->user_id);
               }
            }
            $orderItem->item->students()->attach($order->user_id);
        }
        return back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    /**
     * Show Order from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::findOrFail($id);
        // dd($order);
        //  generateInvoice($order);
        return view('backend.orders.show', compact('order'));
    }

    public function viewInvoice($id,$type){
    $order = Order::findOrFail($id);
   showInvoice($order,$type);
    }

    /**
     * Remove Order from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $order = Order::findOrFail($id);
        $order->items()->delete();
        $order->delete();
        return redirect()->route('admin.orders.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Orders at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (!Gate::allows('course_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Order::whereIn('id', $request->input('ids'))->get();
            foreach ($entries as $entry) {
                if ($entry->status = 1) {
                    foreach ($entry->items as $item) {
                        $item->course->students()->detach($entry->user_id);
                    }
                    $entry->items()->delete();
                    $entry->delete();
                }
            }
        }
    }


}
