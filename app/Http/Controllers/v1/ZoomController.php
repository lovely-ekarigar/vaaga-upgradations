<?php

namespace App\Http\Controllers\v1;

use App\Helpers\General\EarningHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Frontend\User\UpdatePasswordRequest;
use App\Http\Requests\Frontend\User\UpdateProfileRequest;
use App\Mail\Frontend\Contact\SendContact;
use App\Mail\OfflineOrderMail;
use App\Models\Auth\Traits\SendUserPasswordReset;
use App\Models\Auth\User;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\Doubt;
use App\Models\DoubtResponse;
use App\Models\Bundle;
use App\Models\Category;
use App\Models\Certificate;
use App\Models\Config;
use App\Models\Contact;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\CourseContent;
use App\Models\Faq;
use App\Models\Lesson;
use App\Models\Media;
use App\Models\Order;
use App\Models\Page;
use App\Models\Reason;
use App\Models\Slider;
use App\Models\Review;
use App\Models\Sponsor;
use App\Models\System\Session;
use App\Models\Tag;
use App\Models\Tax;
use App\Models\Test;
use App\Models\Testimonial;
use App\Models\VideoProgress;
use App\Models\Batch;
use App\Models\BatchUpload;
use App\Models\TeacherBatch;
use App\Models\StudentTeacherBatch;
use App\Models\StudentJoin;
use App\Models\Recording;
use App\Models\Elearn;
use App\Models\Affiliate;
use App\Repositories\Frontend\Auth\UserRepository;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Carbon\Carbon;
use DevDojo\Chatter\Events\ChatterAfterNewResponse;
use DevDojo\Chatter\Events\ChatterBeforeNewDiscussion;
use DevDojo\Chatter\Events\ChatterBeforeNewResponse;
use DevDojo\Chatter\Mail\ChatterDiscussionUpdated;
use Harimayco\Menu\Models\MenuItems;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Cart;
use DevDojo\Chatter\Events\ChatterAfterNewDiscussion;
use Event;
use DevDojo\Chatter\Models\Models;
use DevDojo\Chatter\Helpers\ChatterHelper as Helper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Purifier;
use Messenger;
use Newsletter;


class ZoomController extends Controller
{
    use FileUploadTrait;
    use SendsPasswordResetEmails;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


}