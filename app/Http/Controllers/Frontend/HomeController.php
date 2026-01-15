<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Board;
use App\Models\Order;
use App\Models\Batch;
use App\Models\Category;
use App\Models\Config;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Recording;
use App\Models\Page; 
use App\Models\General;
use App\Models\Reason;
use App\Models\Achievement;
use App\Models\Sponsor;
use App\Models\Elearn;
use App\Models\Slider;
use App\Models\Feedback;
use App\Models\DemoFeedback;
use App\Models\TeacherFeedbackQuestion;
use App\Models\TeacherBatch;
use App\Models\TeacherFee;
use App\Models\TeacherAttendance;
use App\Models\StudentTeacherBatch;
use App\Models\Training;
use App\Rules\Recaptcha;
use App\Models\StudentFeedbackQuestion;
// use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Newsletter;
use Auth;
use URL;
use Illuminate\Support\Facades\Validator;
// Input facade removed in Laravel 5.4+ - use Request instead
// use Illuminate\Support\Facades\Input;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Auth\Events\Registered;
use Hash;
use Illuminate\Support\Facades\Password;
use App\Models\TeacherProfile;
use Session;
use App\Mail\Frontend\Demo\TeacherOnboardingEmail;
use App\Mail\Frontend\Demo\AdminTutorEmail;
use App\Mail\AdminFeedbackEmail;
use App\Mail\Frontend\Demo\StudentRegistrationEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\Demo\SubscriptionDueEmail;
use App\Mail\TeacherFeedbackEmail;
use App\Mail\FeedbackEmail;
use App\Models\VideoLink;


use Stevebauman\Location\Facades\Location;
use DateTime;
/**
 * 
 * Class HomeController.
 */
