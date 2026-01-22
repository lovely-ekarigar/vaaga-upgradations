<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Board;
use App\Models\Category;
use App\Models\Config;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\General;
use App\Models\Reason;
use App\Models\Sponsor;
use App\Models\Slider;
use App\Models\Feedback;
// use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Newsletter;
use Auth;
use Illuminate\Support\Facades\Validator;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Auth\Events\Registered;
use Hash;
use Mail;
use Illuminate\Support\Facades\Password;
use App\Models\TeacherProfile;
use Session;
/**
 * 
 * Class HomeController.
 */
class FeedbackController extends Controller
{
    
     public function index(){

        $feedback_list = Feedback::with('batch')->with('user')->orderBy('id','desc')->get();
       
        return view('backend.feedback.index',compact('feedback_list'));
  }
  
  
   
}

