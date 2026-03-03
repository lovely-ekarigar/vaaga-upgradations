<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Auth\User;
use App\Models\General;
use App\Models\Elearn;
use Session;
use Auth;
/**
 * Class RedirectIfAuthenticated.
 */
class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Skip OTP if admin is impersonating a user (Login As feature)
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            // Admin is logged in as another user (Tutor/Student)
            // Skip OTP verification for impersonation sessions
            return $next($request);
        }

        if (!auth()->check()) {
            return redirect()->route('frontend.auth.login');
        }
        
        if (!auth()->user()->otp_verified) {
            
            $otp = random_int(100000, 999999); // 6-digit OTP for better security
            $user = User::find(auth()->user()->id);
           if($user->phone){
               
            $user->otp = $otp;
            $user->save();
           
            Session::put('user_id',$user->id);
            
            
            //shruti
            $phone = preg_replace('/[^0-9]/', '', $user->phone);
            $phone = substr($phone, -10);


            //  $g = new General();
             $e = new Elearn();
            // $g->sendOtp($user->phone,$otp);
            $e->sendROTP($otp, $user->phone);
             Auth::logout();
            return redirect("/otp");
            }
        }

        return $next($request);
    }
}