class HomeController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */

    private $path;

    public function userTraining()
    {
        $student_list = Training::where('training_for','student')->get();
        return view('backend.user_training.training',compact('student_list'));
        
        
    }
    
    public function checkOtp(){
       
       $g = new Elearn();
       $otp = rand(1111,9999);
      $d=  $g->paymentStudentSMS("Sanjay",'7089322883',500); 
    
        dd($d);
    }

    public function __construct()
    {

        $path = 'frontend';
        if (session()->has('display_type')) {
            if (session('display_type') == 'rtl') {
                $path = 'frontend-rtl';
            } else {
                $path = 'frontend';
            }
        } else if (config('app.display_type') == 'rtl') {
            $path = 'frontend-rtl';
        }
        $this->path = $path;
    }




    public function cronFeedbackEmail(){


$batches = Batch::where("is_completed","0")->get();

foreach($batches as $bc){

$datetime1 = new DateTime($bc->last_feedback ? $bc->last_feedback : "2023-08-01" );
$datetime2 = new DateTime(date("Y-m-d"));
$interval = $datetime1->diff($datetime2);
$days = $interval->format('%a');
// dd($days);
if($days>=15){
$d1 = date("Y-m-d",strtotime("-1 days",time()));
$d2 = date("Y-m-d",strtotime("-2 days",time()));
$d3 = date("Y-m-d",strtotime("-3 days",time()));
$d4 = date("Y-m-d",strtotime("-4 days",time()));
$d5 = date("Y-m-d",strtotime("-5 days",time()));
$rec1 = Recording::where("created_at","like","%".$d1."%")->where("parent",$bc->parent_api_class_id)->first();
$rec2 = Recording::where("created_at","like","%".$d2."%")->where("parent",$bc->parent_api_class_id)->first();
$rec3 = Recording::where("created_at","like","%".$d3."%")->where("parent",$bc->parent_api_class_id)->first();
$rec4 = Recording::where("created_at","like","%".$d4."%")->where("parent",$bc->parent_api_class_id)->first();
$rec5 = Recording::where("created_at","like","%".$d5."%")->where("parent",$bc->parent_api_class_id)->first();


if($rec1 || $rec2 || $rec3 || $rec4 || $rec5){
    $bx = Batch::find($bc->id);
    $bx->last_feedback = date("Y-m-d");
    $bx->update();

$stb = StudentTeacherBatch::where('bid',$bc->id)->get();

$tid = 0;
foreach($stb as $s){
$tid = $s->tid;
    $user = User::find($s->uid);
    if($user){
Mail::to($user)->send(new FeedbackEmail($user,$bc->id));
    }
}

$teacher = User::find($tid);
if($teacher){
Mail::to($teacher)->send(new TeacherFeedbackEmail($teacher));
}


}

}


    }
    }



    public function cronSubscriptionDue(){

        $orders = Order::where("course_mode","like","%monthly%")->where("status","1")->get();

        $dateRange = [7,3,1,0];
    
        foreach($orders as $order){
            // Check if required columns exist
            $hasTotalCycle = isset($order->total_cycle);
            $hasPaidCycle = isset($order->paid_cycle);
            $hasEndDate = isset($order->end_date) && !empty($order->end_date);
            
            // Only process if cycle columns exist and condition is met
            if($hasTotalCycle && $hasPaidCycle && $order->total_cycle > $order->paid_cycle && $hasEndDate){

            $datetime2 = new DateTime($order->end_date);
             $datetime1 = new DateTime(date("Y-m-d"));
            $interval = $datetime1->diff($datetime2);
            $inv =  $interval->format('%a');

            if(in_array($inv,$dateRange)){
                //Send Email

                  $items ='';

                foreach ($order->items as $key => $item) {
                    if($item->item != null){
                        $key++;
                         $crs = new Course();
                      
                        $items .= $crs->getCouseNameWithCat($item->item->id) . ", ";
                    }

                }

                    $user = User::find($order->user->id);
                    Mail::to($user)->send(new SubscriptionDueEmail($order,$items));
                }
            }
        }


    }




    public function cronAttendance(){

        $recs = Recording::where('marked_attendance','0')->where('end_time','!=',null)->where('end_time','!=','')->get();

        // dd($recs);

        foreach($recs as $r){

            $batchInfo = Batch::where('parent_api_class_id',$r->parent)->first();
            if($batchInfo){
          
 $tb = TeacherBatch::where("bid",$batchInfo->id)->where("tid",$r->tid)->first();

 if($tb){
 if($tb->fees){
                

                $mins = 0;
                $mins = ceil(($r->end_time - $r->start_time)/60);
                if($mins>0){

                $ta = new TeacherAttendance();
                $ta->teacher_id = $r->tid;
                $ta->batch_id = $batchInfo->id;
                $ta->hours = $mins;
                $ta->fees = $tb->fees;
                $ta->date = date("Y-m-d",strtotime($r->created_at));
                $ta->save();
            }
             $rx = Recording::find($r->id);
        $rx->marked_attendance = '1';
        $rx->update();

            

}
       }     
        }

       

        }

    }


    public function cronAttendanceRec(){

$recs = Recording::where('end_time',null)->orWhere('end_time','')->orderBy('id','desc')->get();
foreach($recs as $r){
                $el=new Elearn;
        $in=array(
            "internalMeetingID"=>$r->internal_id,
            "meetingID"=>$r->api_class_id
        );
        $attnd=$el->eClassJson("getAttendance",$in);

        if(array_key_exists('response',$attnd)){
            if(array_key_exists('meetings',$attnd['response'])){
                if(array_key_exists('meeting',$attnd['response']['meetings'])){
                    // dd($attnd->response->meetings->meeting);
                    if(array_key_exists('users',$attnd['response']['meetings']['meeting'])){

                   
                        if(count(json_decode(json_encode($attnd['response']['meetings']['meeting']['users']),true))>=1){
                            if(array_key_exists('endedOn',$attnd['response']['meetings']['meeting'])){
                            $rx = Recording::find($r->id);
                            $rx->end_time = ceil($attnd['response']['meetings']['meeting']['endedOn']/1000);
                          
                            $rx->update();
                        }


                        }else{

                           //start and end same 

                              if($r->start_time==null || $r->start_time ==''){
                             $rx = Recording::find($r->id);
                            $rx->end_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->start_time =ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->update();

                        }else{
                            $rx = Recording::find($r->id);
                           $rx->end_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->start_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->update();
                        }
                        }

                    }else{
                        if($r->start_time==null || $r->start_time ==''){
                             $rx = Recording::find($r->id);
                            $rx->end_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->start_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->update();

                        }else{
                            $rx = Recording::find($r->id);
                         $rx->end_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->start_time = ceil($attnd['response']['meetings']['meeting']['createdOn']/1000);
                            $rx->update();
                        }

                        //start and end same
                    }

                }

            }

        }
        }
        
    }
    
    
     public function about(){

        $categories = Category::where("parent","0")->where("status","1")->orderBy('sort_order','asc')->get();
    
       return view('about',compact('categories'));
  }
  
  public function otp()
  {
      if(!Session::has('user_id')){
          return redirect("/");
      }
       $user = User::find(Session::get('user_id'));
      return view('home.otp',compact('user'));
  }
  public function sendotp(Request $request)
  {
      $user = User::find(Session::get('user_id'));
      if($user->otp==$request->otp){
          $user->otp_verified = '1';
          $user->update();

            

          Auth::login($user);
            

          //Send Onboarding email
           
          return redirect('/user/dashboard'); 
      }else{
          return redirect('/otp')->with('error', 'Invalid OTP'); 
      }
     
  }
  
  public function forgotPassword() 
    {
        return view('home.forgotpassword');
    }
    
