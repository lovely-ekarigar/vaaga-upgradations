<?php

namespace App\Http\Controllers;

use App\Helpers\Auth\Auth;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Question;
use App\Models\QuestionsOption;
use App\Models\Test;
use App\Models\Affiliate;
use App\Models\Order;
use App\Models\TestsResult;
use App\Models\VideoProgress;
use App\Models\Config;
use App\Models\AffWithdrawl;
use Illuminate\Http\Request;
use Validator;
use Session;
use Hash;
class AffiliateController extends Controller
{
 
    private $path;

public function index(Request $request){
     if(!Session::has("aff")){
        return redirect("/affiliate/login");
     }
     $config = Config::where("key",'aff_commision')->first();
     $aff = Affiliate::find(Session::get('aff')->id);
     $ocount = Order::where('aff_code',Session::get('aff')->code)->where('status','1')->count();
     return view('aff.index',compact('aff','ocount','config'));
} 

public function earnings(){
     $aff = Affiliate::find(Session::get('aff')->id);
      $config = Config::where("key",'aff_commision')->first();
      $earnings = Order::where('aff_code',Session::get('aff')->code)->where('status','1')->orderBy("id","desc")->get();
   return view('aff.earnings',compact('earnings','aff','config'));  
}

public function withdrawl($value='')
{
     $aff = Affiliate::find(Session::get('aff')->id);
      $config = Config::where("key",'aff_commision')->first();
      $requests = AffWithdrawl::where('user_id',Session::get('aff')->id)->orderBy("id","desc")->get();
   return view('aff.withdrawl',compact('requests','aff','config'));  
}

public function bank(){
     $aff = Affiliate::find(Session::get('aff')->id);
        return view('aff.bank',compact('aff'));  
}
public function vbank(Request $request){
    $v = Validator::make($request->all(), [
      'bank_name' => 'required',
      'bank_account' => 'required',
      'bank_ifsc' => 'required',
      'bank_bef_name' => 'required',
    ]);
 if ($v->fails()) {

      $errorString = implode("\n", $v->messages()->all());
      Session::flash('flash_message', $errorString);
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();
    } else {
$aff = Affiliate::find(Session::get('aff')->id);

$aff->bank_name = $request->bank_name;
$aff->bank_account = $request->bank_account;
$aff->bank_ifsc = $request->bank_ifsc;
$aff->bank_bef_name = $request->bank_bef_name;
$aff->update();

      Session::flash('flash_message', "Bank Details Updated.");
      Session::flash('alert-class', 'alert-success');
      return redirect()->back();
    } 
}



public function vwithdrawl(Request $request){
$aff = Affiliate::find(Session::get('aff')->id);
$bal = $aff->total_earnings - $aff->total_withdrawl;
if($bal >= $request->amount){
$aw = new AffWithdrawl();
$aw->user_id  = Session::get('aff')->id;
$aw->amount = $request->amount;
$aw->status= '0';
$aw->save();
Session::flash('flash_message', "Withdrawl request created succesfully ");
      Session::flash('alert-class', 'alert-success');
      return redirect()->back();  

}else{
  Session::flash('flash_message', "Withdrawl Amount can not be greater than ".$bal);
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();  
}

}

public function edit()
{
    return view('aff.edit');
}
public function vedit(Request $request)
{
    $v = Validator::make($request->all(), [
      'name' => 'required',
    ]);
 if ($v->fails()) {

      $errorString = implode("\n", $v->messages()->all());
      Session::flash('flash_message', "Name is required");
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();
    } else {
        $aff =  Affiliate::find(Session::get('aff')->id);
        $aff->name = $request->name;
      
        $aff->update();

        $affd = Affiliate::find(Session::get('aff')->id);
Session::put('aff',$affd);
Session::flash('flash_message', "Profile updated");
      Session::flash('alert-class', 'alert-success');
      return redirect()->back();
    }
}

public function register(Request $request){
     if(Session::has("aff")){
        return redirect("/affiliate");
     }
     return view('aff.register');
} 
public function vregister(Request $request){
     
     $v = Validator::make($request->all(), [
      'name' => 'required',
      'email' => 'required|email|unique:affiliates|max:255',
      'password' => 'required|confirmed|min:6',
    ]);

    if ($v->fails()) {

      $errorString = implode("\n", $v->messages()->all());
      Session::flash('flash_message', $errorString);
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();
    } else {
         $config = Config::where("key",'aff_commision')->first();
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $code = strtoupper(substr(str_shuffle($permitted_chars), 0, 6));
        $aff = new Affiliate();
        $aff->name = $request->name;
        $aff->email = $request->email;
         $aff->commision = $config->value;
        $aff->code = $code;
        $aff->password = md5($request->password);
        $aff->save();
Session::flash('flash_message', "Registration sucessful. Kindly login now");
      Session::flash('alert-class', 'alert-success');
      return redirect("/affiliate/login");

    }
} 
public function login(Request $request){
     
     if(Session::has("aff")){
        return redirect("/affiliate");
     }

     return view('aff.login');
} 

public function vlogin(Request $request){
     
   
   $aff = Affiliate::where("email",$request->email)->where("password",md5($request->password))->first();

   if($aff){

    Session::put("aff",$aff);

      return redirect("/affiliate");

   }else{
    Session::flash('flash_message', "Invalid email or password");
      Session::flash('alert-class', 'alert-danger');
      return redirect()->back();
   }

}


public function logout(){
    Session::forget("aff");
     return redirect("/affiliate/login");
}

public function forgot(Request $request){
     
     return view('aff.forgot');
} 

    /**
     * Handle affiliate form submission from admin
     */
    public function vaffiliate(Request $request)
    {
        // TODO: Implement admin affiliate update logic
        return redirect()->back()->with('flash_message', 'Affiliate updated');
    }

    /**
     * Save affiliate settings
     */
    public function saveAff(Request $request)
    {
        // TODO: Implement save affiliate logic
        return redirect()->back()->with('flash_message', 'Affiliate settings saved');
    }
}   