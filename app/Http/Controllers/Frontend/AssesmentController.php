<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Config;
use App\Models\Course;
use App\Models\CourseTimeline;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Page;
use App\Models\Reason;
use App\Models\Sponsor;
use App\Models\Resource;
use App\Models\ResourceList;
use App\Models\System\Session;
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
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentUser;

/**
 * Class HomeController.
 */
class AssesmentController extends Controller
{
    
    public function index(){
        
        return view('frontend.assessment.index');
        
        
    }
    
    
    
    
    
}