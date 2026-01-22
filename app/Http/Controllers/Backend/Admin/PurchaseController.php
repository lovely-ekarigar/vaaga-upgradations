<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Course;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        // Get all orders with course purchases
        $query = Order::where('status', '1')
            ->whereHas('items', function($q) {
                $q->where('item_type', Course::class);
            })
            ->with(['items.item', 'user'])
            ->orderBy('created_at', 'desc');

        $purchaseList = $query->paginate(15);

        return view('admin.purchase', compact('purchaseList'));
    }
}
