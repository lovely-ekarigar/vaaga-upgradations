<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Auth\User;
use App\Models\General;
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
        if (!auth()->user()->otp_verified) {
            
            $otp = rand(1000,9999);
            $user = User::find(auth()->user()->id);
           if($user->phone){
            $user->otp = $otp;
            $user->save();
           
            Session::put('user_id',$user->id);
             $g = new General();
            $g->sendOtp($user->phone,$otp);
             Auth::logout();
            return redirect("/otp");
            }
        }

        return $next($request);
    }
}
