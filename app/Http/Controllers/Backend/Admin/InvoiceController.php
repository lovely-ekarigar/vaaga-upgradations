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
            $orders = Order::where('status','1')->where('user_id',Auth::user()->id)->orderBy("id","desc")->get();
            $subscriptions = Subscription::where('status','1')->where("user_id",Auth::user()->id)->orderBy("id","desc")->get();
 return view('backend.invoices.user_index',compact('orders','subscriptions'));
        }else{
        return view('backend.invoices.index',compact('invoices'));
    }
    }

    
public function viewInvoicestudent($id,$type){
    $order = Order::findOrFail($id);
   showInvoice($order,$type);
    }
 

 public function viewSubsInvoicestudent($id,$type,$sid){
    $order = Order::findOrFail($id);
    $subscription = Subscription::findOrFail($sid);
   showInvoiceSubs($order,$subscription,$type);
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