public function setForgotPassword(Request $request)
{
     $user = User::where('email',$request->email)->first();

    if($user)
    {
         $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
    
 $status === Password::RESET_LINK_SENT;
                if($status){
 return redirect('/forgot/password')->with('success', 'Password reset email link sent to email.');
                }else{
                 return redirect('/forgot/password')->with('flash_message', 'Unable to send Email');    
                }        
    }
    else{
    return redirect('/forgot/password')->with('flash_message', 'Email Not Found.');
     
    }
    
} 

public function demoFeedback($id){

$demo_id = $id;

    if (auth()->user()->hasRole('student')) {

        $question_list = StudentFeedbackQuestion::all();
        
        return view('home.demo-feedback',compact('demo_id','question_list'));
    }
    if (auth()->user()->hasRole('teacher')) {
       
       $question_list = TeacherFeedbackQuestion::all();
        
        return view('home.demo-feedback',compact('demo_id','question_list'));
    }

}

public function feedback($id)
{



    $batch_id = $id;

    if (auth()->user()->hasRole('student')) {

        $question_list = StudentFeedbackQuestion::all();
        
        return view('home.feedback',compact('batch_id','question_list'));
    }
    if (auth()->user()->hasRole('teacher')) {

        if(Session::has('class_rec_id')){
            $rec= Recording::find(Session::get('class_rec_id'));
            $rec->end_time = time();
            $rec->update();
            Session::forget('class_rec_id');

        }
       
       $question_list = TeacherFeedbackQuestion::all();
        
        return view('home.feedback',compact('batch_id','question_list'));
    }
   
}



public function demofeedbackCreate(Request $request,$id)
{



    foreach ($request->rating as $k=>$v) {
         $feedback_list = new DemoFeedback();
    $feedback_list->user_id = Auth()->user()->id;
    $feedback_list->demo_id = $id;
    $feedback_list->message = $request->message;
    $feedback_list->question_id = $k;
    if(auth()->user()->hasRole('teacher')){
       $feedback_list->is_teacher = '1';     
    }
    if(auth()->user()->hasRole('student')){
       $feedback_list->is_teacher = '0';   
        
    }
    $feedback_list->review = $v;
    $feedback_list->save();
       }
    
   if(auth()->user()->hasRole('student')){
   

       $user = User::find(Auth::user()->id);
       // Mail::to($user) 
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminFeedbackEmail($user));
    }

    return redirect()->back()->with('flash_message', 'Thank you for Submitting your Feedback');


}

