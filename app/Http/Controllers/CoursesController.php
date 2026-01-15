<?php

namespace App\Http\Controllers;

use App\Helpers\General\EarningHelper;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Board;
use App\Models\Course;
use App\Models\Coupon;
use App\Models\Media;
use App\Models\Review;
use App\Models\Recording;
use App\Models\Elearn;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\Auth\User;
use App\Models\CourseContent;
use App\Models\DemoRequest;
use App\Models\Lesson;
use App\Models\Affiliate; 
use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use App\Mail\OfflineOrderMail;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Customer;
use Cart;
use Auth;
use Hash;
use Cookie;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Validator;
use Illuminate\Support\Facades\Password;
use App\Mail\Frontend\Contact\DemoRequestEmail;
use App\Mail\Frontend\Demo\PaymentReceivedEmail;
use App\Mail\Frontend\Demo\AdminTutorEmail;
use App\Rules\Recaptcha;
use Mail;
use Stevebauman\Location\Facades\Location;


class CoursesController extends Controller
{

    private $path;

    public function __construct()
    {
        $path = 'frontend';
        if(session()->has('display_type')){
            if(session('display_type') == 'rtl'){
                $path = 'frontend-rtl';
            }else{
                $path = 'frontend';
            }
        }else if(config('app.display_type') == 'rtl'){
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }
    
    public function siteVerify($res){
        $post = [
    'secret' => env('GOOGLE_RECPTCHA_SERVER_KEY'),
    'response' => $res,
];

$ch = curl_init('https://www.google.com/recaptcha/api/siteverify');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

// execute!
$response = curl_exec($ch);

// close the connection, release resources used
curl_close($ch);
$data= json_decode($response,true);
// do anything you want with your response
return $data['success'];

    }
    
     public function demoRequestHome(Request $request){
        // dd($request->all());
        
        // $this->validate($request,[
        //     'name'=>'required',
        //     'phone'=>'required',
        //     'email'=>'required|email',
        //     'g-recaptcha-response'=>'required'
        //     ],[
        //         'name.required'=>'Name is required'
        //         ]); 
                
                $validator = Validator::make($request->all(), [
            'name'=>'required',
            'course_id'=>'required',
            'phone'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'email'=>'required|email',
            'g-recaptcha-response' => ['required', new Recaptcha()],
            // 'g-recaptcha-response'=>'required'
            ],[
                'name.required'=>'Name is required',
                'course_id.required'=>'Kindly select the valid course',
                'phone.required'=>'Phone number is required',
                'email.required'=>'Email is required',
                'g-recaptcha-response'=>'Recaptcha required',
                'email.email'=>'Kindly enter valid email',
                'phone.regex'=>'Kindly enter valid phone number',
                 'phone.min'=>'Kindly enter valid phone number',
                  'phone.max'=>'Kindly enter valid phone number',
                  'g-recaptcha-response.required' =>'Verify that you are not a robot.'
                ]);

    if ($validator->fails()) {
        return redirect(URL::previous() . "#demo")
                    ->withErrors($validator)
                    ->withInput();
    }  
    
      
    // $gverify = $this->siteVerify($request->get('g-recaptcha-response'));
    
    // if(!$gverify){
    //      return redirect(URL::previous() . "#demo")->with("error","Unable to verify Google recaptcha");
    // }
    
    
    $email = $request->email;
    $user = User::where('email',$email)->withTrashed()->first();
    // dd($email);
    $password = time();
    if($user){
         $user->active = '1';
         // $user->password= Hash::make($password);
          $user->deleted_at = null;
         $user->update();
        
        $dr = new DemoRequest();
        $dr->user_id = $user->id;
        $dr->course_id = $request->course_id;
          $dr->name = $request->name;
        $dr->email = $request->email;
        $dr->phone = $request->phone;
        $dr->save();
       // $request->password = $password;
       try {
         Mail::to($request->email)->send(new DemoRequestEmail($request));
          Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New Demo request"));
        }
        catch (\Exception $e) {}


    }else{
        $user = new User();
        $user->first_name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->active = '1';
        $user->password= Hash::make($password);
        $user->save(); 
         $user->assignRole('student'); 
        
        $dr = new DemoRequest();
         $dr->name = $request->name;
        $dr->email = $request->email;
        $dr->phone = $request->phone;
        $dr->user_id = $user->id;
        $dr->course_id = $request->course_id;
        $dr->save();
        $request->password = $password;
        
        
        try {
       Mail::to($request->email)->send(new DemoRequestEmail($request));
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New Demo request"));
        }
        catch (\Exception $e) {}
    }

    $el = new Elearn;
    $el->demoStudentSMS($request->name,$request->phone);
    
    
       return redirect()->back()->with("success","Your demo request has been registered. Our team will get in touch with you shortly.");
    
    }
    
    public function demoCourse(Request $request){
        $ret ='';
        if($request->cat_id){
            $cat_id = $request->cat_id;
            $cat = Category::find($cat_id);
            if($cat->is_board=='1'){
                $boards = Board::get();
                $ret ='<select class="form-control mb-3 form-select l2" id="select_board"><option value="">Select Board</option>';
                foreach($boards as $b){
                    $ret .= '<option value="'.$b->id.'">'.$b->name.'</option>';
                }
                $ret .= "</select>";
                
            }else{
                
                
                $cats = Category::where("parent",$cat_id)->orderBy("sort_order","asc")->get();
                
                    if(count($cats)>0){
                $ret ='<select class="form-control mb-3 form-select courseCategory l3" id="select_cat"><option value="">Select Sub Category</option>';
                foreach($cats as $b){
                    $ret .= '<option value="'.$b->id.'">'.$b->name.'</option>';
                }
                $ret .= "</select>";
                    }
                
                  if(count($cats)==0){
                $courses = Course::where("category_id",$request->cat_id)->orderBy("sort_order","asc")->get();
                
                 $ret ='<select class="form-control mb-3 form-select l4" id="select_course"><option value="">Select Course</option>';
                foreach($courses as $b){
                    $ret .= '<option value="'.$b->id.'">'.$b->title.'</option>';
                }
                $ret .= "</select>";
                  }
            }
            
            
        }  
        
        
        if($request->board_id){
            
            // $courses = Course::where("category_id",$request->bcat_id)->where("board_id",$request->board_id)->get();
           
                
               $cats = Category::where("board_id",$request->board_id)->where('parent',$request->category_id)->orderBy("sort_order","asc")->get();
                
                $ret ='<select class="form-control mb-3 form-select courseCategory l5" id="select_cat"><option value="">Select Class</option>';
                foreach($cats as $b){
                    $ret .= '<option value="'.$b->id.'">'.$b->name.'</option>';
                }
                $ret .= "</select>";
                    
                
                 
                 
                 
                
            
        }
        return $ret;
    }
    
    public function demoRequest(Request $request){
       
        // dd($request->all());
        // $this->validate($request,[
        //     'name'=>'required',
        //     'phone'=>'required',
        //     'email'=>'required|email',
        //     'g-recaptcha-response'=>'required'
        //     ],[
        //         'name.required'=>'Name is required'
        //         ]); 
                
                $ip = request()->ip();
$location = Location::get($ip);

if($location->countryName!='India'){
    
    
    return redirect()->back();
}
                
                
                $validator = Validator::make($request->all(), [
            'name'=>'required',
            'phone'=>'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'email'=>'required|email',
            'g-recaptcha-response' => ['required', new Recaptcha()],
            ],[
                'name.required'=>'Name is required',
                'phone.required'=>'Phone number is required',
                'email.required'=>'Email is required',
                'g-recaptcha-response'=>'Recaptcha required',
                'email.email'=>'Kindly enter valid email',
                'phone.regex'=>'Kindly enter valid phone number',
                 'phone.min'=>'Kindly enter valid phone number',
                  'phone.max'=>'Kindly enter valid phone number',
                   'g-recaptcha-response.required' =>'Verify that you are not a robot.'
                ]);

    if ($validator->fails()) {
        return redirect(URL::previous() . "#demo")
                    ->withErrors($validator)
                    ->withInput();
    }  
    
      
    $gverify = $this->siteVerify($request->get('g-recaptcha-response'));
    
    // if(!$gverify){
    //      return redirect(URL::previous() . "#demo")->with("error","Unable to verify Google recaptcha");
    // }
    
    $email = $request->email;
    $user = User::where('email',$email)->withTrashed()->first();
    $password = time();
    if($user){
         $user->active = '1';
          // $user->password= Hash::make($password);
          $user->deleted_at = null;
         $user->update();
        $dr = new DemoRequest();
        $dr->user_id = $user->id;
        $dr->course_id = $request->course_id;
          $dr->name = $request->name;
        $dr->email = $request->email;
        $dr->phone = $request->phone;
        $dr->ip = $ip;

        $dr->save();
         // $request->password = $password;
         try {
         Mail::to($request->email)->send(new DemoRequestEmail($request));
           Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New Demo request"));

        } catch (\Exception $e) {}

    }else{
        $user = new User();
        $user->first_name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
         $user->active = '1';
        $user->password= Hash::make($password);
        $user->save(); 
         $user->assignRole('student'); 
        
        $dr = new DemoRequest();
         $dr->name = $request->name;
        $dr->email = $request->email;
        $dr->phone = $request->phone;
        $dr->user_id = $user->id;
        $dr->course_id = $request->course_id;
        $dr->save();
        $request->password = $password;
        
        // Auth::login($user);
    //      $status = Password::sendResetLink(
    //     $request->only('email')
    // );
    // if($status === Password::RESET_LINK_SENT){
        
    // }
    try {
       Mail::to($request->email)->send(new DemoRequestEmail($request));
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New Demo request"));
    } catch (\Exception $e) {}
    }
      $el = new Elearn;
    $el->demoStudentSMS($request->name,$request->phone);
    
       return redirect()->back()->with("success","Your demo request has been registered. Our team will get in touch with you shortly.");
    
    }

 public function recordings(){
  
  
   $recordings=Recording::where("view_url",null)->get();

    foreach($recordings as $rd){
    $found=0;    
        $el=new Elearn;
        $in=array(
            "meetingID"=>$rd->internal_id,
            "recordID"=>$rd->internal_id
        );
        $x=$el->eClass("getRecordings",$in);
if(array_key_exists("recordings",$x)){  
       foreach($x["recordings"] as $rec){
        $found++;
    $rx=(array)$rec;
//print_r();
    $pb=(array)$rx["playback"];
    $format=(array)$pb["format"];
   // $prv=(array)$format["preview"];
   // $images=(array)$prv["images"];
$sr=Recording::find($rd->id);
$sr->name=$rx["name"];
$sr->view_url=$format["url"];
$sr->start_time=$rx["startTime"];
$sr->end_time=$rx["endTime"];
$sr->update();
//print_r($images["image"]);
   // $list[]=array("name"=>$rx["name"],"url"=>$format["url"],"images"=>$images["image"],"start"=>$rx["startTime"]/1000,"end"=>$rx["endTime"]/1000);
}
}
/*
if($found==0){
$sr=Recording::find($rd->id);
$sr->name="";
$sr->view_url="nf";
$sr->start_time="";
$sr->end_time="";
$sr->update();

}*/
    }
 

    } 
    public function completeOrder(Request $request){
         $counter = 0;
        $items = [];
$order = Order::where('id', '=', (int)$request->oid)->where('status', '=', 0)->first();
        if ($order) {
            $order->payment_type = 0;
            $order->status = 1;
            $order->remarks = 'Paid';
            $order->transaction_id = $request->pid;
            $order->save();
            if($order->status == 1){
                $aff = Affiliate::where("code",$order->aff_code)->first();
                if($aff){
                    // $per = Config::where("key","aff_commision")->first();
                    // $amount  = $per->value*$order->amount/100;
                    $af  = Affiliate::find($aff->id);
                  $amount  = $af->commision*$order->amount/100; 
                    $af->total_earnings += $amount;
                    $af->update();
                }
                (new EarningHelper())->insert($order);
            }
            
                foreach ($order->items as $key => $cartItem) {
                    $counter++;
                    array_push($items, ['number' => $counter, 'name' => $cartItem->item->name, 'price' => $cartItem->item->price]);
                }

                $content['items'] = $items;
                $content['total'] = $order->amount;
                $content['reference_no'] = $order->reference_no;

                try {
                    \Mail::to(Auth::user()->email)->send(new OfflineOrderMail($content));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order ' . $order->id);
                }

           
                foreach ($order->items as $orderItem) {
                    //Bundle Entries
                    if ($orderItem->item_type == Bundle::class) {
                        foreach ($orderItem->item->courses as $course) {
                            $course->students()->attach($order->user_id);
                        }
                    }
                    $orderItem->item->students()->attach($order->user_id);
                }

                //Generating Invoice
                generateInvoice($order);
               
            
    }
     return $order;
}

public function thankYou(){


    return view('frontend.thank');
}


    public function order(Request $request)
    {

        // dd($request->all());

        $ids=$request->course;
        $ids[] = $request->course_id;
        $cccode = $request->coupon;

        $courses = Course::whereIn('id',$ids);
         // $couponInfo =  $this->applyCoupon($total,$cccode);
        // dd($couponInfo);

        $course = Course::find($request->course_id);
        //$cccode = $request->coupon;

        $total = 0;
 
         if($request->course_mode=='onetoone_full'){
$total=$courses->sum('price_1');
         }else if($request->course_mode=='onetoone_monthly'){
$total=$courses->sum('monthly_price_1');
         }else if($request->course_mode=='onetomany_full'){
            $total=$courses->sum('price');
        }else if($request->course_mode=='full'){
            $total=$courses->sum('full_price');
        }else if($request->course_mode=='quarterly'){
            $total=$courses->sum('quarterly_price');
        }else{
           $total=$courses->sum('monthly_price'); 
         }


           $couponInfo =  $this->applyCoupon($total,$cccode);
        //    dd($couponInfo);

      
          $coupon=0;
          $amount = $total;
          $discount=0;
           $gst = round($request->gst);
          if($couponInfo){
              if($couponInfo["status"]=="success"){
                  $amount = $couponInfo["grant_total"];
                  $coupon = $couponInfo["coupon_id"];
                  $discount= $couponInfo["discount"];
              }
          }

          $tamount=ceil($amount);
        //  dd($request->all());
           $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->reference_no = str_random(8);
        $order->amount = ceil($amount); 
        $order->discount = $discount;
        $order->status = 0;
        $order->coupon_id = $coupon;
        $order->course_mode = $request->course_mode;
        $order->gst = $gst;
        $order->payment_method = $request->payment_method;
        $order->payment_type = 1;
        $order->aff_code = Cookie::get('affiliate_code');
        $order->save();
        //Getting and Adding items
        
        $duration = 0;
         
                $type = Course::class;

            foreach($ids as $cid){
                $course = Course::find($cid);
                $duration = $course->duration;
$price=0;
                  if($request->course_mode=='onetoone_full'){
$price=$course->price_1;
         }else if($request->course_mode=='onetoone_monthly'){
$price=$course->monthly_price_1;
         }else if($request->course_mode=='onetomany_full'){
$price=$course->price;
}else if($request->course_mode=='full'){
    $total=$courses->sum('full_price');
}else if($request->course_mode=='quarterly'){
    $total=$courses->sum('quarterly_price');
}else{
   $total=$courses->sum('monthly_price'); 
 }

            $order->items()->create([
                'item_id' => $cid,
                'item_type' => $type,
                'price' => ceil($price),
            ]);
        }
        $orx = Order::find($order->id);
        $orx->total_cycle = $duration;
        $orx->paid_cycle = 0;
        $orx->update();


          if($tamount<1){
       $orderx = Order::where('id', '=', $order->id)->first();
       $orderx->status = 1;
            $orderx->remarks = 'Free';
            $orderx->save();
            
             foreach ($orderx->items as $orderItem) {
                    //Bundle Entries
                    // if ($orderItem->item_type == Bundle::class) {
                    //     foreach ($orderItem->item->courses as $course) {
                    //         $course->students()->attach($orderx->user_id);
                    //     }
                    // }
               
                    $orderItem->item->students()->attach($orderx->user_id);
                }
                $counter=0;
          $items=[];     
 foreach($ids as $cid){
                $course = Course::find($cid);
                    array_push($items, ['number' => 1, 'name' => $course->title, 'price' => ceil($course->price)]);

                }
             

                $content['items'] = $items;
                $content['total'] = 0;
                $content['reference_no'] = $orderx->reference_no;

                try {
                    \Mail::to(Auth::user()->email)->send(new OfflineOrderMail($content));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order ' . $orderx->id);
                }
                //Generating Invoice
                generateInvoice($orderx);
                
                 return redirect("/thank-you")->with('success', 'Course Purchase');
                 // return array("id"=>$orderx->id,"amount"=>$orderx->amount,"status"=>$orderx->status); 
           }else{

            return redirect('/pay/'.$order->reference_no."?payment_for=ORDER");


         // return array("id"=>$order->id,"amount"=>$order->amount,"status"=>$order->status);  
           }

        // return redirect()->back()->withInput();

        
    }


    public function all(Request $request)
    {
      $coursesd = Course::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc');
      if($request->key){
          $coursesd->where("title","like","%".$request->key."%");
      }
      
      $courses = $coursesd->paginate(50);
      
        $purchased_courses = NULL;
        $purchased_bundles = NULL;
        $categories = Category::where('parent','=',0)->get();

        // if (\Auth::check()) {
        //     $purchased_courses = Course::withoutGlobalScope('filter')->whereHas('students', function ($query) {
        //         $query->where('id', \Auth::id());
        //     })
        //         ->with('lessons')
        //         ->orderBy('id', 'desc')
        //         ->get();
        // }
        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->where('featured', '=', 1)->take(8)->get();

        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $view = 'index';
        if($request->view){
            if($request->view=='grid'){
                $view = 'grid-course';
            }
        }

        return view( $this->path.'.courses.'.$view, compact('courses', 'purchased_courses', 'recent_news','featured_courses','categories','view')); 
    }

   
    
    public function search(Request $request){
      $cats=Category::where("name","like","%".$request->key."%")->where("status","1")->get();
      $courses=Course::where("title","like","%".$request->key."%")->where("published","1")->get();
       $ret=array();
       foreach($cats as $c){
           $ret[]=array("name"=>$c->name,"href"=>"/category/".$c->slug."/courses");
       }
       foreach($courses as $c){
           $ret[]=array("name"=>$c->title,"href"=>"/course/".$c->slug);
       }
       
       return array("success"=>true,"data"=>$ret);
    }




    public function download($cid,$mid){
$crs = Course::withoutGlobalScope('filter')->where('id', $cid)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $crs->students()->where('user_id', \Auth::id())->count() > 0;
        if($purchased_course){
            $m=Media::find($mid);

$urlFile = $m->url; // of course find the exact filename....        
$file_name  =   basename($urlFile);
    //save the file by using base name
    $fn         =   file_put_contents($file_name,file_get_contents($urlFile));
    header("Expires: 0");
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Content-type: application/file");
    header('Content-length: '.filesize($file_name));
    header('Content-disposition: attachment; filename="'.basename($file_name).'"');
    readfile($file_name);
        }else{
            echo "<script>Kindly buy this course to download files.</acript>";
        }

    }

    public function study($course_slug,$les_slug=""){

        $crs = Course::withoutGlobalScope('filter')->where('slug', $course_slug)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $crs->students()->where('user_id', \Auth::id())->count() > 0;
        $course=Course::where("slug",$course_slug)->first();

if($les_slug==""){
$les=Lesson::where("course_id",$course->id)->first();
}else{
   $les=Lesson::where("slug",$les_slug)->where("course_id",$course->id)->first();  
}

       
$contents=CourseContent::where("course_id",$course->id)->get();
$current=(object)array("video"=>null,"full_text"=>null,"pdf"=>null,"media"=>null);
        $clist=array();
        foreach($contents as $ct){
            $clessons=Lesson::where("content_id",$ct->id)->get();
            $rl=array();
                foreach($clessons as $cl){

                    $media=Media::where("model_id",$cl->id)->where("model_type","App\Models\Lesson")->where("type","!=","youtube")->get();
                    $yout=Media::where("model_id",$cl->id)->where("model_type","App\Models\Lesson")->where("type","youtube")->first();
                    $pdf=Media::where("model_id",$cl->id)->where("model_type","App\Models\Lesson")->where("type","application/pdf")->first();
                    $cl->media=$media;
                    $cl->video=$yout;
                    $cl->pdf=$pdf;
                    if($les->id==$cl->id){
                        $current=$cl;
                    }
                    $rl[]=$cl;

                } 
                $ct->lessons=$rl;
            $clist[]=$ct;
        }


  return view('study', compact('course','current','clist','purchased_course'));
    }
    
    public function applyCoupon($total,$coupon){
        
        
        $gst = $total - $total*100/118;
      $total = $total - $gst;
    $discount =0;
        $coupon = Coupon::where('code', '=', $coupon)
            ->where('status', '=', 1)
            ->first();

            // dd($coupon);

            if($coupon){

       
        
          $isCouponValid = false;
            if( $coupon->useByUser() < $coupon->per_user_limit ){
                $isCouponValid = true;
                if(($coupon->min_price != null) && ($coupon->min_price > 0)){
                    if($total >= $coupon->min_price){
                        $isCouponValid = true;
                    }
                }else{
                    $isCouponValid = true;
                }
                if($coupon->expires_at != null){
                    if(Carbon::parse($coupon->expires_at) >= Carbon::now()){
                        $isCouponValid = true;
                    }else{
                        $isCouponValid = false;
                    }
                }

            }


            if($isCouponValid == true){
                $type = null;
                if($coupon->type == 1){
                    $type = '-'.$coupon->amount.'%';
                    $discount = $total*$coupon->amount/100;
                }else{
                    $type = '-'.$coupon->amount;
                    if($total > $coupon->amount){
                    $discount = $coupon->amount;
                }else{
                    $discount = $total;
                }

                }

                
                return ['status' => 'success', 'message' => 'Coupon applied succesfully','discount'=>round($discount),'grant_total'=>round($total-$discount+$gst),'subtotal'=>round($total),"gst"=>round($gst),"coupon_id"=>$coupon->id];
            }else{
 Session::forget("ccode");
                               Session::put("ccode_for");
                return ['status'=>'failure','message'=>'Invalid or Expired coupon','discount'=>round($discount),'grant_total'=>round($total-$discount+$gst),'subtotal'=>round($total),"gst"=>round($gst)];
            }     
        }else{
 Session::forget("ccode");
                               Session::put("ccode_for");
           return ['status'=>'failure','message'=>'Invalid or Expired coupon','discount'=>round($discount),'grant_total'=>round($total-$discount+$gst),'subtotal'=>round($total),"gst"=>round($gst)]; 
        }
        
    //     //  $amount = $course->price;
    //     // $course_id = $course->id;
    //     $discount=0;  
    //   $gst = $amount - $amount*100/118;
    //   $amount = $amount - $gst;
       
    //   $coupon = Coupon::where('code', '=',  $cccode)
    //                     ->where('status', '=', 1)
    //                     ->first();
                        
    //          if ($coupon != null) {

    //                           if ($coupon->per_user_limit > $coupon->useByUser()) {
    //                     $isCouponValid = true;
    //                     if (($coupon->min_price != null) && ($coupon->min_price > 0)) {
    //                         if ($amount >= $coupon->min_price) {
    //                             $isCouponValid = true;
    //                         }
    //                     } else {
    //                         $isCouponValid = true;
    //                     }
    //                 }

    //                 if ($coupon->expires_at != null) {
    //                     if (Carbon::parse($coupon->expires_at) >= Carbon::now()) {
    //                         $isCouponValid = true;
    //                     } else {
    //                         $isCouponValid = false;
    //                     }
    //                 }
    //                 if($isCouponValid){

    //                     if ($coupon->type == 1) {
    //                         $discount = $amount * $coupon->amount / 100;

    //                     } else {

    //                         $discount = $coupon->amount; 
    //                         if($amount<$discount){
    //                             $discount = $amount;
    //                         }

    //                     }
    //                     $pay = $amount  - $discount;

    //                     if($discount >= (float)$amount){
    //                         $pay = 0;
    //                     }
    //                   $couponInfo= ['status' => 'success', 'message' => 'Coupon Code applied.', "data"=>array("discount"=>$discount,"payable"=>$pay,"code"=>$cccode,"coupon_id"=>$coupon->id)]; 

    //                 }else{
    //                             Session::forget("ccode");
    //                           Session::put("ccode_for");
    //                 $couponInfo= ['status' => 'failure', 'message' => 'Invalid Coupon Code',"code"=>$cccode];

    //                 }

             
    //          }else{
    //               Session::forget("ccode");
    //                           Session::put("ccode_for");
    //             $couponInfo= ['status' => 'failure', 'message' => 'Invalid Coupon Code',"code"=>$cccode];
    //          }  
             
    //          return $couponInfo;
    }

    public function show(Request $request,$course_slug)
    {
        //dd($request->all());
          $course = Course::with('lessons')->withoutGlobalScope('filter')->where('slug', $course_slug)->with('publishedLessons')->firstOrFail();
           $couponInfo=[];
            $cccode = $request->cc ? $request->cc : Session::get('ccode');
            
            if(!$request->cc){
                if(Session::get('ccode_for')!=$course->id){
                 $cccode = null;   
                }
            }
            
          if($cccode){
         
            $couponInfo =  $this->applyCoupon($course,$cccode);
          }
        
        
        $continue_course=NULL;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
     
        $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        if(($course->published == 0) && ($purchased_course == false)){
            abort(404);
        }
        $course_rating = 0;
        $total_ratings = 0;
        $completed_lessons = "";
        $is_reviewed = false;
        if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
            $is_reviewed = true;
        }
        if ($course->reviews->count() > 0) {
            $course_rating = $course->reviews->avg('rating');
            $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
        }
        $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

        $contents=CourseContent::where("course_id",$course->id)->orderBy("sort_order","asc")->get();
        $clist=array();
        foreach($contents as $ct){
            $clessons=Lesson::where("content_id",$ct->id)->orderBy("position","asc")->get();
            $rl=array();
                foreach($clessons as $cl){

                    $media=Media::where("model_id",$cl->id)->where("model_type","App\Models\Lesson")->get();
                    $yout=Media::where("model_id",$cl->id)->where("model_type","App\Models\Lesson")->where("type","youtube")->first();
                    $cl->media=$media;
                    $cl->video=$yout;
                    $rl[]=$cl;

                }
                $ct->lessons=$rl;
            $clist[]=$ct;
        }
        if (\Auth::check()) {

            $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
            $course_lessons = $course->lessons->pluck('id')->toArray();
            $continue_course  = $course->courseTimeline()
                ->whereIn('model_id',$course_lessons)
                ->orderby('sequence','asc')
                ->whereNotIn('model_id',$completed_lessons)

                ->first();
            if($continue_course == null){
                $continue_course = $course->courseTimeline()
                    ->whereIn('model_id',$course_lessons)
                    ->orderby('sequence','asc')->first();
            }

        }
    $pcategory=null;  
$category = Category::where('id', '=', $course->category->id)
            ->where('status','=',1)
            ->first();
            if($category){
$pcategory = Category::where('id', '=', $category->parent)
            ->where('status','=',1)
            ->first();
            }
            $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->orderByRaw('RAND()')->paginate(4);
//dd($courses); 
 
$acode = Cookie::get("affiliate_code");
$aff = Affiliate::where("code",$acode)->first();

$conf = Config::where("key","affiliate_user")->first();
// dd($purchased_course);

        return view('course', compact('course','couponInfo','cccode', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course','courses','clist','acode','aff','conf','category','pcategory'));
    }

 
    public function checkoutCoupon(Request $request){

        $cids = $request->courses;
        $couponc = $request->coupon;
        $discount=0;
         $courses = new Collection(Course::find($cids));
   $total = 0;

         if($request->course_mode=='onetoone_full'){
        $total=$courses->sum('price_1');
                }else if($request->course_mode=='onetoone_monthly'){
        $total=$courses->sum('monthly_price_1');
                }else if($request->course_mode=='onetomany_full'){
        $total=$courses->sum('price');
        }else if($request->course_mode=='full'){
            $total=$courses->sum('full_price');
        }else if($request->course_mode=='quarterly'){
            $total=$courses->sum('quarterly_price');
        }else{
        $total=$courses->sum('monthly_price'); 
        }
    $newTotal = $total;    
                                          



      $gst = $total - $total*100/118;
      $total = $total - $gst;
   
        // $coupon = Coupon::where('code', '=', $coupon)
        //     ->where('status', '=', 1)
        //     ->first();
        if(trim($couponc)!=''){
            $coupon = Coupon::where('code', '=', $couponc)
                ->where('status', '=', 1)
                ->first();
       }else{
           $fc = Course::find($cids[0]);
           if($fc){
             $order = Order::where('user_id', Auth::user()->id)
                    ->where('status', '1')
                    ->orderBy('id', 'desc')
                    ->first();
            
                if ($request->course_mode === 'full') {
                    // if ($order && $order->created_at->timestamp > time()) {
                    //     $coupon = Coupon::find($fc->for_old_user);
                    // } else {
                        
                        $coupon = Coupon::find($fc->coupon_id_full_price);
                    // }
                } else if($request->course_mode === 'quarterly'){
                    
                     $coupon = Coupon::find($fc->coupon_id_quarterly_price);
                } else if($request->course_mode === 'monthly'){
                    
                     $coupon = Coupon::find($fc->coupon_id_monthly_price);
                }else {
                    $coupon = null;
                }
            } else {
                $coupon = null;
            }
    
           
       }

            // dd($coupon);

            if($coupon){

       
         $discount =0;
          $isCouponValid = false;
            if( $coupon->useByUser() < $coupon->per_user_limit ){
                $isCouponValid = true;
                if(($coupon->min_price != null) && ($coupon->min_price > 0)){
                    if($total >= $coupon->min_price){
                        $isCouponValid = true;
                    }
                }else{
                    $isCouponValid = true;
                }
                if($coupon->expires_at != null){
                    if(Carbon::parse($coupon->expires_at) >= Carbon::now()){
                        $isCouponValid = true;
                    }else{
                        $isCouponValid = false;
                    }
                }

            }


            if($isCouponValid == true){
                $type = null;
                if($coupon->type == 1){
                    $type = '-'.$coupon->amount.'%';
                    $discount = $newTotal*$coupon->amount/100;
                }else{
                    $type = '-'.$coupon->amount;
                    if($total > $coupon->amount){
                    $discount = $coupon->amount;
                }else{
                    $discount = $newTotal;
                }

                }

                
                return ['status' => 'success', 'html' => 'Coupon applied succesfully','discount'=>number_format($discount,2),'grant_total'=>round($total-$discount+$gst),'subtotal'=>$total,"gst"=>$gst,'coupon'=>$coupon->code];
            }else{

                return ['status'=>'fail','html'=>'Invalid or Expired coupon','discount'=>number_format($discount,2),'grant_total'=>round($total-$discount+$gst),'subtotal'=>$total,"gst"=>$gst];
            }     
        }else{
           return ['status'=>'fail','html'=>'Invalid or Expired coupon','discount'=>number_format($discount,2),'grant_total'=>round($total-$discount+$gst),'subtotal'=>$total,"gst"=>$gst]; 
        }


    }
    
    public function checkout(Request $request, $slug){
       
        $course = Course::where('slug',$slug)->first();
    //    dd($course);
        if(!$course){
            return abort(404);
        }
         $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
        $category = Category::where('id', '=', $course->category->id)->first();
        $courses = Course::where('category_id',$course->category->id)->where('published',1)->get();
      
        
         return view('course-checkout', compact('course','category','courses','purchased_course'));
    }

    public function renew(Request $request){

        $return = array("success"=>false,"msg"=>"");
        $order = Order::find($request->oid);
        if(!$order){
        return  array("success"=>false,"msg"=>"Order not found");
        }
        if($order->user_id != Auth::user()->id){
            return  array("success"=>false,"msg"=>"Order not found");
        }

        $subs = Subscription::where("order_id",$order->id)->where("status","0")->first();
        if($subs){
            $ref=uniqid();
            $subs->renew_date = date("Y-m-d");
            $subs->reference_no = $ref;
            $subs->update();
             return  array("success"=>true,"order_id"=>$ref);
        }else{

            $sub = new Subscription;
            $sub->order_id = $order->id;
            $sub->amount = $order->amount;
            $sub->gst = $order->gst;
            $sub->discount = $order->discount;
            $sub->coupon_id = $order->coupon_id;
            $sub->user_id = Auth::user()->id;
            $ref=uniqid();
            $sub->renew_date = date("Y-m-d");
            $sub->reference_no = $ref;
            $sub->save();
            return  array("success"=>true,"order_id"=>$ref);
        }


    }

    public function successPay(Request $request){
         $tid = $request->TRANSACTIONID;
 $status = $request->TRANSACTIONPAYMENTSTATUS;
   $oid = explode("-",$tid);
   $cycle=0;
   $ox = Order::find($oid[1]);
   if($oid[0]=="ORDER"){
 $order = Order::find($oid[1]);
$orderx = Order::find($oid[1]);
 $cycle=$order->paid_cycle;
   }else{
    $order = Subscription::with('user')->find($oid[1]);
    $orderx = Order::find($order->order_id);
     $cycle=$orderx->paid_cycle;
   }
   
 if($status=='SUCCESS'){



    $aptid = $request->APTRANSACTIONID;
  
    // $item = Order::find($oid[0]);
    $order->status= "1";
    if(str_contains($order->course_mode,"monthly")){
        if($orderx->end_date){
        if(date("Y-m-d")<date("Y-m-d",strtotime($orderx->end_date))){

            $orderx->end_date= date("Y-m-d",strtotime("+1 Months",strtotime($orderx->end_date)));
            $order->end_date= date("Y-m-d",strtotime("+1 Months",strtotime($orderx->end_date)));
        }else{
            $orderx->end_date= date("Y-m-d",strtotime("+1 Months",time()));
            $order->end_date= date("Y-m-d",strtotime("+1 Months",time()));
        }
    }else{
       $orderx->end_date= date("Y-m-d",strtotime("+1 Months",time())); 
       $order->end_date= date("Y-m-d",strtotime("+1 Months",time())); 
    }
    
}
          $order->transaction_id= $aptid;
          $orderx->transaction_id= $aptid;
          $orderx->paid_cycle= $cycle+1;
          $order->update();
          $orderx->update();
     $ids=[];
$items=[];

if($oid[0]=="ORDER"){
     $ordernx = Order::where('id', '=', $orderx->id)->first();
       
            
             foreach ($ordernx->items as $orderItem) {
                    //Bundle Entries
                    // if ($orderItem->item_type == Bundle::class) {
                    //     foreach ($orderItem->item->courses as $course) {
                    //         $course->students()->attach($orderx->user_id);
                    //     }
                    // }
               
                    $orderItem->item->students()->attach($ordernx->user_id);
                }
}
     foreach($orderx->items as $k=>$item){
        $ids[] = $item->item_id;
     

                $cro = new Course();
                
                    array_push($items, ['number' => $k+1, 'name' => $cro->getCouseNameWithCat($item->item_id), 'price' => $item->price]);

                
                 }

                $content['items'] = $items;
                $content['total'] = ceil($order->amount);
                $content['discount'] = $order->discount;
                $content['gst'] = $order->gst;
                $content['reference_no'] = $order->reference_no;
                $user = User::find($order->user->id);
                Auth::login($user); 
                $el = new Elearn;
                $el->paymentStudentSMS($order->user->first_name,$order->user->phone,ceil($order->amount));

                try {
                    \Mail::to($order->user->email)->send(new OfflineOrderMail($content));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order ' . $order->id);
                }

                try {
                    \Mail::to($order->user->email)->send(new PaymentReceivedEmail($user,ceil($order->amount)));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order payment ' . $order->id);
                }



return redirect('/thank-you');




}else{
    return redirect('/pay/'.$order->reference_no."?failed=true");

      
}




    }

    public function failedPay(Request $request){
    $tid = $request->TRANSACTIONID;
    $aptid = $request->APTRANSACTIONID;
     $oid = explode("-",$tid);
  
   if($oid[0]=="ORDER"){
$item = Order::find($oid[1]);
 $user = User::find($item->user->id);
                Auth::login($user); 

   }else{
    $item = Subscription::with('user')->where("id",$oid[1])->first();

    $user = User::find($item->user->id);
                Auth::login($user);

   }

   return redirect('/pay/'.$item->reference_no."?failed=true");
}


public function payConfirm($ref,$type,Request $request){
    
    $razorpay_order_id = $request->razorpay_order_id;
      $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;
   
            $payfor = $type;
            
            
               
   if($payfor=="ORDER"){
 $order = Order::where("reference_no",$ref)->first();
$orderx = Order::where("reference_no",$ref)->first();
 $cycle=$order->paid_cycle;
   }else{
    $order = Subscription::with('user')->where("reference_no",$ref)->first();
    $orderx = Order::find($order->order_id);
     $cycle=$orderx->paid_cycle;
   }
   
 



  
    // $item = Order::find($oid[0]);
    $order->status= "1";
    if(str_contains($order->course_mode,"monthly")){
        if($orderx->end_date){
        if(date("Y-m-d")<date("Y-m-d",strtotime($orderx->end_date))){

            $orderx->end_date= date("Y-m-d",strtotime("+1 Months",strtotime($orderx->end_date)));
            $order->end_date= date("Y-m-d",strtotime("+1 Months",strtotime($orderx->end_date)));
        }else{
            $orderx->end_date= date("Y-m-d",strtotime("+1 Months",time()));
            $order->end_date= date("Y-m-d",strtotime("+1 Months",time()));
        }
    }else{
       $orderx->end_date= date("Y-m-d",strtotime("+1 Months",time())); 
       $order->end_date= date("Y-m-d",strtotime("+1 Months",time())); 
    }
    
}
          $order->transaction_id= $razorpay_payment_id;
          $orderx->transaction_id= $razorpay_payment_id;
          $orderx->paid_cycle= $cycle+1;
          $order->update();
          $orderx->update();
     $ids=[];
$items=[];

if($payfor=="ORDER"){
     $ordernx = Order::where('id', '=', $orderx->id)->first();
       
            
             foreach ($ordernx->items as $orderItem) {
                    //Bundle Entries
                    // if ($orderItem->item_type == Bundle::class) {
                    //     foreach ($orderItem->item->courses as $course) {
                    //         $course->students()->attach($orderx->user_id);
                    //     }
                    // }
               
                    $orderItem->item->students()->attach($ordernx->user_id);
                }
}
     foreach($orderx->items as $k=>$item){
        $ids[] = $item->item_id;
     

                $cro = new Course();
                
                    array_push($items, ['number' => $k+1, 'name' => $cro->getCouseNameWithCat($item->item_id), 'price' => $item->price]);

                
                 }

                $content['items'] = $items;
                $content['total'] = ceil($order->amount);
                $content['discount'] = $order->discount;
                $content['gst'] = $order->gst;
                $content['reference_no'] = $order->reference_no;
                $user = User::find($order->user->id);
                Auth::login($user); 
                $el = new Elearn;
                $el->paymentStudentSMS($order->user->first_name,$order->user->phone,ceil($order->amount));

                try {
                    \Mail::to($order->user->email)->send(new OfflineOrderMail($content));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order ' . $order->id);
                }

                try {
                    \Mail::to($order->user->email)->send(new PaymentReceivedEmail($user,ceil($order->amount)));
                } catch (\Exception $e) {
                    \Log::info($e->getMessage() . ' for order payment ' . $order->id);
                }



return redirect('/thank-you');





        
}



    public function pay($ref_id=null,Request $request){

        $payfor = "ORDER";
        if($request->payment_for){
            $payfor = $request->payment_for;
        }

        if(!$ref_id){
            return redirect("/");
        }
        if($payfor == "ORDER"){

        $order = Order::where('reference_no',$ref_id)->where('status','0')->first();
        
        if($order){
            
            if($order->payment_method=='razorpay'){
             $rzp_id=   $this->createRzpOrder("order_".$order->id,$order->amount);
             $order->order_id=$rzp_id;
             $order->update();
            }
        }
        
        
    }else{
        $order = Subscription::where("reference_no",$ref_id)->where("status","0")->first();
        $rzp_id=   $this->createRzpOrder("sub_".$order->id,$order->amount);
             $order->order_id=$rzp_id;
             $order->update();
    }
        if(!$order){

            return redirect("/");

        }
        
        // dd($order);

// 
 if($order->payment_method=='razorpay'){
return view('rzp', compact('order','payfor'));
}else{
  return view('paynow', compact('order','payfor'));  
}
    }
    
    
    
    public function createRzpOrder($rep,$amount){
        $ch = curl_init();
$data=array(
    "amount"=>$amount*100,
    "currency"=>"INR",
    "receipt"=>$rep
    );
curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_USERPWD, env('RZP_KEY') . ':' . env('RZP_SECRET'));

$headers = array();
$headers[] = 'Content-Type: application/json';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);

$rd = json_decode($result,true);

return $rd["id"];
    }
    

