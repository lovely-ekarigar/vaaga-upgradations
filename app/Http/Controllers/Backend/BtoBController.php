<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Auth\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Contact\SendContact;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Order;
use App\Models\Coupon;

use App\Rules\Recaptcha;
use Validator;
use Illuminate\Database\QueryException;

use Illuminate\Support\Facades\DB;

class BtoBController extends Controller
{


    public function users()
    {
        $authUserId = Auth::id(); // Get authenticated user ID

        // Get the authenticated user's coupon code
        $couponCode = User::where('id', $authUserId)->value('coupon_code');

        if (!$couponCode) {
            return view('backend.btob.users', ['users' => collect()]); // Return empty collection if no coupon code
        }

        // Find the coupon ID using the coupon code
        $coupon = Coupon::where('code', $couponCode)->first();

        if (!$coupon) {
            return view('backend.btob.users', ['users' => collect()]); // Return empty collection if no matching coupon
        }

        // Get users who have placed orders using this coupon ID with optimized query
        $users = User::whereHas('orders', function ($query) use ($coupon) {
            $query->where('coupon_id', $coupon->id);
        })->get();

        // dd($users);

        return view('backend.btob.users', compact('users'));
    }


    public function index(Request $request)
    {
        $btob_lists = User::where("is_type", 'btob')->orderBy("id", "desc");
        $btob_lists = $btob_lists->paginate(10);


        $team = User::find($request->del);
        if ($team) {
            $team->delete();
            return redirect()->back()->withFlashSuccess("BtoB deleted successfully");
        }

        // dd($btob_lists);
        return view('backend.btob.index', compact('btob_lists'));
    }

    public function create()
    {
        return view('backend.btob.create');
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:450',
            'org_name' => 'required|max:500',
            'email' => 'required|max:500|email|unique:users,email', // Ensure unique email
            'code' => 'required|unique:users,coupon_code', // Ensure unique referral code
            'password' => 'required|min:8',
        ], [
            'name.required' => 'Kindly Enter Name',
            'org_name.required' => 'Kindly Enter Organization Name',
            'email.required' => 'Kindly Enter Email',
            'email.unique' => 'This email is already registered.',
            'code.required' => 'Kindly Enter Code',
            'code.unique' => 'This referral code is already in use.',
            'password.required' => 'Kindly Enter Password',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $co = new User();
            $co->first_name = $request->name;
            $co->org_name = $request->org_name;
            $co->email = $request->email;
            $co->coupon_code = $request->code;
            $co->active = '1';
            $co->password = Hash::make($request->password);
            $co->is_type = 'btob';
            $co->save();

            return redirect()->route('admin.btob.list')->with('success', 'BtoB client added successfully!');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'An error occurred. Please try again.');
        }
    }

    public function edit($id)
    {
        $team = User::find($id);
        if (!$team) {
            return abort(404);
        }
        return view('backend.btob.edit', compact('team'));
    }



    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'name' => 'required|max:450',
            'org_name' => 'required|max:500',
            'email' => 'required|max:500|email',
            'code' => 'required',

        ], [
            'name.required' => 'Kindly Enter Name',
            'org_name.required' => 'Kindly Enter Organization Name',
            'email.required' => 'Kindly Enter Email',
            'code.required' => 'Kindly Enter Code',

        ]);



        $co = User::find($request->id);
        $co->first_name = $request->name;
        $co->org_name = $request->org_name;
        $co->email = $request->email;
        $co->coupon_code = $request->code;
        $co->update();


        return redirect()->route('admin.btob.list')->withFlashSuccess("Updated successfully");
    }
}