public function feedbackCreate(Request $request,$id)
{

    foreach ($request->rating as $k=>$v) {
         $feedback_list = new Feedback();
    $feedback_list->user_id = Auth()->user()->id;
    $feedback_list->batch_id = $id;
    $feedback_list->message = $request->message;
    $feedback_list->question_id = $k;
    if(auth()->user()->hasRole('teacher')){
       $feedback_list->is_teacher = '1';     
    }
    if(auth()->user()->hasRole('student')){
       $feedback_list->is_teacher = '0';    

      
    }
    $feedback_list->review = $v;
    $feedback_list->save();
       }
    
   if(auth()->user()->hasRole('student')){
   

       $user = User::find(Auth::user()->id);
       // Mail::to($user) 
        Mail::to(env('ADMIN_EMAIL'))->send(new AdminFeedbackEmail($user));
    }

    return redirect()->back()->with('flash_message', 'Thank you for Submitting your Feedback');


}
    
  public function login(Request $request){
    $red=$request->redirect;
       return view('home.login',compact('red'));
  }

  public function becometeacherRegister(){

    $crss = Course::orderBy("sort_order",'asc')->get();
    $courses = [];

    foreach($crss as $c){
        $cro = new Course();
        $c->title = $cro->getCouseNameWithCat($c->id);
        $courses[]=$c;
    }

    $categories = Category::where("parent","0")->orderBy('sort_order','asc')->get();

  
       return view('home.teacher-register',compact('categories','courses'));
  }

  public function supportHelpdesk()
  {
    return view('support_helpdesk');
  }

   public function category($slug){

     $find_parent_category = Category::where('slug',$slug)->first();

     if($find_parent_category->is_board){
       
        $categories_list = Category::where('parent',$find_parent_category->id)->where("status","1")->orderBy('sort_order','asc')->get();

        $categories = Category::where("parent","0")->orderBy('sort_order','asc')->get();
        $boards = Board::orderBy('id','desc')->where('status','1')->get();

       return view('boards',compact('find_parent_category','categories_list','categories','boards'));

     }else{ 
    $find_parent_category = Category::where('slug',$slug)->first();
    $categories_list = Category::where('parent',$find_parent_category->id)->where("status","1")->orderBy('sort_order','asc')->get();
    $categories = Category::where("parent","0")->where("status","1")->orderBy('sort_order','asc')->get();
     $courses=Course::where('published', '=', 1)->where('category_id',$find_parent_category->id)->get();
    //  dd($courses);

    return view('category-or-course',compact('categories_list','categories','slug','courses','find_parent_category'));
     
     }

     
  }

  public function academicFind(Request $request,$slug,$cat){

   $boards = $slug;
   $academic_slug = $cat;

   $find_parent_category = Category::where('slug',$academic_slug)->first();
  $board = Board::where('slug',$slug)->first();

if(!$board){
    return abort(404);
}

   $categories_list = Category::where('parent',$find_parent_category->id)->where("board_id",$board->id)->orderBy('sort_order','asc')->get();
    $categories = Category::where("parent","0")->orderBy('sort_order','asc')->get();

    return view('category',compact('categories_list','categories','boards','academic_slug','board','find_parent_category'));
  }

   public function academicCourse(Request $request,$board,$slug){

   $category_list = $slug;
//   $board = $request->board;

   $find_category_id = Category::where('slug',$category_list)->first();
   $find_baords_id = Board::where('slug',$board)->first();


   $courses=Course::where('published', '=', 1)->where('category_id',$find_category_id->id)->where('board_id',$find_baords_id->id)->get();

   $categories = Category::where("parent","0")->orderBy('sort_order','asc')->get();

  $categories_data = Category::where('parent',$find_category_id->id)->orderBy('sort_order','asc')->get();
  $categories_parent_data = Category::where('id',$find_category_id->parent)->first();
    
    //dd($categories_data_);
    return view('academic-course',compact('courses','categories','category_list','categories_data','find_category_id','find_baords_id','categories_parent_data'));
  }

//   public function becometeacherCreate(Request $request)
//   {
    
//             // Store your user in database

//         $otp = rand(1000,9999);

//       $number = '6205181838';

//         $g = new Elearn();
//         $g->sendROTP($otp,$number);
//       dd($g);
      
