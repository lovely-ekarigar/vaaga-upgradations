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
    
   public function demoCourse(Request $request)
{
    $ret = '';

    //  Category selected → show subcategory OR course
    if ($request->cat_id) {

        $cats = Category::where("parent", $request->cat_id)
                        ->orderBy("sort_order", "asc")
                        ->get();

        if ($cats->count() > 0) {
            $ret = '<select class="form-control mb-3 form-select l3" id="select_subcat">
                        <option value="">Select Sub Category</option>';
            foreach ($cats as $c) {
                $ret .= '<option value="'.$c->id.'">'.$c->name.'</option>';
            }
            $ret .= '</select>';
            return $ret;   // STOP 
        }

        // no subcategory → show course
        $courses = Course::where("category_id", $request->cat_id)
                         ->orderBy("sort_order", "asc")
                         ->get();

        $ret = '<select class="form-control mb-3 form-select l4" id="select_course">
                    <option value="">Select Course</option>';
        foreach ($courses as $c) {
            $ret .= '<option value="'.$c->id.'">'.$c->title.'</option>';
        }
        $ret .= '</select>';

        return $ret;   // STOP
    }

    //  Subcategory selected → show course
    if ($request->subcat_id) {

        $courses = Course::where("category_id", $request->subcat_id)
                         ->orderBy("sort_order", "asc")
                         ->get();

        $ret = '<select class="form-control mb-3 form-select l4" id="select_course">
                    <option value="">Select Course</option>';
        foreach ($courses as $c) {
            $ret .= '<option value="'.$c->id.'">'.$c->title.'</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    //  Board selected → show class
    if ($request->board_id) {

        $cats = Category::where("board_id", $request->board_id)
                        ->where('parent', $request->category_id)
                        ->orderBy("sort_order", "asc")
                        ->get();

        $ret = '<select class="form-control mb-3 form-select l5" id="select_class">
                    <option value="">Select Class</option>';
        foreach ($cats as $c) {
            $ret .= '<option value="'.$c->id.'">'.$c->name.'</option>';
        }
        $ret .= '</select>';

        return $ret;
    }

    return '';
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
         }else if($request->course_mode=='regular_monthly'){
$total=$courses->sum('regular_monthly');
         }else if($request->course_mode=='regular_monthly_1'){
$total=$courses->sum('regular_monthly_1');
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
            // Set payment_type based on payment_method
        $paymentTypes = [
            'stripe' => 1,
            'paypal' => 2,
            'offline' => 3,
            'razorpay' => 4
        ];
        $order->payment_type = $paymentTypes[strtolower($request->payment_method ?? 'razorpay')] ?? 4;
        $order->aff_code = Cookie::get('affiliate_code');
        // Set payment_cycle based on course_mode
        if (in_array($request->course_mode, ['onetoone_full', 'onetomany_full', 'full'])) {
            $order->payment_cycle = 'full';
        } else {
            $order->payment_cycle = 'monthly';
        }
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
         }else if($request->course_mode=='regular_monthly'){
$price=$course->regular_monthly;
         }else if($request->course_mode=='regular_monthly_1'){
$price=$course->regular_monthly_1;
         }else if($request->course_mode=='onetomany_full'){
$price=$course->price;
}else if($request->course_mode=='full'){
    $price=$course->full_price;
}else if($request->course_mode=='quarterly'){
    $price=$course->quarterly_price;
}else{
   $price=$course->monthly_price;
 }

            $order->items()->create([
                'item_id' => $cid,
                'item_type' => $type,
                'price' => ceil($price),
            ]);
        }
                $orx = Order::find($order->id);
        // Set total_cycle:
        // - full course types => 1
        // - regular monthly => 0 (custom requirement)
        // - other monthly types => course duration
        if ($request->course_mode === 'regular_monthly' || $request->course_mode === 'regular_monthly_1') {
            $orx->total_cycle = 0;
        } else {
            $orx->total_cycle = in_array($request->course_mode, ['full', 'onetoone_full', 'onetomany_full']) ? 1 : $duration;
        }
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




    public function download($cid, $mid){
        $crs = Course::withoutGlobalScope('filter')->where('id', $cid)->with('publishedLessons')->firstOrFail();
        $purchased_course = \Auth::check() && $crs->students()->where('user_id', \Auth::id())->count() > 0;
        
        if(!$purchased_course){
            return redirect()->back()->withFlashDanger('Please purchase this course to download files.');
        }

        $media = Media::findOrFail($mid);
        
        // Check if it's external media (YouTube, Vimeo, etc.)
        if($media->is_external){
            return redirect($media->url);
        }

        // Get file path
        $filePath = 'uploads/' . $media->file_name;
        
        if(!\Storage::disk('public')->exists($filePath)){
            return redirect()->back()->withFlashDanger('File not found on server.');
        }

        // Return file download response
        return \Storage::disk('public')->download(
            $filePath,
            $media->name ?? $media->file_name,
            ['Content-Type' => $media->mime_type ?? 'application/octet-stream']
        );
    }

    public function study($course_slug, $les_slug = ""){
        $crs = Course::withoutGlobalScope('filter')
            ->where('slug', $course_slug)
            ->with('publishedLessons')
            ->firstOrFail();
            
        $purchased_course = \Auth::check() && $crs->students()->where('user_id', \Auth::id())->count() > 0;
        $course = Course::where("slug", $course_slug)->first();

        // Get first lesson if no lesson slug provided
        if($les_slug == ""){
            $les = Lesson::where("course_id", $course->id)->first();
        } else {
            $les = Lesson::where("slug", $les_slug)->where("course_id", $course->id)->first();  
        }

        // Get course contents with lessons and media
        $contents = CourseContent::where("course_id", $course->id)
            ->orderBy("sort_order", "asc")
            ->get();
            
        $current = (object)["video" => null, "full_text" => null, "pdf" => null, "media" => null];
        $clist = [];
        
        foreach($contents as $ct){
            // Load lessons with their media using eager loading
            $clessons = Lesson::where("content_id", $ct->id)
                ->with(['media' => function($query) {
                    // Get all media types including youtube, upload, lesson_pdf, lesson_audio
                    $query->orderBy('created_at', 'asc');
                }])
                ->orderBy('position', 'asc')
                ->get();
                
            $rl = [];
            
            foreach($clessons as $cl){
                // Get video media (youtube, vimeo, upload)
                $cl->video = $cl->media->first(function($m) {
                    return in_array($m->type, ['youtube', 'vimeo', 'upload', 'embed']);
                });
                
                // Get PDF media
                $cl->pdf = $cl->media->first(function($m) {
                    return $m->type === 'lesson_pdf';
                });
                
                // Get audio media
                $cl->audio = $cl->media->first(function($m) {
                    return $m->type === 'lesson_audio';
                });
                
                // Set current lesson
                if($les && $les->id == $cl->id){
                    $current = $cl;
                }
                
                $rl[] = $cl;
            } 
            
            $ct->lessons = $rl;
            $clist[] = $ct;
        }

        return view('frontend.study', compact('course', 'current', 'clist', 'purchased_course'));
    }
    
    public function applyCoupon($total,$coupon){


        $gst = 0;
      $totalWithGst = $total;
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
                    $discount = $totalWithGst*$coupon->amount/100;
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
        try {
            if(auth()->check() && $course->reviews()->where('user_id','=',auth()->user()->id)->first()){
                $is_reviewed = true;
            }
            if ($course->reviews->count() > 0) {
                $course_rating = $course->reviews->avg('rating');
                $total_ratings = $course->reviews()->where('rating', '!=', "")->get()->count();
            }
        } catch (\Exception $e) {
            // reviews table doesn't exist, use default values
            $course_rating = 0;
            $total_ratings = 0;
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
$courses = collect([]);
            if($category){
$pcategory = Category::where('id', '=', $category->parent)
            ->where('status','=',1)
            ->first();
            $courses = $category->courses()->withoutGlobalScope('filter')->where('published', 1)->orderByRaw('RAND()')->paginate(4);
            }
//dd($courses); 
 
$acode = Cookie::get("affiliate_code");
$aff = Affiliate::where("code",$acode)->first();

$conf = Config::where("key","affiliate_user")->first();
// dd($purchased_course);

        return view('frontend.course', compact('course','couponInfo','cccode', 'purchased_course', 'recent_news', 'course_rating', 'completed_lessons','total_ratings','is_reviewed','lessons','continue_course','courses','clist','acode','aff','conf','category','pcategory'));
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
                }else if($request->course_mode=='regular_monthly'){
        $total=$courses->sum('regular_monthly');
            }else if($request->course_mode=='regular_monthly_1'){
        $total=$courses->sum('regular_monthly_1');
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
                                          



      $gst = 0;
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
                } else if($request->course_mode === 'regular_monthly'){
                    $coupon = null;
                } else if($request->course_mode === 'regular_monthly_1'){
                    $coupon = null;
                }else{
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
      
        
         return view('frontend.course-checkout', compact('course','category','courses','purchased_course'));
    }

    public function renew(Request $request){

        try {
            $order = Order::find($request->oid);
            if(!$order){
                return response()->json(["success"=>false,"msg"=>"Order not found"]);
            }
            if($order->user_id != Auth::user()->id){
                return response()->json(["success"=>false,"msg"=>"Order not found"]);
            }

            // Check if ANY subscription entry exists for this order
            $anySubscription = Subscription::where("order_id", $order->id)->first();
            
            // If no subscription exists at all, create the initial subscription entry first
            if(!$anySubscription) {
                \Log::info('No subscription entry found for order, creating initial subscription', [
                    'order_id' => $order->id,
                    'order_reference_no' => $order->reference_no,
                    'order_status' => $order->status,
                    'order_paid_cycle' => $order->paid_cycle
                ]);
                
                // Create the initial subscription entry (cycle_no = 1) using order's reference_no
                $initialSub = new Subscription;
                $initialSub->order_id = $order->id;
                $initialSub->cycle_no = 1;
                $initialSub->amount = $order->amount;
                $initialSub->gst = $order->gst;
                $initialSub->discount = $order->discount;
                $initialSub->coupon_id = $order->coupon_id;
                $initialSub->user_id = $order->user_id;
                $initialSub->course_mode = $order->course_mode;
                $initialSub->reference_no = $order->reference_no; // Use order's reference_no
                $initialSub->status = 1; // Mark as paid since the order is already paid
                $initialSub->transaction_id = $order->transaction_id;
                $initialSub->renew_date = $order->created_at;
                $initialSub->end_date = $order->end_date;
                
                if(!$initialSub->save()){
                    \Log::error('Failed to create initial subscription in renew', [
                        'order_id' => $order->id
                    ]);
                    return response()->json(["success"=>false,"msg"=>"Failed to create initial subscription"], 500);
                }
                
                \Log::info('Initial subscription created successfully', [
                    'order_id' => $order->id,
                    'subscription_id' => $initialSub->id,
                    'cycle_no' => $initialSub->cycle_no,
                    'reference_no' => $initialSub->reference_no
                ]);
            }

            // Check if there's already a pending (unpaid) subscription for this renewal
            // Handle both integer 0 and empty string for status
            $pendingSubs = Subscription::where("order_id",$order->id)
                ->where(function($q) {
                    $q->where("status", 0)
                      ->orWhere("status", "")
                      ->orWhereNull("status");
                })
                ->orderBy('cycle_no', 'desc')
                ->first();
            
            if($pendingSubs){
                \Log::info('Renew - Found existing pending subscription', [
                    'subscription_id' => $pendingSubs->id,
                    'cycle_no' => $pendingSubs->cycle_no,
                    'reference_no' => $pendingSubs->reference_no
                ]);
                // If there's already a pending subscription, just return it
                return response()->json(["success"=>true,"order_id"=>$pendingSubs->reference_no]);
            }

            // Get the latest paid subscription to determine next cycle number
            $lastPaidSubs = Subscription::where("order_id",$order->id)
                ->where("status","1")
                ->orderBy('cycle_no', 'desc')
                ->first();
            
            // Calculate next cycle number: paid_cycle + 1
            // This ensures cycle_no matches with the payment cycle
            $nextCycleNo = ($order->paid_cycle ?? 0) + 1;
            
            \Log::info('Creating new subscription entry for renewal', [
                'order_id' => $order->id,
                'current_paid_cycle' => $order->paid_cycle,
                'next_cycle_no' => $nextCycleNo,
                'last_paid_cycle_no' => $lastPaidSubs ? $lastPaidSubs->cycle_no : 0
            ]);

            // Always create a NEW subscription entry for each renewal
            $sub = new Subscription;
            $sub->order_id = $order->id;
            $sub->cycle_no = $nextCycleNo;
            $sub->amount = $order->amount;
            $sub->gst = $order->gst;
            $sub->discount = $order->discount;
            $sub->coupon_id = $order->coupon_id;
            $sub->user_id = Auth::user()->id;
            $sub->course_mode = $order->course_mode;
            $ref=uniqid();
            $sub->renew_date = date("Y-m-d H:i:s");
            $sub->reference_no = $ref;
            $sub->status = (int)0; // Pending payment - force integer type
            
            if(!$sub->save()){
                \Log::error('Failed to save subscription in renew', [
                    'order_id' => $order->id,
                    'cycle_no' => $nextCycleNo,
                    'reference_no' => $ref
                ]);
                return response()->json(["success"=>false,"msg"=>"Failed to create subscription"], 500);
            }
            
            \Log::info('Subscription save() returned true', [
                'subscription_id' => $sub->id,
                'reference_no' => $ref,
                'order_id' => $order->id
            ]);
            
            // Force refresh the model from database to ensure it's committed
            $sub->refresh();
            
            // Clear query builder cache
            \DB::connection()->flushQueryLog();
            
            // Add a small sleep to ensure database write completes (especially on replicated DBs)
            usleep(100000); // 100ms
            
            // Verify the subscription was saved using direct DB query to bypass any model scopes
            // Handle both integer 0 and empty string for status
            $verifySubscription = \DB::table('subscriptions')
                ->where('reference_no', $ref)
                ->where(function($q) {
                    $q->where('status', 0)
                      ->orWhere('status', '')
                      ->orWhereNull('status');
                })
                ->first();
            
            if(!$verifySubscription){
                \Log::error('Subscription not found after save in renew', [
                    'order_id' => $order->id,
                    'reference_no' => $ref,
                    'subscription_id' => $sub->id,
                    'attempted_query' => 'SELECT * FROM subscriptions WHERE reference_no = '.$ref.' AND status = 0'
                ]);
                
                // Try one more time with just the ID
                $verifyById = \DB::table('subscriptions')->where('id', $sub->id)->first();
                if($verifyById){
                    \Log::info('Found subscription by ID but not by reference_no', [
                        'subscription_id' => $verifyById->id,
                        'db_reference_no' => $verifyById->reference_no,
                        'expected_reference_no' => $ref,
                        'status' => $verifyById->status
                    ]);
                    // If we can find it by ID, return success anyway
                    return response()->json(["success"=>true,"order_id"=>$ref]);
                }
                
                return response()->json(["success"=>false,"msg"=>"Subscription verification failed"], 500);
            }
            
            \Log::info('Subscription created and verified successfully', [
                'subscription_id' => $verifySubscription->id,
                'order_id' => $verifySubscription->order_id,
                'reference_no' => $verifySubscription->reference_no,
                'cycle_no' => $verifySubscription->cycle_no,
                'amount' => $verifySubscription->amount,
                'status' => $verifySubscription->status
            ]);
            
            return response()->json(["success"=>true,"order_id"=>$ref]);
        } catch (\Exception $e) {
            \Log::error('Renew subscription error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'oid' => $request->oid,
                'user_id' => Auth::check() ? Auth::user()->id : null,
            ]);
            return response()->json(["success"=>false,"msg"=>"Server error: ".$e->getMessage()], 500);
        }

    }

    public function successPay(Request $request){
        \Log::info('SuccessPay START', [
            'request_all' => $request->all()
        ]);
        
         $tid = $request->TRANSACTIONID;
 $status = $request->TRANSACTIONPAYMENTSTATUS;
   $oid = explode("-",$tid);
   $cycle=0;
   $ox = Order::find($oid[1]);
   if($oid[0]=="ORDER"){
 $order = Order::find($oid[1]);
$orderx = Order::find($oid[1]);
 $cycle=$order->paid_cycle;
 
 \Log::info('SuccessPay - ORDER payment', [
     'order_id' => $order->id,
     'current_paid_cycle' => $cycle
 ]);
   }else{
    $order = Subscription::with('user')->find($oid[1]);
    
    if(!$order) {
        \Log::error('Subscription not found in successPay', ['subscription_id' => $oid[1]]);
        return redirect('/')->with('error', 'Subscription not found');
    }
    
    $orderx = Order::find($order->order_id);
    
    if(!$orderx) {
        \Log::error('Parent order not found in successPay', ['subscription_id' => $order->id, 'order_id' => $order->order_id]);
        return redirect('/')->with('error', 'Order not found');
    }
    
     $cycle=$orderx->paid_cycle;
     
     \Log::info('SuccessPay - SUBSCRIPTION payment loaded', [
         'subscription_id' => $order->id,
         'order_id' => $orderx->id,
         'subscription_status' => $order->status,
         'subscription_end_date' => $order->end_date,
         'order_end_date' => $orderx->end_date,
         'order_paid_cycle' => $orderx->paid_cycle,
         'course_mode' => $orderx->course_mode
     ]);
   }
   
 if($status=='SUCCESS'){

    $aptid = $request->APTRANSACTIONID;

    // Check if this subscription is already paid to prevent duplicate cycle increment on repayment
    // IMPORTANT: Check this BEFORE setting status to "1"
    $isAlreadyPaid = false;
    if($oid[0]=="ORDER"){
        // For ORDER payments, check if order is already paid
        $isAlreadyPaid = ($order->status == "1");
        \Log::info('SuccessPay - Checking if order already paid', [
            'order_id' => $order->id,
            'current_status' => $order->status,
            'is_already_paid' => $isAlreadyPaid
        ]);
    } else if($oid[0]=="SUBSCRIPTION" && $order instanceof Subscription){
        $isAlreadyPaid = ($order->status == "1");
        \Log::info('SuccessPay - Checking if subscription already paid', [
            'subscription_id' => $order->id,
            'current_status' => $order->status,
            'is_already_paid' => $isAlreadyPaid
        ]);
    }
  
    // $item = Order::find($oid[0]);
    $order->status= "1";
    // Use course_mode from parent Order ($orderx) for both ORDER and SUBSCRIPTION
    $courseMode = $orderx->course_mode ?? '';
    
    \Log::info('SuccessPay - Checking course_mode', [
        'payfor' => $oid[0],
        'course_mode' => $courseMode,
        'contains_monthly' => str_contains($courseMode,"monthly"),
        'current_order_end_date' => $orderx->end_date,
        'is_renewal' => ($orderx->end_date && date("Y-m-d") < date("Y-m-d",strtotime($orderx->end_date)))
    ]);
    
       // Capture the current end_date BEFORE any modifications for last EMI logic
    $previousEndDate = $orderx->end_date;

    if(str_contains($courseMode,"monthly")){
        if (in_array($courseMode, ['regular_monthly', 'regular_monthly_1'])) {
            $baseEndDate = $orderx->end_date ?: date("Y-m-d");
            $newEndDate = date("Y-m-d", strtotime("+1 Months", strtotime($baseEndDate)));
            $orderx->end_date = $newEndDate;
            $order->end_date = $newEndDate;

            \Log::info('SuccessPay - Regular monthly: extending end_date from previous by +1 month', [
                'course_mode' => $courseMode,
                'base_end_date' => $baseEndDate,
                'new_end_date' => $newEndDate
            ]);
        } else {
        // Check if this is a renewal with valid end_date
        if($orderx->end_date && date("Y-m-d") < date("Y-m-d",strtotime($orderx->end_date))){
            // Renewal: extend from existing end_date by 1 month
            $oldEndDate = $orderx->end_date;
            $newEndDate = date("Y-m-d",strtotime("+1 Months",strtotime($oldEndDate)));
            $orderx->end_date = $newEndDate;
            $order->end_date = $newEndDate;
            
            \Log::info('SuccessPay - Renewal: extending end_date', [
                'old_end_date' => $oldEndDate,
                'new_end_date' => $newEndDate
            ]);
        } else {
            // New purchase or expired subscription: check for batch enrollment
            $courseIds = $orderx->items->pluck('item_id')->toArray();
            $userId = $orderx->user_id;
            
            $enrolledBatch = \DB::table('student_teacher_batches')
                ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
                ->whereIn('batches.cid', $courseIds)
                ->where('student_teacher_batches.uid', $userId)
                ->select('batches.start_date')
                ->first();
            
            if ($enrolledBatch && $enrolledBatch->start_date) {
                // Use day from batch start_date, month from purchase date + 1 month
                $batchDay = Carbon::parse($enrolledBatch->start_date)->day;
                $newEndDate = Carbon::now()->addMonth()->day($batchDay)->format('Y-m-d');
                $orderx->end_date = $newEndDate;
                $order->end_date = $newEndDate;
                
                \Log::info('SuccessPay - New/expired: using batch date', [
                    'batch_start_date' => $enrolledBatch->start_date,
                    'new_end_date' => $newEndDate
                ]);
            } else {
                // Default: use purchase date + 1 month
                $newEndDate = date("Y-m-d",strtotime("+1 Months",time()));
                $orderx->end_date = $newEndDate;
                $order->end_date = $newEndDate;
                
                \Log::info('SuccessPay - New/expired: using default +1 month', [
                    'new_end_date' => $newEndDate
                ]);
            }
        }
        }
    } else if(str_contains($courseMode, "full")) {
        // Full course purchase: set end_date to batch start date + 6 months (if batch assigned), else order date + 6 months
        $courseIds = $orderx->items->pluck('item_id')->toArray();
        $userId = $orderx->user_id;

        $enrolledBatch = \DB::table('student_teacher_batches')
            ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
            ->whereIn('batches.cid', $courseIds)
            ->where('student_teacher_batches.uid', $userId)
            ->select('batches.start_date')
            ->first();

        if ($enrolledBatch && $enrolledBatch->start_date) {
            $newEndDate = Carbon::parse($enrolledBatch->start_date)->addMonths(6)->format('Y-m-d');
            \Log::info('SuccessPay - Full course: using batch start date + 6 months', [
                'batch_start_date' => $enrolledBatch->start_date,
                'new_end_date' => $newEndDate
            ]);
        } else {
            $newEndDate = Carbon::now()->addMonths(6)->format('Y-m-d');
            \Log::info('SuccessPay - Full course: using order date + 6 months', [
                'new_end_date' => $newEndDate
            ]);
        }
        $orderx->end_date = $newEndDate;
        $order->end_date = $newEndDate;
    } else {
        \Log::warning('SuccessPay - Course mode does not contain monthly or full', [
            'course_mode' => $courseMode,
            'order_id' => $orderx->id,
            'payfor' => $oid[0]
        ]);
    }
          
          $order->transaction_id= $aptid;
          $orderx->transaction_id= $aptid;
          
          // Only increment paid_cycle if this is a new payment (not a repayment)
          if(!$isAlreadyPaid){
              $orderx->paid_cycle= $cycle+1;
              \Log::info('SuccessPay - Incrementing paid_cycle', [
                  'old_paid_cycle' => $cycle,
                  'new_paid_cycle' => $cycle+1
              ]);
          } else {
              \Log::info('SuccessPay - Skipping paid_cycle increment (repayment)', [
                  'paid_cycle' => $orderx->paid_cycle
              ]);
          }
          
          // Validate cycle_no for subscription entries (already set during subscription creation)
          if($oid[0]=="SUBSCRIPTION" && $order instanceof Subscription){
              // Verify that cycle_no matches the expected value
              $expectedCycleNo = $cycle + 1;
              if($order->cycle_no != $expectedCycleNo){
                  \Log::warning('SuccessPay - cycle_no mismatch detected', [
                      'subscription_id' => $order->id,
                      'current_cycle_no' => $order->cycle_no,
                      'expected_cycle_no' => $expectedCycleNo,
                      'order_paid_cycle' => $orderx->paid_cycle
                  ]);
                  // Fix the cycle_no if there's a mismatch
                  $order->cycle_no = $expectedCycleNo;
              }
              
              \Log::info('SuccessPay - Validated subscription cycle_no', [
                  'subscription_id' => $order->id,
                  'cycle_no' => $order->cycle_no,
                  'order_paid_cycle' => $orderx->paid_cycle
              ]);
          }
          
          // Check if this is the last cycle payment - if yes, extend end_date by 6 months from batch start date
          if(!in_array($courseMode, ['regular_monthly', 'regular_monthly_1']) && $orderx->total_cycle && ($cycle + 1) >= $orderx->total_cycle){
              // This is the last cycle payment - get batch start date and extend by 6 months
              $courseIds = $orderx->items->pluck('item_id')->toArray();
              $userId = $orderx->user_id;

              $enrolledBatch = \DB::table('student_teacher_batches')
                  ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
                  ->whereIn('batches.cid', $courseIds)
                  ->where('student_teacher_batches.uid', $userId)
                  ->select('batches.start_date')
                  ->first();

              if ($enrolledBatch && $enrolledBatch->start_date) {
                  // Use batch start date + 6 months for orders table
                  $newEndDate = Carbon::parse($enrolledBatch->start_date)->addMonths(6)->format('Y-m-d');
                  $orderx->end_date = $newEndDate;

                  // For subscriptions table on last EMI, use previous end_date + 1 month
                  if($oid[0] == "SUBSCRIPTION") {
                      $subscriptionEndDate = Carbon::parse($previousEndDate)->addMonth()->format('Y-m-d');
                      $order->end_date = $subscriptionEndDate;

                      \Log::info('SuccessPay - Last cycle payment: orders table +6 months, subscriptions table = original end_date +1 month', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'batch_start_date' => $enrolledBatch->start_date,
                          'original_end_date' => $previousEndDate,
                          'orders_end_date' => $newEndDate,
                          'subscription_end_date' => $subscriptionEndDate
                      ]);
                  } else {
                      $order->end_date = $newEndDate;

                      \Log::info('SuccessPay - Last cycle payment: extending end_date by 6 months from batch start date', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'batch_start_date' => $enrolledBatch->start_date,
                          'new_end_date' => $newEndDate
                      ]);
                  }
              } else {
                  // Fallback: use order date + 6 months if no batch found
                  $currentDate = $orderx->created_at ? Carbon::parse($orderx->created_at)->format('Y-m-d') : date("Y-m-d");
                  $newEndDate = Carbon::parse($currentDate)->addMonths(6)->format('Y-m-d');
                  $orderx->end_date = $newEndDate;

                  // For subscriptions table on last EMI, use previous end_date + 1 month
                  if($oid[0] == "SUBSCRIPTION") {
                      $subscriptionEndDate = Carbon::parse($previousEndDate)->addMonth()->format('Y-m-d');
                      $order->end_date = $subscriptionEndDate;

                      \Log::warning('SuccessPay - Last cycle payment: No batch found, orders table +6 months, subscriptions table = original end_date +1 month', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'order_date' => $currentDate,
                          'original_end_date' => $previousEndDate,
                          'orders_end_date' => $newEndDate,
                          'subscription_end_date' => $subscriptionEndDate
                      ]);
                  } else {
                      $order->end_date = $newEndDate;

                      \Log::warning('SuccessPay - Last cycle payment: No batch found, using order date + 6 months', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'order_date' => $currentDate,
                          'new_end_date' => $newEndDate
                      ]);
                  }
              }
          } 
          
          \Log::info('SuccessPay - About to save values', [
              'payfor' => $oid[0],
              'order_type' => get_class($order),
              'orderx_type' => get_class($orderx),
              'order_status' => $order->status,
              'order_end_date' => $order->end_date,
              'order_transaction_id' => $order->transaction_id,
              'orderx_end_date' => $orderx->end_date,
              'orderx_paid_cycle' => $orderx->paid_cycle,
              'orderx_transaction_id' => $orderx->transaction_id
          ]);
          
          // Save updates
          try {
              $orderSaved = $order->save();
              $orderxSaved = $orderx->save();
              
              \Log::info('SuccessPay - Save completed', [
                  'order_saved' => $orderSaved,
                  'orderx_saved' => $orderxSaved
              ]);
              
              // Reload from database to verify
              $order->refresh();
              $orderx->refresh();
              
              \Log::info('SuccessPay - After reload from DB', [
                  'order_id' => $order->id,
                  'order_status' => $order->status,
                  'order_end_date' => $order->end_date,
                  'orderx_id' => $orderx->id,
                  'orderx_end_date' => $orderx->end_date,
                  'orderx_paid_cycle' => $orderx->paid_cycle
              ]);
          } catch (\Exception $e) {
              \Log::error('SuccessPay - Error saving payment updates', [
                  'error' => $e->getMessage(),
                  'trace' => $e->getTraceAsString()
              ]);
              throw $e;
          }
          
          // Create first subscription entry for ORDER payments (first-time purchase)
          if($oid[0]=="ORDER" && !$isAlreadyPaid && $orderx->status == 1){
              // Check if subscription already exists for this order and cycle
              $existingSubscription = Subscription::where('order_id', $orderx->id)
                  ->where('cycle_no', $orderx->paid_cycle)
                  ->first();
              
              if(!$existingSubscription){
                  try {
                      $firstSubscription = new Subscription();
                      $firstSubscription->order_id = $orderx->id;
                      $firstSubscription->cycle_no = $orderx->paid_cycle;
                      $firstSubscription->amount = $orderx->amount;
                      $firstSubscription->gst = $orderx->gst;
                      $firstSubscription->discount = $orderx->discount;
                      $firstSubscription->coupon_id = $orderx->coupon_id;
                      $firstSubscription->user_id = $orderx->user_id;
                      $firstSubscription->course_mode = $orderx->course_mode;
                      $firstSubscription->reference_no = $orderx->reference_no;
                      $firstSubscription->transaction_id = $aptid;
                      $firstSubscription->renew_date = date("Y-m-d H:i:s");
                      $firstSubscription->end_date = $orderx->end_date;
                      $firstSubscription->status = 1; // Paid
                      $firstSubscription->save();
                      
                      \Log::info('SuccessPay - Created first subscription entry for ORDER', [
                          'order_id' => $orderx->id,
                          'subscription_id' => $firstSubscription->id,
                          'cycle_no' => $firstSubscription->cycle_no,
                          'reference_no' => $firstSubscription->reference_no
                      ]);
                  } catch (\Exception $e) {
                      \Log::error('SuccessPay - Failed to create first subscription', [
                          'order_id' => $orderx->id,
                          'error' => $e->getMessage()
                      ]);
                  }
              } else {
                  \Log::info('SuccessPay - Subscription already exists for this order/cycle', [
                      'order_id' => $orderx->id,
                      'cycle_no' => $orderx->paid_cycle,
                      'subscription_id' => $existingSubscription->id
                  ]);
              }
          }
          
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
    
    \Log::info('PayConfirm START', [
        'ref' => $ref,
        'type' => $type,
        'request_all' => $request->all()
    ]);
    
    $razorpay_order_id = $request->razorpay_order_id;
      $razorpay_payment_id = $request->razorpay_payment_id;
        $razorpay_signature = $request->razorpay_signature;
   
        // Verify Razorpay signature to prevent payment tampering
        $secret = env('RZP_SECRET');
        
        \Log::info('Signature verification details', [
            'secret' => substr($secret, 0, 4) . '...', // Log first 4 chars only
            'razorpay_order_id' => $razorpay_order_id,
            'razorpay_payment_id' => $razorpay_payment_id
        ]);
        
        $expectedSignature = hash_hmac('sha256', $razorpay_order_id . '|' . $razorpay_payment_id, $secret);
        if (!hash_equals($expectedSignature, $razorpay_signature)) {
            \Log::error('Razorpay signature verification failed', [
                'ref' => $ref,
                'type' => $type,
                'expected' => $expectedSignature,
                'received' => $razorpay_signature,
                'secret_length' => strlen($secret)
            ]);
            return redirect('/pay/'.$ref."?failed=true&error=invalid_signature&payment_for=".strtoupper($type));
        }
   
            $payfor = $type;
            
            \Log::info('PayConfirm - Signature verified', [
                'ref' => $ref,
                'payfor' => $payfor,
                'razorpay_payment_id' => $razorpay_payment_id
            ]);
            
               
   if($payfor=="ORDER"){
 $order = Order::where("reference_no",$ref)->first();
$orderx = Order::where("reference_no",$ref)->first();
 $cycle=$order->paid_cycle;
 
 \Log::info('PayConfirm - ORDER payment', [
     'order_id' => $order->id,
     'current_paid_cycle' => $cycle,
     'current_end_date' => $orderx->end_date
 ]);
   }else{
    $order = Subscription::with('user')->where("reference_no",$ref)->first();
    
    if(!$order) {
        \Log::error('Subscription not found', ['ref' => $ref]);
        return redirect('/')->with('error', 'Subscription not found');
    }
    
    $orderx = Order::find($order->order_id);
    
    if(!$orderx) {
        \Log::error('Parent order not found', ['subscription_id' => $order->id, 'order_id' => $order->order_id]);
        return redirect('/')->with('error', 'Order not found');
    }
    
     $cycle=$orderx->paid_cycle;
     
     \Log::info('PayConfirm - SUBSCRIPTION payment - loaded from DB', [
         'subscription_id' => $order->id,
         'order_id' => $orderx->id,
         'subscription_status' => $order->status,
         'subscription_end_date' => $order->end_date,
         'order_end_date' => $orderx->end_date,
         'order_paid_cycle' => $orderx->paid_cycle,
         'course_mode' => $orderx->course_mode
     ]);
   }
   
 

    // Check if this subscription is already paid to prevent duplicate cycle increment on repayment
    // IMPORTANT: Check this BEFORE setting status to "1"
    $isAlreadyPaid = false;
    if($payfor=="ORDER"){
        // For ORDER payments, check if order is already paid
        $isAlreadyPaid = ($order->status == "1");
        \Log::info('PayConfirm - Checking if order already paid', [
            'order_id' => $order->id,
            'current_status' => $order->status,
            'is_already_paid' => $isAlreadyPaid
        ]);
    } else if($payfor=="SUBSCRIPTION" && $order instanceof Subscription){
        $isAlreadyPaid = ($order->status == "1");
        \Log::info('PayConfirm - Checking if subscription already paid', [
            'subscription_id' => $order->id,
            'current_status' => $order->status,
            'is_already_paid' => $isAlreadyPaid
        ]);
    }

  
    // $item = Order::find($oid[0]);
    $order->status= "1";
    // Use course_mode from parent Order ($orderx) for both ORDER and SUBSCRIPTION
    $courseMode = $orderx->course_mode ?? '';
    
    \Log::info('PayConfirm - Checking course_mode', [
        'payfor' => $payfor,
        'course_mode' => $courseMode,
        'contains_monthly' => str_contains($courseMode,"monthly"),
        'current_order_end_date' => $orderx->end_date,
        'is_renewal' => ($orderx->end_date && date("Y-m-d") < date("Y-m-d",strtotime($orderx->end_date)))
    ]);
        
    // Capture the current end_date BEFORE any modifications for last EMI logic
    $previousEndDate = $orderx->end_date;

    if(str_contains($courseMode,"monthly")){
        if (in_array($courseMode, ['regular_monthly', 'regular_monthly_1'])) {
            $baseEndDate = $orderx->end_date ?: date("Y-m-d");
            $newEndDate = date("Y-m-d", strtotime("+1 Months", strtotime($baseEndDate)));
            $orderx->end_date = $newEndDate;
            $order->end_date = $newEndDate;

            \Log::info('PayConfirm - Regular monthly: extending end_date from previous by +1 month', [
                'course_mode' => $courseMode,
                'base_end_date' => $baseEndDate,
                'new_end_date' => $newEndDate
            ]);
        } else {
        // Check if this is a renewal with valid end_date
        if($orderx->end_date && date("Y-m-d") < date("Y-m-d",strtotime($orderx->end_date))){
            // Renewal: extend from existing end_date by 1 month
            $oldEndDate = $orderx->end_date;
            $newEndDate = date("Y-m-d",strtotime("+1 Months",strtotime($oldEndDate)));
            $orderx->end_date = $newEndDate;
            $order->end_date = $newEndDate;
            
            \Log::info('PayConfirm - Renewal: extending end_date', [
                'old_end_date' => $oldEndDate,
                'new_end_date' => $newEndDate
            ]);
        } else {
            // New purchase or expired subscription: check for batch enrollment
            $courseIds = $orderx->items->pluck('item_id')->toArray();
            $userId = $orderx->user_id;
            
            $enrolledBatch = \DB::table('student_teacher_batches')
                ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
                ->whereIn('batches.cid', $courseIds)
                ->where('student_teacher_batches.uid', $userId)
                ->select('batches.start_date')
                ->first();
            
            if ($enrolledBatch && $enrolledBatch->start_date) {
                // Use day from batch start_date, month from purchase date + 1 month
                $batchDay = Carbon::parse($enrolledBatch->start_date)->day;
                $newEndDate = Carbon::now()->addMonth()->day($batchDay)->format('Y-m-d');
                $orderx->end_date = $newEndDate;
                $order->end_date = $newEndDate;
                
                \Log::info('PayConfirm - New/expired: using batch date', [
                    'batch_start_date' => $enrolledBatch->start_date,
                    'new_end_date' => $newEndDate
                ]);
            } else {
                // Default: use purchase date + 1 month
                $newEndDate = date("Y-m-d",strtotime("+1 Months",time()));
                $orderx->end_date = $newEndDate;
                $order->end_date = $newEndDate;
                
                \Log::info('PayConfirm - New/expired: using default +1 month', [
                    'new_end_date' => $newEndDate
                ]);
            }
        }
        }
    } else if(str_contains($courseMode, "full")) {
        // Full course purchase: set end_date to batch start date + 6 months (if batch assigned), else order date + 6 months
        $courseIds = $orderx->items->pluck('item_id')->toArray();
        $userId = $orderx->user_id;

        $enrolledBatch = \DB::table('student_teacher_batches')
            ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
            ->whereIn('batches.cid', $courseIds)
            ->where('student_teacher_batches.uid', $userId)
            ->select('batches.start_date')
            ->first();

        if ($enrolledBatch && $enrolledBatch->start_date) {
            $newEndDate = Carbon::parse($enrolledBatch->start_date)->addMonths(6)->format('Y-m-d');
            \Log::info('PayConfirm - Full course: using batch start date + 6 months', [
                'batch_start_date' => $enrolledBatch->start_date,
                'new_end_date' => $newEndDate
            ]);
        } else {
            $newEndDate = Carbon::now()->addMonths(6)->format('Y-m-d');
            \Log::info('PayConfirm - Full course: using order date + 6 months', [
                'new_end_date' => $newEndDate
            ]);
        }
        $orderx->end_date = $newEndDate;
        $order->end_date = $newEndDate;
    } else {
        \Log::warning('PayConfirm - Course mode does not contain monthly or full', [
            'course_mode' => $courseMode,
            'order_id' => $orderx->id,
            'payfor' => $payfor
        ]);
    }
          
          $order->transaction_id= $razorpay_payment_id;
          $orderx->transaction_id= $razorpay_payment_id;
          
          // Only increment paid_cycle if this is a new payment (not a repayment)
          if(!$isAlreadyPaid){
              $orderx->paid_cycle= $cycle+1;
              \Log::info('PayConfirm - Incrementing paid_cycle', [
                  'old_paid_cycle' => $cycle,
                  'new_paid_cycle' => $cycle+1
              ]);
          } else {
              \Log::info('PayConfirm - Skipping paid_cycle increment (repayment)', [
                  'paid_cycle' => $orderx->paid_cycle
              ]);
          }
          
          // Validate cycle_no for subscription entries (already set during subscription creation)
          if($payfor=="SUBSCRIPTION" && $order instanceof Subscription){
              // Verify that cycle_no matches the expected value
              $expectedCycleNo = $cycle + 1;
              if($order->cycle_no != $expectedCycleNo){
                  \Log::warning('PayConfirm - cycle_no mismatch detected', [
                      'subscription_id' => $order->id,
                      'current_cycle_no' => $order->cycle_no,
                      'expected_cycle_no' => $expectedCycleNo,
                      'order_paid_cycle' => $orderx->paid_cycle
                  ]);
                  // Fix the cycle_no if there's a mismatch
                  $order->cycle_no = $expectedCycleNo;
              }
              
              \Log::info('PayConfirm - Validated subscription cycle_no', [
                  'subscription_id' => $order->id,
                  'cycle_no' => $order->cycle_no,
                  'order_paid_cycle' => $orderx->paid_cycle
              ]);
          }
          
          // Check if this is the last cycle payment - if yes, extend end_date by 6 months from batch start date
           if(!in_array($courseMode, ['regular_monthly', 'regular_monthly_1']) && $orderx->total_cycle && ($cycle + 1) >= $orderx->total_cycle){
              // This is the last cycle payment - get batch start date and extend by 6 months
              $courseIds = $orderx->items->pluck('item_id')->toArray();
              $userId = $orderx->user_id;

              $enrolledBatch = \DB::table('student_teacher_batches')
                  ->join('batches', 'student_teacher_batches.bid', '=', 'batches.id')
                  ->whereIn('batches.cid', $courseIds)
                  ->where('student_teacher_batches.uid', $userId)
                  ->select('batches.start_date')
                  ->first();

              if ($enrolledBatch && $enrolledBatch->start_date) {
                  // Use batch start date + 6 months for orders table
                  $newEndDate = Carbon::parse($enrolledBatch->start_date)->addMonths(6)->format('Y-m-d');
                  $orderx->end_date = $newEndDate;

                  // For subscriptions table on last EMI, use previous end_date + 1 month
                  if($payfor == "SUBSCRIPTION") {
                      $subscriptionEndDate = Carbon::parse($previousEndDate)->addMonth()->format('Y-m-d');
                      $order->end_date = $subscriptionEndDate;

                      \Log::info('PayConfirm - Last cycle payment: orders table +6 months, subscriptions table = previous end_date +1 month', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'batch_start_date' => $enrolledBatch->start_date,
                          'previous_end_date' => $previousEndDate,
                          'orders_end_date' => $newEndDate,
                          'subscription_end_date' => $subscriptionEndDate
                      ]);
                  } else {
                      $order->end_date = $newEndDate;

                      \Log::info('PayConfirm - Last cycle payment: extending end_date by 6 months from batch start date', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'batch_start_date' => $enrolledBatch->start_date,
                          'new_end_date' => $newEndDate
                      ]);
                  }
              } else {
                  // Fallback: use order date + 6 months if no batch found
                  $currentDate = $orderx->created_at ? Carbon::parse($orderx->created_at)->format('Y-m-d') : date("Y-m-d");
                  $newEndDate = Carbon::parse($currentDate)->addMonths(6)->format('Y-m-d');
                  $orderx->end_date = $newEndDate;

                  // For subscriptions table on last EMI, use previous end_date + 1 month
                  if($payfor == "SUBSCRIPTION") {
                      $subscriptionEndDate = Carbon::parse($previousEndDate)->addMonth()->format('Y-m-d');
                      $order->end_date = $subscriptionEndDate;

                      \Log::warning('PayConfirm - Last cycle payment: No batch found, orders table +6 months, subscriptions table = previous end_date +1 month', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'order_date' => $currentDate,
                          'previous_end_date' => $previousEndDate,
                          'orders_end_date' => $newEndDate,
                          'subscription_end_date' => $subscriptionEndDate
                      ]);
                  } else {
                      $order->end_date = $newEndDate;

                      \Log::warning('PayConfirm - Last cycle payment: No batch found, using order date + 6 months', [
                          'total_cycle' => $orderx->total_cycle,
                          'paid_cycle_after_payment' => $cycle + 1,
                          'order_date' => $currentDate,
                          'new_end_date' => $newEndDate
                      ]);
                  }
              }
          } 
          
          \Log::info('About to save - values set', [
              'payfor' => $payfor,
              'order_type' => get_class($order),
              'orderx_type' => get_class($orderx),
              'order_status' => $order->status,
              'order_end_date' => $order->end_date,
              'order_transaction_id' => $order->transaction_id,
              'orderx_end_date' => $orderx->end_date,
              'orderx_paid_cycle' => $orderx->paid_cycle,
              'orderx_transaction_id' => $orderx->transaction_id
          ]);
          
          // Save updates
          try {
              $orderSaved = $order->save();
              $orderxSaved = $orderx->save();
              
              \Log::info('Save completed', [
                  'order_saved' => $orderSaved,
                  'orderx_saved' => $orderxSaved
              ]);
              
              // Reload from database to verify
              $order->refresh();
              $orderx->refresh();
              
              \Log::info('After reload from DB', [
                  'order_id' => $order->id,
                  'order_status' => $order->status,
                  'order_end_date' => $order->end_date,
                  'orderx_id' => $orderx->id,
                  'orderx_end_date' => $orderx->end_date,
                  'orderx_paid_cycle' => $orderx->paid_cycle
              ]);
          } catch (\Exception $e) {
              \Log::error('Error saving payment updates', [
                  'error' => $e->getMessage(),
                  'trace' => $e->getTraceAsString()
              ]);
              throw $e;
          }
          
          // Create first subscription entry for ORDER payments (first-time purchase)
          if($payfor=="ORDER" && !$isAlreadyPaid && $orderx->status == 1){
              // Check if subscription already exists for this order and cycle
              $existingSubscription = Subscription::where('order_id', $orderx->id)
                  ->where('cycle_no', $orderx->paid_cycle)
                  ->first();
              
              if(!$existingSubscription){
                  try {
                      $firstSubscription = new Subscription();
                      $firstSubscription->order_id = $orderx->id;
                      $firstSubscription->cycle_no = $orderx->paid_cycle;
                      $firstSubscription->amount = $orderx->amount;
                      $firstSubscription->gst = $orderx->gst;
                      $firstSubscription->discount = $orderx->discount;
                      $firstSubscription->coupon_id = $orderx->coupon_id;
                      $firstSubscription->user_id = $orderx->user_id;
                      $firstSubscription->course_mode = $orderx->course_mode;
                      $firstSubscription->reference_no = $orderx->reference_no;
                      $firstSubscription->transaction_id = $razorpay_payment_id;
                      $firstSubscription->renew_date = date("Y-m-d H:i:s");
                      $firstSubscription->end_date = $orderx->end_date;
                      $firstSubscription->status = 1; // Paid
                      $firstSubscription->save();
                      
                      \Log::info('PayConfirm - Created first subscription entry for ORDER', [
                          'order_id' => $orderx->id,
                          'subscription_id' => $firstSubscription->id,
                          'cycle_no' => $firstSubscription->cycle_no,
                          'reference_no' => $firstSubscription->reference_no
                      ]);
                  } catch (\Exception $e) {
                      \Log::error('PayConfirm - Failed to create first subscription', [
                          'order_id' => $orderx->id,
                          'error' => $e->getMessage()
                      ]);
                  }
              } else {
                  \Log::info('PayConfirm - Subscription already exists for this order/cycle', [
                      'order_id' => $orderx->id,
                      'cycle_no' => $orderx->paid_cycle,
                      'subscription_id' => $existingSubscription->id
                  ]);
              }
          }
          
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
            $payfor = strtoupper($request->payment_for);
        }

        \Log::info('Pay function called', [
            'ref_id' => $ref_id,
            'payment_for' => $payfor
        ]);

        if(!$ref_id){
            return redirect("/");
        }
        
        if($payfor == "ORDER"){
            $order = Order::where('reference_no',$ref_id)->where('status','0')->first();
            
            if($order){
                // Always create Razorpay order for new payments (default to razorpay)
                $paymentMethod = strtolower($order->payment_method ?? 'razorpay');
                if($paymentMethod == 'razorpay' || empty($order->payment_method)){
                    try {
                        $rzp_id = $this->createRzpOrder("order_".$order->id,$order->amount);
                        $order->order_id=$rzp_id;
                        $order->save(); // Fixed: use save() instead of update()
                        \Log::info('Razorpay order created for ORDER', ['rzp_id' => $rzp_id]);
                    } catch (\Exception $e) {
                        \Log::error('Razorpay Order Creation Failed', [
                            'order_id' => $order->id,
                            'reference_no' => $ref_id,
                            'error' => $e->getMessage()
                        ]);
                        return redirect()->back()->with('error', 'Payment gateway error: ' . $e->getMessage());
                    }
                }
            }
        } else if($payfor == "SUBSCRIPTION") {
            // Handle SUBSCRIPTION payments - always use Razorpay
            
            // First, check if ANY subscription with this reference_no exists (regardless of status)
            $anySubscription = Subscription::where("reference_no",$ref_id)->first();
            
            \Log::info('Subscription lookup - checking all', [
                'ref_id' => $ref_id,
                'any_found' => $anySubscription ? true : false,
                'status_if_found' => $anySubscription ? $anySubscription->status : null,
                'id_if_found' => $anySubscription ? $anySubscription->id : null
            ]);
            
            // Now get the unpaid subscription - handle both integer 0 and empty string
            $order = Subscription::where("reference_no",$ref_id)
                ->where(function($q) {
                    $q->where("status", 0)
                      ->orWhere("status", "")
                      ->orWhereNull("status");
                })
                ->first();
            
            \Log::info('Subscription lookup - unpaid only', [
                'ref_id' => $ref_id,
                'found' => $order ? true : false,
                'subscription_id' => $order ? $order->id : null,
                'amount' => $order ? $order->amount : null,
                'payment_ref' => $order ? $order->payment_ref : null
            ]);
            
            if($order){
                // Always create a fresh Razorpay order for subscription renewals
                try {
                    $rzp_id = $this->createRzpOrder("sub_".$order->id, $order->amount);
                    $order->payment_ref = $rzp_id;
                    $order->save(); // Fixed: use save() instead of update()
                    \Log::info('Razorpay order created for SUBSCRIPTION', [
                        'subscription_id' => $order->id,
                        'rzp_id' => $rzp_id
                    ]);
                } catch (\Exception $e) {
                    \Log::error('Razorpay Subscription Order Creation Failed', [
                        'subscription_id' => $order->id,
                        'reference_no' => $ref_id,
                        'error' => $e->getMessage()
                    ]);
                    return redirect()->back()->with('error', 'Payment gateway error: ' . $e->getMessage());
                }
                
                // Return Razorpay view directly for subscriptions
                return view('frontend.rzp', compact('order','payfor'));
            }
        }
        
        if(!$order){
            // Try one more time with raw DB query to bypass Eloquent
            if($payfor == "SUBSCRIPTION"){
                $rawLookup = \DB::table('subscriptions')
                    ->where('reference_no', $ref_id)
                    ->where(function($q) {
                        $q->where('status', 0)
                          ->orWhere('status', '')
                          ->orWhereNull('status');
                    })
                    ->first();
                
                \Log::warning('Order not found via Eloquent, trying raw DB', [
                    'ref_id' => $ref_id,
                    'ref_id_length' => strlen($ref_id),
                    'ref_id_raw' => bin2hex($ref_id),
                    'payfor' => $payfor,
                    'raw_found' => $rawLookup ? true : false,
                    'raw_id' => $rawLookup ? $rawLookup->id : null
                ]);
                
                if($rawLookup){
                    // Try to load via Eloquent using ID
                    $order = Subscription::find($rawLookup->id);
                    \Log::info('Loaded subscription via ID after raw lookup', [
                        'subscription_id' => $order ? $order->id : null
                    ]);
                }
            }
        }
        
        if(!$order){
            \Log::error('Order not found - all methods exhausted', [
                'ref_id' => $ref_id,
                'payfor' => $payfor
            ]);
            return redirect("/")->with('error', 'Order not found. Please contact support with reference: ' . $ref_id);
        }
     
        // For ORDER payments, determine view based on payment method
        $paymentMethod = strtolower($order->payment_method ?? 'razorpay');
        
        \Log::info('Payment view selection', [
            'payfor' => $payfor,
            'payment_method' => $paymentMethod,
            'order_type' => get_class($order)
        ]);
        
        // Default to Razorpay when payment method is razorpay or not set
        if($paymentMethod == 'razorpay' || empty($paymentMethod)){
            return view('frontend.rzp', compact('order','payfor'));
        }else{
            return view('frontend.paynow', compact('order','payfor'));  
        }
    }
    
    
    
    public function createRzpOrder($rep,$amount){
        // Check if Razorpay credentials are configured
        if (empty(env('RZP_KEY')) || empty(env('RZP_SECRET'))) {
            \Log::error('Razorpay credentials not configured');
            throw new \Exception('Payment gateway configuration error. Please contact support.');
        }

        $ch = curl_init();
        $data = array(
            "amount" => $amount * 100,
            "currency" => "INR",
            "receipt" => $rep
        );
        curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/orders');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_USERPWD, env('RZP_KEY') . ':' . env('RZP_SECRET'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        $curlErrno = curl_errno($ch);
        
        curl_close($ch);

        // Check for cURL errors
        if ($curlErrno) {
            \Log::error('Razorpay cURL Error: ' . $curlError, ['errno' => $curlErrno, 'receipt' => $rep, 'amount' => $amount]);
            throw new \Exception('Payment gateway connection error. Please try again later.');
        }

        // Check HTTP response code
        if ($httpCode !== 200) {
            \Log::error('Razorpay API Error: HTTP ' . $httpCode, ['response' => $result, 'receipt' => $rep, 'amount' => $amount]);
            throw new \Exception('Payment gateway error. Please try again or contact support.');
        }

        // Decode JSON response
        $rd = json_decode($result, true);
        
        // Check if JSON decode was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            \Log::error('Razorpay JSON Decode Error: ' . json_last_error_msg(), ['response' => $result, 'receipt' => $rep]);
            throw new \Exception('Invalid response from payment gateway. Please try again.');
        }

        // Check if response has error
        if (isset($rd['error'])) {
            $errorMsg = $rd['error']['description'] ?? $rd['error']['code'] ?? 'Unknown error';
            \Log::error('Razorpay API Error Response', ['error' => $rd['error'], 'receipt' => $rep, 'amount' => $amount]);
            throw new \Exception('Payment gateway error: ' . $errorMsg);
        }

        // Check if 'id' key exists
        if (!isset($rd['id']) || empty($rd['id'])) {
            \Log::error('Razorpay Response Missing ID', ['response' => $rd, 'receipt' => $rep, 'amount' => $amount]);
            throw new \Exception('Invalid response from payment gateway. Order ID not received.');
        }

        return $rd['id'];
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
               return view('frontend.'.$view, compact('view','courses', 'category', 'recent_news','featured_courses','categories','pcats'));
        }
        return abort(404);
    }

      public function categoryPage()
    {
        $course_categories = Category::with('courses')->take(20)->get();
        
        return view('frontend.category', compact('course_categories'));
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
            return view('frontend.courses.course', compact('course', 'purchased_course', 'recent_news','completed_lessons','continue_course', 'course_rating', 'total_ratings','lessons', 'review'));
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

    /**
     * Course details page (alias for show)
     */
    public function details($slug)
    {
        return $this->show(request(), $slug);
    }

    /**
     * Process course payment
     */
    public function payment(Request $request)
    {
        // TODO: Implement payment processing
        return redirect()->back()->with('error', 'Payment processing not implemented');
    }

}