    public function checkoutPay(Request $request,$slug)
    {
        
        if (auth()->check()) {

         $order_item_list =  new OrderItem();
         $order_item_list->item_id = $request->course_id;
         $order_item_list->price = $request->final_price;
         $order_item_list->save();

         $order_list = new Order();
         $order_list->order_id = $order_item_list->id;
         $order_list->amount = $order_item_list->price;
         $order_list->status = '1';
         $order_list->user_id = auth::user()->id;
         $order_list->save();

         return redirect()->back()->with('success', 'Course Purchase');

        } else {
            return redirect('/userlogin');
        }
        
    }


    public function rating($course_id, Request $request)
    {
        $course = Course::findOrFail($course_id);
        $course->students()->updateExistingPivot(\Auth::id(), ['rating' => $request->get('rating')]);

        return redirect()->back()->with('success', 'Thank you for rating.');
    }

    public function getByCategory(Request $request)
    {
        $category = Category::where('slug', '=', $request->category)
            ->where('status','=',1)
            ->first();
        $categories = Category::where('status','=',1)->paginate(6);

        if ($category != "") {
            $pcats=Category::where("parent",$category->id)->where("status","1")->get();
            // dd($pcats);
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $featured_courses = Course::where('published', '=', 1)
                ->where('featured', '=', 1)->take(8)->get();

            // if (request('type') == 'popular') {
            //     $courses = $category->courses()->with('lessons')->withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(9);

            // } else if (request('type') == 'trending') {
            //     $courses = $category->courses()->with('lessons')->withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(9);

            // } else if (request('type') == 'featured') {
            //     $courses = $category->courses()->with('lessons')->withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(9);

            // } else {
            //     $courses = $category->courses()->with('lessons')->withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(9);
            // }
            
             $coursesd = $category->courses()->with('lessons')->withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc');
      if($request->key){
          $coursesd->where("title","like","%".$request->key."%");
      }
      
      $courses = $coursesd->paginate(9);
      

            $view = 'cat';
        if($request->view){
            if($request->view=='grid'){
                $view = 'cat-grid';
            }
        } 

// dd($courses[1]);

            //return view( $this->path.'.courses.index', compact('courses', 'category', 'recent_news','featured_courses','categories'));
               return view($view, compact('view','courses', 'category', 'recent_news','featured_courses','categories','pcats'));
        }
        return abort(404);
    }