//   }
  public function becometeacherCreate(Request $request)
  {
    $validator = Validator::make($request->all(), [ 
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'dob' => 'required',
            'gender' => 'required',
            'country' => 'required',
            'city' => 'required',
            'hig_qualification' => 'required',
            'total_exp' => 'required|numeric',
            'subject_teach' => 'required',
            'grade_teach' => 'required',
            'phone' => 'required|max:10|min:10',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'upload_cv' => 'required|max:10000|mimes:doc,docx,pdf',
            'g-recaptcha-response' => ['required', new Recaptcha()],
        ],
        $msg = [
        'first_name.required' => 'Kindly Enter First Name',
        'last_name.required' => 'Kindly Enter Last Name',
        'dob.required' => 'Kindly Enter Date of Birth',
        'gender.required' => 'Kindly Enter Gender',
        'country.required' => 'Kindly Enter Country',
        'city.required' => 'Kindly Enter City ',
        'hig_qualification.required' => 'Kindly Enter Highest Qualification',
        'total_exp.required' => 'Kindly Enter Total Experience',
        'subject_teach.required' => 'Kindly Enter Subjects you would like to teach',
        'grade_teach.required' => 'Kindly Enter Grades you would like to teach',
        'phone.required' => 'Kindly Enter Phone number',
        'phone.min' => 'Phone Number should be 10 digits',
        'phone.max' => 'Phone Number should be 10 digits',
        'email.required' => 'Kindly Enter Email',
        'upload_cv.required' => 'Kindly Upload CV',
        'upload_cv.max' => 'File Size should be less than 1MB',
        'upload_cv.mimes' => 'File Size should be PDF,Doc',
         'g-recaptcha-response.required' =>'Verify that you are not a robot.',

      ]);
        if ($validator->passes()) {
            // Store your user in database

        $otp = rand(1000,9999);

        $teacher =  new User();
        $teacher->first_name = $request->first_name;
        $teacher->middle_name = $request->middle_name;
        $teacher->last_name = $request->last_name;
        $teacher->email  = $request->email;
        $teacher->dob = $request->dob;
        $teacher->phone = $request->phone;
        $teacher->gender = $request->gender;
        $teacher->state = $request->state;
        $teacher->city = $request->city;
        $teacher->country = $request->country;
        $teacher->password = Hash::make($request->password);
        $teacher->otp = $otp;
        $teacher->save(); 


        $user = User::find($teacher->id);
          $el = new Elearn();
          $el->teacherRSMS($user->first_name,$user->phone);
          try {
          Mail::to($user)->send(new TeacherOnboardingEmail($user));
          Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New tutor onboarded"));

        }
        catch (\Exception $e) {}

        $teacherprofile = new TeacherProfile();
        $teacherprofile->user_id  = $teacher->id;
        $teacherprofile->hig_qualification = $request->hig_qualification;
        $teacherprofile->total_exp = $request->total_exp;
        $teacherprofile->relevant_exp = $request->relevant_exp;
        $teacherprofile->subject_teach = json_encode($request->subject_teach);
        $teacherprofile->grade_teach = $request->grade_teach;
        $teacherprofile->lang_proficiency = $request->lang_proficiency;
        
        if ($request->has('upload_cv')) {
            $fname=time().".".$request->upload_cv->getClientOriginalExtension();
             $request->upload_cv->move(public_path('storage/upload_cv'), $fname);
           
            $teacherprofile->upload_cv = 'storage/upload_cv/'.$fname;
        }
        $teacherprofile->save();


 

        $g = new Elearn();
        $g->sendROTP($otp,$request->phone);
        $teacherForRole = User::find($teacher->id);
        $teacherForRole->confirmed = 1;
        $teacherForRole->save();
        $teacherForRole->assignRole('teacher');
        Session::put('user_id',$teacher->id);

            if($request->redirect){
                        $redirect = $request->redirect;
                        }else{
                            
                          $redirect = '/otp';  
                        }
            return redirect($redirect)->with('success', 'Registration Sucessful.');

        }
        else{
                 $errorString = implode("<br>",$validator->messages()->all());
                 session()->flash('flash_message',$errorString);
                  return redirect(URL::previous() . "#cus-enroll-tutor")->withInput();
         }

      
  }

   protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
         $otp = rand(1000,9999);
                $user->dob = isset($data['dob']) ? $data['dob'] : NULL ;
                $user->phone = isset($data['phone']) ? $data['phone'] : NULL ;
                $user->gender = isset($data['gender']) ? $data['gender'] : NULL;
                $user->address = isset($data['address']) ? $data['address'] : NULL;
                $user->city =  isset($data['city']) ? $data['city'] : NULL;
                $user->pincode = isset($data['pincode']) ? $data['pincode'] : NULL;
                $user->state = isset($data['state']) ? $data['state'] : NULL;
                $user->country = isset($data['country']) ? $data['country'] : NULL;
                $user->otp = $otp;
                $user->save();
 $g = new Elearn();
        $g->sendROTP($otp,$data['phone']);
        $userForRole = User::find($user->id);
        $userForRole->confirmed = 1;
        $userForRole->active = 1;
        $userForRole->save();
        $userForRole->assignRole('student');
        Session::put('user_id',$user->id);
        return $user;
    }
    
   public function doregister(Request $request){
    // dd($request->all());
    
    
               $ip = request()->ip();
$location = Location::get($ip);

if($location->countryName!='India'){
    
    
    return redirect()->back();
}


      $validator = Validator::make($request->all(), [ 
            'first_name' => 'required|max:255',
            
            'last_name' => 'required|max:255',
            // 'dob' => 'required',
            'gender' => 'required|max:255',
            // 'country' => 'required|max:255',
            'phone' => 'required|max:10|min:10',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],
        $msg = [
        'first_name.required' => 'Kindly Enter First Name',
        
        'last_name.required' => 'Kindly Enter Last Name',
        'dob.required' => 'Kindly Enter Date Of Birth ',
        'gender.required' => 'Kindly Enter Your Gender',
        'country.required' => 'Kindly Enter Your Country',
        'phone.required' => 'Kindly Enter Phone number',
        'phone.min' => 'Phone Number should be 10 digits',
        'phone.max' => 'Phone Number should be 10 digits',
        'email.required' => 'Kindly Enter Email',

      ],
        [
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);
        
        $request->ip = $ip;

        if ($validator->passes()) {
            // Store your user in database
            event(new Registered($user = $this->create($request->all())));
               Mail::to($user->email)->send(new StudentRegistrationEmail($user));
           
           Mail::to(env('ADMIN_EMAIL'))->send(new AdminTutorEmail($user,"New student onboarded"));
          $el = new Elearn();
          $el->studentRSMS($user->otp,$user->phone);

            // if($request->redirect){
            //             $redirect = $request->redirect;
            //             }else{
                            
                          $redirect = '/otp';  
                        // }
            return redirect($redirect)->with('success', 'Registration Sucessful.');

        }
        else{
                 $errorString = implode("<br>",$validator->messages()->all());
                 session()->flash('flash_message',$errorString);
                  return redirect(URL::previous() . "#cus-enroll-student")->withInput();


                  // return redirect(URL::previous() . "#cus-enroll-student")
                  //   ->withErrors($validator)
                  //   ->withInput();
         }

        //   return redirect('/userregister')->with('message', 'Something went wrong.');
   }
   public function dologin(Request $request){
      $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]); 

        if($validator->passes()){
            $credentials = $request->only('email', 'password');
            $authSuccess = \Illuminate\Support\Facades\Auth::attempt($credentials, $request->has('remember'));
            if($authSuccess) {
                $request->session()->regenerate();
                // if(auth()->user()->active > 0){
                    if(auth()->user()->isAdmin()){
                        $redirect = '/user/dashboard';
                    }else{
                        if($request->redirect){
                        $redirect = $request->redirect;
                        }else{
                          $redirect = '/user';  
                        }
                        
                    }
                    return redirect()->intended($redirect)->with('successx', true);
                    // return redirect($redirect)->with('success', true);
                    
                // }else{
                //     \Illuminate\Support\Facades\Auth::logout();

               
                        
                //          return redirect('/userlogin')->with('message', 'Login failed. Account is not active');
                // }
            }else{
                
                    
                      return redirect('/userlogin')->with('message', 'Login failed. Account not found');
            }

        }


         return redirect('/userlogin')->with('message', 'Something went wrong');
  }
   public function register(Request $request){
    $red=$request->redirect;
       return view('home.register',compact('red'));
  }
    public function index()
    {
        if (request('page')) {
            $page = Page::where('slug', '=', request('page'))
                ->where('published', '=', 1)->first();
            if ($page != "") {
                return view($this->path . '.pages.index', compact('page'));
            }
            abort(404);
        }
        $type = config('theme_layout');
        $sections = Config::where('key', '=', 'layout_' . $type)->first();
        $sections = json_decode($sections->value);

        $popular_courses = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)->take(60)->get();

        $featured_courses = Course::withoutGlobalScope('filter')->where('published', '=', 1)
            ->whereHas('category')
            ->where('featured', '=', 1)->orderBy("id","desc")->get();
