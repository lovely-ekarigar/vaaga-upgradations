<?php

namespace App\Http\Controllers\Backend;

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
use App\Http\Controllers\Controller;
class AffiliateController extends Controller
{
 
    private $path;

public function index(Request $request){
    
     $config = Config::where("key",'aff_commision')->first();
     $config1 = Config::where("key",'affiliate_user')->first();
     $affs = Affiliate::orderBy("id","desc")->get();
     return view('backend.aff.index',compact('affs','config','config1'));
} 


public function withdrawl(Request $request){
    
     $wrs =  AffWithdrawl::where("status","0")->orderBy("id","desc")->get();
     $requests = array();
     foreach($wrs as $w){
        $w->user = Affiliate::find($w->user_id);
        $requests[] = $w;

     }
     return view('backend.aff.withdrawl',compact('requests'));
} 


public function vwithdrawl(Request $request){

if($request->wid){
 $aw = AffWithdrawl::find($request->wid);
 $aff  = Affiliate::find($aw->user_id);

 $aff->total_withdrawl += $aw->amount;
 $aff->update();
 $aw->status = '1';
 $aw->update();

 return redirect()->back();
}


}

public function saveAff(Request $request){
    
   $aff = Affiliate::find($request->afid); 
   $aff->commision = $request->amount;
   $aff->update();
   return redirect()->back();
}
public function vaffiliate(Request $request){

if($request->percent){
 $config = Config::where("key",'aff_commision')->first();
 $config->value=$request->percent;
 $config->update();

 $config1 = Config::where("key",'affiliate_user')->first();
 $config1->value=$request->percent1;
 $config1->update();
 return redirect()->back();
}


}

}   