      public function categoryPage()
    {
        $course_categories = Category::with('courses')->take(20)->get();
        
        return view('category', compact('course_categories'));
    }



    public function addReview(Request $request)
    {
        $this->validate($request, [
            'review' => 'required'
        ]);
        $course = Course::findORFail($request->id);
        $review = new Review();
        $review->user_id = auth()->user()->id;
        $review->reviewable_id = $course->id;
        $review->reviewable_type = Course::class;
        $review->rating = $request->rating;
        $review->content = $request->review;
        $review->save();

        return back();
    }

    public function editReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $course = $review->reviewable;
            $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            $course_rating = 0;
            $total_ratings = 0;
            $lessons = $course->courseTimeline()->orderby('sequence','asc')->get();

            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
            if (\Auth::check()) {

                $completed_lessons = \Auth::user()->chapters()->where('course_id', $course->id)->get()->pluck('model_id')->toArray();
                $continue_course  = $course->courseTimeline()->orderby('sequence','asc')->whereNotIn('model_id',$completed_lessons)->first();
                if($continue_course == ""){
                    $continue_course = $course->courseTimeline()->orderby('sequence','asc')->first();
                }

            }
            return view( $this->path.'.courses.course', compact('course', 'purchased_course', 'recent_news','completed_lessons','continue_course', 'course_rating', 'total_ratings','lessons', 'review'));
        }
        return abort(404);

    }


    public function updateReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $review->rating = $request->rating;
            $review->content = $request->review;
            $review->save();

            return redirect()->route('courses.show', ['slug' => $review->reviewable->slug]);
        }
        return abort(404);

    }

    public function deleteReview(Request $request)
    {
        $review = Review::where('id', '=', $request->id)->where('user_id', '=', auth()->user()->id)->first();
        if ($review) {
            $slug = $review->reviewable->slug;
            $review->delete();
            return redirect()->route('courses.show', ['slug' => $slug]);
        }
        return abort(404);
    }

}
