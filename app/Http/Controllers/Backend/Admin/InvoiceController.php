<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\Course;
use App\Models\Order; 
use App\Models\Subscription; 
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Auth;

class InvoiceController extends Controller
{
    /**
     * Get invoice list of current user
     *
     * @param Request $request
     */
    public function getIndex(){

        $invoices = auth()->user()->invoices()->whereHas('order')->get();
        if(auth()->user()->hasRole('student')){
            $paidInvoiceRows = collect();
            $activeSubscriptionRows = collect();

            // Fetch all paid orders for the user
            $orders = Order::with(['items.item'])->where('status','1')->where('user_id',Auth::user()->id)->orderBy("id","desc")->get();
            
            // Fetch all paid subscriptions for the user
            $subscriptions = Subscription::with(['order.items.item'])
                ->where('status','1')
                ->where("user_id",Auth::user()->id)
                ->orderBy("renew_date","desc")
                ->get();

            foreach ($orders as $order) {
                // Get course names from order items
                $courseNames = $order->items
                    ->map(function ($orderItem) {
                        return optional($orderItem->item)->title;
                    })
                    ->filter()
                    ->implode(', ');

                // Add initial order as cycle 1
                $paidInvoiceRows->push((object) [
                    'date' => $order->created_at,
                    'amount' => $order->amount,
                    'cycle_no' => 1,
                    'order_id' => $order->id,
                    'subscription_id' => null,
                    'has_invoice' => true,
                    'course_names' => $courseNames,
                    'remarks' => $order->remarks,
                    'sort_at' => $order->created_at,
                ]);
            }

            // Add all paid subscriptions
            foreach ($subscriptions as $subscription) {
                $order = $subscription->order;
                if (!$order) continue;

                // Get course names from order items
                $courseNames = $order->items
                    ->map(function ($orderItem) {
                        return optional($orderItem->item)->title;
                    })
                    ->filter()
                    ->implode(', ');

                $paidInvoiceRows->push((object) [
                    'date' => $subscription->renew_date ?: $subscription->created_at,
                    'amount' => $subscription->amount,
                    'cycle_no' => $subscription->cycle_no,
                    'order_id' => $subscription->order_id,
                    'subscription_id' => $subscription->id,
                    'has_invoice' => true,
                    'course_names' => $courseNames,
                    'remarks' => $order->remarks,
                    'sort_at' => $subscription->renew_date ?: $subscription->created_at,
                ]);
            }

            // Sort paid invoices by date descending
            $paidInvoiceRows = $paidInvoiceRows->sortByDesc('sort_at')->values();

            // Build active subscriptions (upcoming cycles)
            foreach ($orders as $order) {
                $isMonthly = str_contains((string) ($order->course_mode ?? ''), 'monthly');
                if (!$isMonthly) continue;

                $totalCycle = max((int) ($order->total_cycle ?? 1), 1);
                $paidCycle = max((int) ($order->paid_cycle ?? 0), 0);

                // Get course names
                $courseNames = $order->items
                    ->map(function ($orderItem) {
                        return optional($orderItem->item)->title;
                    })
                    ->filter()
                    ->implode(', ');

                // Calculate upcoming cycles
                if ($totalCycle > $paidCycle) {
                    for ($upcoming = $paidCycle + 1; $upcoming <= $totalCycle; $upcoming++) {
                        $monthsAhead = $upcoming - $paidCycle - 1;
                        $dueDate = null;
                        if (!empty($order->end_date)) {
                            $dueDate = date("Y-m-d", strtotime("+{$monthsAhead} Months", strtotime($order->end_date)));
                        }

                        $activeSubscriptionRows->push((object) [
                            'date' => $dueDate,
                            'amount' => $order->amount,
                            'cycle_no' => $upcoming,
                            'order_id' => $order->id,
                            'course_names' => $courseNames,
                            'remarks' => $order->remarks,
                            'sort_at' => $dueDate ?: $order->created_at,
                        ]);
                    }
                }
            }

             // Sort active subscriptions by order id
            $activeSubscriptionRows = $activeSubscriptionRows->sortBy(function ($row) {
                return (int) ($row->order_id ?? 0);
            })->values();

            return view('backend.invoices.user_index',compact('orders','subscriptions','paidInvoiceRows','activeSubscriptionRows'));
        }else{
            return view('backend.invoices.index',compact('invoices'));
        }
    }

    
    public function viewInvoicestudent($id,$type){
        $order = Order::findOrFail($id);
        return showInvoice($order,$type);
    }
 

    public function viewSubsInvoicestudent($id,$type,$sid){
        $order = Order::findOrFail($id);
        $subscription = Subscription::findOrFail($sid);
        return showInvoiceSubs($order,$subscription,$type);
    }

    /**
     * Download order invoice
     *
     * @param Request $request
     */
    public function getInvoice(Request $request)
    {
        if (auth()->check()) {
            $order = Order::findOrFail($request->order);
            if (auth()->user()->isAdmin() || ($order->user_id == auth()->user()->id)) {
                $file = public_path() . "/storage/invoices/" . $order->invoice->url;
                return Response::download($file);
            }
        }
        return abort(404);
    }

}
