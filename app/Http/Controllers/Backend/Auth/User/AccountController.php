<?php

namespace App\Http\Controllers\Backend\Auth\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use App\Models\User;
use App\Models\Auth\User;
use Validator;
use DB;
use Session;
use Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AccountController.
 */
class AccountController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if (auth()->user()->hasRole('student')) {
            return view('backend.account.user_index', compact('user'));
        } else {

            return view('backend.account.index', compact('user'));
        }
    }

    public function changePassword()
    {
        return view('backend.account.change_password');
    }

    public function updatePassword(Request $request)
    {
        $hashedPassword = Auth::user()->password;
        //dd($hashedPassword);
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required',
        ]);

        if ($validator->passes()) {

            if (!Hash::check($request->old_password, $hashedPassword)) {
                return back()->with('flash_message', 'Current Password Not Match');
            }
            $user = Auth::user();
            $user->password = Hash::make($request->new_password);
            $user->update();

            Session::flash('flash_message', "Password update successfully");
            Session::flash('alert-class', 'alert-info');
            return redirect()->back();
        } else {
            $errorString = implode("<br>", $validator->messages()->all());
            Session::flash('flash_message', $errorString);
            return redirect()->back();
        }
    }


    public function profileUpdate(Request $request)
    {
        // dd($request->all());

        $user = User::find(Auth::user()->id);
        $validator = Validator::make($request->all(), [
            'image' => 'mimes:jpeg,png,jpg',
        ]);

        if ($validator->passes()) {

            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->country = $request->country;
            $user->city = $request->city;
            $user->pincode = $request->pincode;
            $user->state = $request->state;
            $user->phone = $request->phone;
            $user->dob = $request->dob;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . rand(10, 99) . $image->getClientOriginalName();
                $image->move(public_path('storage/uploads/'), $filename);
                $user->avatar_type = 'storage';
                $user->avatar_location = "uploads/" . $filename;
            }
            $user->update();

            Session::flash('flash_message', "Profile update successfully");
            return redirect()->back();
        } else {
            $errorString = implode($validator->messages()->all());

            Session::flash('flash_error', $errorString);
            return redirect()->back()->withInput();
        }
    }
}
