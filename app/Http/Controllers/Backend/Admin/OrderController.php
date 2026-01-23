<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Helpers\General\EarningHelper;
use App\Models\Bundle;
use App\Models\Course;
use App\Models\Order;
use App\Models\OrderItem;
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

                    $message = 'Dear '.$order->user->first_name.',<br><br>

Your subscription for <strong>'.$items.'</strong> is pending for the month of <strong>'.date("d M Y",strtotime("-1 Months",strtotime($order->end_date))).' - '.date("d M Y",strtotime($order->end_date)).'</strong>. Renew at the earliest for uninterrepted learning.<br><br>

Best regards,<br>
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


       return view('backend.subscriptions.gst', compact('date','orders','subscriptions')); 
    }

    public function subscriptionReports(Request $request){

           $orders = Order::where("course_mode","like","%_monthly%")->where('status','1')->orderBy('updated_at', 'desc')->whereRaw("total_cycle > paid_cycle");

        if($request->type=='7days'){
            $orders->where("end_date","<=",date("Y-m-d",strtotime("+7 Days",time())));

        }
        if($request->type=='15days'){
$orders->where("end_date","<=",date("Y-m-d",strtotime("+15 Days",time())));
        }
        if($request->type=='pending'){
$orders->where("end_date","<=",date("Y-m-d"));
        }

 return view('backend.subscriptions.reports',compact('orders'));
    }


    public function subscriptions(){
     
        return view('backend.subscriptions.index');

    }

    public function subscriptionReportsData(Request $request){
        $orders = Order::where("course_mode","like","%_monthly%")->where('status','1')->orderBy('updated_at', 'desc');

        if($request->type=='7days'){
            $orders->where("end_date","<=",date("Y-m-d",strtotime("+7 Days",time())))->whereRaw("total_cycle > paid_cycle");

        }
        if($request->type=='15days'){
$orders->where("end_date","<=",date("Y-m-d",strtotime("+15 Days",time())))->whereRaw("total_cycle > paid_cycle");
        }
        if($request->type=='pending'){
$orders->where("end_date","<=",date("Y-m-d"))->whereRaw("total_cycle > paid_cycle");
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
            ->addColumn('due_cycle', function ($q) {
                 return $q->total_cycle - $q->paid_cycle;
            })
             ->addColumn('subs_date', function ($q) {
                return $q->created_at->format('d M, Y');
            })
              ->addColumn('course_mode', function ($q) {
                return getCourseType($q->course_mode);
            })
             ->addColumn('total_amount', function ($q) {
                 return $q->total_cycle * $q->amount;
            })
              ->addColumn('paid_amount', function ($q) {
                 return $q->amount * $q->paid_cycle;
            })
            ->addColumn('name', function ($q) {
                return $q->user ? $q->user->name : '';
            })
            ->addColumn('due_date', function ($q) {
                return date("d M Y",strtotime("+0 Months",strtotime($q->end_date)));
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

                $edit = view('backend.datatable.action-edit')
                    ->with(['route' => route('admin.orders.edit', ['order' => $q->id])])
                    ->render();
                $view .= $edit;

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

                $edit = view('backend.datatable.action-edit')
                    ->with(['route' => route('admin.orders.edit', ['order' => $q->id])])
                    ->render();
                $view .= $edit;

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
     * Show the form for creating a new Order.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::orderBy('first_name')->get();
        
        $courses = Course::where('published', 1)->orderBy('title')->get();
        
        return view('backend.orders.create', compact('users', 'courses'));
    }

    /**
     * Store a newly created Order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'gst' => 'nullable|numeric|min:0',
            'course_mode' => 'nullable|string',
            'payment_type' => 'required|in:0,1,2,3,4',
            'status' => 'required|in:0,1',
            'end_date' => 'nullable|date',
            'total_cycle' => 'nullable|integer|min:0',
            'paid_cycle' => 'nullable|integer|min:0',
        ]);

        $order = new Order();
        $order->user_id = $request->user_id;
        $order->reference_no = str_random(8);
        $order->amount = $request->amount;
        $order->discount = $request->discount ?? 0;
        $order->gst = $request->gst ?? 0;
        $order->course_mode = $request->course_mode;
        $order->payment_type = $request->payment_type;
        $order->status = $request->status;
        $order->end_date = $request->end_date;
        $order->total_cycle = $request->total_cycle;
        $order->paid_cycle = $request->paid_cycle ?? 0;
        $order->save();

        // Add order items
        foreach ($request->course_ids as $courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $order->items()->create([
                    'item_id' => $courseId,
                    'item_type' => Course::class,
                    'price' => $course->price ?? $request->amount / count($request->course_ids)
                ]);
            }
        }

        // If status is completed, attach students to courses
        if ($order->status == 1) {
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type == Course::class) {
                    $orderItem->item->students()->attach($order->user_id);
                }
            }
        }

        return redirect()->route('admin.orders.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    /**
     * Show the form for editing the specified Order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $users = User::orderBy('first_name')->get();
        
        $courses = Course::where('published', 1)->orderBy('title')->get();
        
        // Get selected course IDs
        $selectedCourseIds = $order->items()->where('item_type', Course::class)->pluck('item_id')->toArray();
        
        return view('backend.orders.edit', compact('order', 'users', 'courses', 'selectedCourseIds'));
    }

    /**
     * Update the specified Order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'amount' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'gst' => 'nullable|numeric|min:0',
            'course_mode' => 'nullable|string',
            'payment_type' => 'required|in:0,1,2,3,4',
            'status' => 'required|in:0,1',
            'end_date' => 'nullable|date',
            'total_cycle' => 'nullable|integer|min:0',
            'paid_cycle' => 'nullable|integer|min:0',
        ]);

        $oldStatus = $order->status;
        
        $order->user_id = $request->user_id;
        $order->amount = $request->amount;
        $order->discount = $request->discount ?? 0;
        $order->gst = $request->gst ?? 0;
        $order->course_mode = $request->course_mode;
        $order->payment_type = $request->payment_type;
        $order->status = $request->status;
        $order->end_date = $request->end_date;
        $order->total_cycle = $request->total_cycle;
        $order->paid_cycle = $request->paid_cycle ?? 0;
        $order->save();

        // Update order items - remove old ones and add new ones
        $order->items()->delete();
        
        foreach ($request->course_ids as $courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $order->items()->create([
                    'item_id' => $courseId,
                    'item_type' => Course::class,
                    'price' => $course->price ?? $request->amount / count($request->course_ids)
                ]);
            }
        }

        // Handle student enrollment based on status
        if ($oldStatus == 0 && $order->status == 1) {
            // Status changed from pending to completed - attach students
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type == Course::class) {
                    $orderItem->item->students()->attach($order->user_id);
                }
            }
        } elseif ($oldStatus == 1 && $order->status == 0) {
            // Status changed from completed to pending - detach students
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type == Course::class) {
                    $orderItem->item->students()->detach($order->user_id);
                }
            }
        } elseif ($oldStatus == 1 && $order->status == 1) {
            // Status remains completed - ensure students are attached
            foreach ($order->items as $orderItem) {
                if ($orderItem->item_type == Course::class) {
                    $orderItem->item->students()->syncWithoutDetaching([$order->user_id]);
                }
            }
        }

        return redirect()->route('admin.orders.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
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