// dd($featured_courses);
        $course_categories = Category::with('courses')->take(20)->get();
        $courses=Course::with('category')->where('published', '=', 1)->limit(6)->get();
// dd($courses);
        $trending_courses = Course::withoutGlobalScope('filter')
            ->whereHas('category')
            ->where('published', '=', 1)
            ->where('trending', '=', 1)->take(2)->get();

        $teachers = User::role('teacher')->where('on_home', '1')->get(); 
        // dd($teachers);

        $sponsors = Sponsor::where('status', '=', 1)->get();

        $news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        $faqs = Category::with('faqs')->get()->take(6);

        $testimonials = Testimonial::where('status', '=', 1)->orderBy('created_at', 'desc')->get();

        $reasons = Reason::where('status', '=', 1)->orderBy('created_at', 'desc')->get();

        $slider = Slider::where('status', '=', 1)->orderBy('created_at', 'desc')->get();

        if ((int)config('counter') == 1) {
            $total_students = config('total_students');
            $total_courses = config('total_courses');
            $total_teachers = config('total_teachers');
        } else {
            $total_course = Course::where('published', '=', 1)->get()->count();
            $total_bundle = Bundle::where('published', '=', 1)->get()->count();
            $total_students = User::role('student')->get()->count();
            $total_courses = $total_course + $total_bundle;
            $total_teachers = User::role('teacher')->get()->count();
        }
         

        $categories = Category::where("parent","101")->where('status','1')->orderBy('sort_order','asc')->get();
        try {
            $achievement = Achievement::where('id','1')->first();
        } catch (\Exception $e) {
            $achievement = null;
        }
        try {
            $link = VideoLink::where('id','1')->first();
        } catch (\Exception $e) {
            $link = null;
        }
       // dd($testimonials);
        /*return view($this->path . '.index-' . config('theme_layout'), compact('popular_courses', 'featured_courses', 'sponsors', 'total_students', 'total_courses', 'total_teachers', 'testimonials', 'news', 'trending_courses', 'teachers', 'faqs', 'course_categories', 'reasons', 'sections','categories'));*/
        return view('welcome', compact('popular_courses', 'featured_courses', 'sponsors', 'total_students', 'total_courses', 'total_teachers', 'testimonials', 'news', 'trending_courses', 'teachers', 'faqs', 'course_categories', 'reasons', 'sections','categories','courses','slider','achievement','link'));
    }
    
    public function testimonials()
    {
        $testimonials = Testimonial::where('status', '=', 1)->orderBy('created_at', 'desc')->get();
        return view('all-testimonial',compact('testimonials'));
    }

    public function getFaqs()
    {
        $faq_categories = Category::has('faqs', '>', 0)->get();
        return view($this->path . '.faq', compact('faq_categories'));
    }


    public function ourClasses(){
        return view('our-class');
    }

    public function subscribe(Request $request)
    {
        $this->validate($request, [
            'subs_email' => 'required'
        ]);

        if (config('mail_provider') != "" && config('mail_provider') == "mailchimp") {
            try {
                if (!Newsletter::isSubscribed($request->subs_email)) {
                    if (config('mailchimp_double_opt_in')) {
                        Newsletter::subscribePending($request->subs_email);
                        session()->flash('alert', "We've sent you an email, Check your mailbox for further procedure.");
                    } else {
                        Newsletter::subscribe($request->subs_email);
                        session()->flash('alert', "You've subscribed successfully");
                    }
                    return back();
                } else {
                    session()->flash('alert', "Email already exist in subscription list");
                    return back();

                }
            } catch (Exception $e) {
                \Log::info($e->getMessage());
                session()->flash('alert', "Something went wrong, Please try again Later");
                return back();
            }

        } elseif (config('mail_provider') != "" && config('mail_provider') == "sendgrid") {
            try {
                $apiKey = config('sendgrid_api_key');
                $sg = new \SendGrid($apiKey);
                $query_params = json_decode('{"page": 1, "page_size": 1}');
                $response = $sg->client->contactdb()->recipients()->get(null, $query_params);
                if ($response->statusCode() == 200) {
                    $users = json_decode($response->body());
                    $emails = [];
                    foreach ($users->recipients as $user) {
                        array_push($emails, $user->email);
                    }
                    if (in_array($request->subs_email, $emails)) {
                        session()->flash('alert', "Email already exist in subscription list");
                        return back();
                    } else {
                        $request_body = json_decode(
                            '[{
                             "email": "' . $request->subs_email . '",
                             "first_name": "",
                             "last_name": ""
                              }]'
                        );
                        $response = $sg->client->contactdb()->recipients()->post($request_body);
                        if ($response->statusCode() != 201 || (json_decode($response->body())->new_count == 0)) {

                            session()->flash('alert', "Email already exist in subscription list");
                            return back();
                        } else {
                            $recipient_id = json_decode($response->body())->persisted_recipients[0];
                            $list_id = config('sendgrid_list');
                            $response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->_($recipient_id)->post();
                            if ($response->statusCode() == 201) {
                                session()->flash('alert', "You've subscribed successfully");
                            } else {
                                session()->flash('alert', "Check your email and try again");
                                return back();
                            }

                        }
                    }
                }
            } catch (Exception $e) {
                \Log::info($e->getMessage());
                session()->flash('alert', "Something went wrong, Please try again Later");
                return back();
            }
        }else{
            session()->flash('alert', "Please configure Newsletter from Admin");
            return back();
        }


    }

    public function getTeachers()
    {
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $teachers = User::role('teacher')->paginate(12);
        return view($this->path . '.teachers.index', compact('teachers', 'recent_news'));
    }

    public function showTeacher(Request $request)
    {
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();
        $teacher = User::role('teacher')->where('id', '=', $request->id)->first();
        $courses = $teacher->courses;
        if (count($teacher->courses) > 0) {
            $courses = $teacher->courses()->paginate(12);
        }
        return view($this->path . '.teachers.show', compact('teacher', 'recent_news', 'courses'));
    }

    public function getDownload(Request $request)
    {
        if (auth()->check()) {
            $lesson = Lesson::findOrfail($request->lesson);
            $course_id = $lesson->course_id;
            $course = Course::findOrfail($course_id);
            $purchased_course = \Auth::check() && $course->students()->where('user_id', \Auth::id())->count() > 0;
            if ($purchased_course) {
                $file = public_path() . "/storage/uploads/" . $request->filename;

                return Response::download($file);
            }
            return abort(404);

        }
        return abort(404);

    }

    public function searchCourse(Request $request)
    {

        if (request('type') == 'popular') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'trending') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'featured') {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else {
            $courses = Course::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(12);
        }


        if ($request->category != null) {
            $category = Category::find((int)$request->category);
            if($category){
                $ids = $category->courses->pluck('id')->toArray();
                $types = ['popular', 'trending', 'featured'];
                if ($category) {

                    if (in_array(request('type'), $types)) {
                        $type = request('type');
                        $courses = $category->courses()->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                            $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                        })
                            ->whereIn('id', $ids)
                            ->where('published', '=', 1)
                            ->where($type, '=', 1)
                            ->paginate(12);
                    } else {
                        $courses = $category->courses()
                            ->where(function ($query) use ($request) {
                                $query->where('title', 'LIKE', '%' . $request->q . '%');
                                $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                            })
                            ->where('published', '=', 1)
                            ->whereIn('id', $ids)
                            ->paginate(12);
                    }

                }
            }


        } else {
            $courses = Course::where('title', 'LIKE', '%' . $request->q . '%')
                ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                ->where('published', '=', 1)
                ->paginate(12);

        }

        $categories = Category::where('status', '=', 1)->orderBy('sort_order','asc')->get();


        $q = $request->q;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        return view($this->path . '.search-result.courses', compact('courses', 'q', 'recent_news', 'categories'));
    }


    public function searchBundle(Request $request)
    {

        if (request('type') == 'popular') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('popular', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'trending') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('trending', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else if (request('type') == 'featured') {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->where('featured', '=', 1)->orderBy('id', 'desc')->paginate(12);

        } else {
            $bundles = Bundle::withoutGlobalScope('filter')->where('published', 1)->orderBy('id', 'desc')->paginate(12);
        }


        if ($request->category != null) {
            $category = Category::find((int)$request->category);
            $ids = $category->bundles->pluck('id')->toArray();
            $types = ['popular', 'trending', 'featured'];
            if ($category) {

                if (in_array(request('type'), $types)) {
                    $type = request('type');
                    $bundles = $category->bundles()->where(function ($query) use ($request) {
                        $query->where('title', 'LIKE', '%' . $request->q . '%');
                        $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                    })
                        ->whereIn('id', $ids)
                        ->where('published', '=', 1)
                        ->where($type, '=', 1)
                        ->paginate(12);
                } else {
                    $bundles = $category->bundles()
                        ->where(function ($query) use ($request) {
                            $query->where('title', 'LIKE', '%' . $request->q . '%');
                            $query->orWhere('description', 'LIKE', '%' . $request->q . '%');
                        })
                        ->where('published', '=', 1)
                        ->whereIn('id', $ids)
                        ->paginate(12);
                }

            }

        } else {
            $bundles = Bundle::where('title', 'LIKE', '%' . $request->q . '%')
                ->orWhere('description', 'LIKE', '%' . $request->q . '%')
                ->where('published', '=', 1)
                ->paginate(12);

        }

        $categories = Category::where('status', '=', 1)->orderBy('sort_order','asc')->get();


        $q = $request->q;
        $recent_news = Blog::orderBy('created_at', 'desc')->take(2)->get();

        return view($this->path . '.search-result.bundles', compact('bundles', 'q', 'recent_news', 'categories'));
    }

    public function searchBlog(Request $request)
    {
        $blogs = Blog::where('title', 'LIKE', '%' . $request->q . '%')
            ->paginate(12);
        $categories = Category::has('blogs')->where('status', '=', 1)->paginate(10);
        $popular_tags = Tag::has('blogs', '>', 4)->get();


        $q = $request->q;
        return view($this->path . '.search-result.blogs', compact('blogs', 'q', 'categories', 'popular_tags'));
    }
}

