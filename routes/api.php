<?php

use Illuminate\Http\Request;
use App\Http\Controllers\v1\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|categories
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(['prefix' => 'v1','namespace'=>'v1', 'middleware' => 'cors'],function (){
  Route::post('sliders',[ApiController::class, 'getSliders']);
 Route::post('upload-image',[ApiController::class, 'uploadImage']);
    Route::group([
        'prefix' => 'auth', 'middleware' => 'cors'
    ], function () {

    Route::post('login', [ApiController::class, 'login']);
    Route::post('provider-login', [ApiController::class, 'loginProvider']);
        Route::post('signup-form', [ApiController::class, 'signupForm']);
        
        Route::post('signup-save', [ApiController::class, 'signup']);

        Route::group([
            'middleware' => 'auth:api'
        ], function() {

            Route::post('logout', [ApiController::class, 'logout']);
 
        }); 
    }); 
 Route::get('download',[ApiController::class, 'download']);
    Route::group(['middleware' => ['auth:api','cors']],function (){
        Route::post('registerFCM',[ApiController::class, 'registerFCM']);
        Route::post('request-call',[ApiController::class, 'requestCall']);
        Route::post('update-photo',[ApiController::class, 'updatePhoto']); 
        Route::post('get-single-doubt',[ApiController::class, 'singleDoubt']);
         Route::post('add-doubt-reponse',[ApiController::class, 'resDoubt']);
        Route::post('add-doubt',[ApiController::class, 'addDoubt']);
        Route::post('get-doubts',[ApiController::class, 'getDoubts']);
        Route::post('get-contact',[ApiController::class, 'getContact']);
        Route::post('courses',[ApiController::class, 'getCourses']);
         Route::post('otp-verify', [ApiController::class, 'otpVerify']);
       Route::post('check-coupon',[ApiController::class, 'checkCoupon']);
        Route::post('categories',[ApiController::class, 'getCategories']);
         Route::post('popular',[ApiController::class, 'getPopular']);
        Route::post('single-category',[ApiController::class, 'getSingleCategory']);
        Route::post('bundles',[ApiController::class, 'getBundles']);
        Route::post('search',[ApiController::class, 'search']);
        Route::post('latest-news',[ApiController::class, 'getLatestNews']);
        Route::post('testimonials',[ApiController::class, 'getTestimonials']);
        Route::post('teachers',[ApiController::class, 'getTeachers']);
        Route::post('single-teacher',[ApiController::class, 'getSingleTeacher']);
        Route::post('teacher-courses',[ApiController::class, 'getTeacherCourses']);
        Route::post('teacher-bundles',[ApiController::class, 'getTeacherBundles']);
        Route::post('get-faqs',[ApiController::class, 'getFaqs']);
        Route::post('why-us',[ApiController::class, 'getWhyUs']);
        Route::post('sponsors',[ApiController::class, 'getSponsors']);
        Route::post('contact-us',[ApiController::class, 'saveContactUs']);
        Route::post('single-course',[ApiController::class, 'getSingleCourse']);
        Route::post('submit-review',[ApiController::class, 'submitReview']);
        Route::post('update-review',[ApiController::class, 'updateReview']);
        Route::post('single-lesson',[ApiController::class, 'getLesson']);
        Route::post('video-progress',[ApiController::class, 'videoProgress']);
        Route::post('course-progress',[ApiController::class, 'courseProgress']);
        Route::post('generate-certificate',[ApiController::class, 'generateCertificate']);
        Route::post('single-bundle',[ApiController::class, 'getSingleBundle']);
        Route::post('add-to-cart',[ApiController::class, 'addToCart']);
        Route::post('getnow',[ApiController::class, 'getNow']);
        Route::post('remove-from-cart',[ApiController::class, 'removeFromCart']);
        Route::post('get-cart-data',[ApiController::class, 'getCartData']);
        Route::post('clear-cart',[ApiController::class, 'clearCart']);
        Route::post('payment-status',[ApiController::class, 'paymentStatus']);
        Route::post('get-blog',[ApiController::class, 'getBlog']);
        Route::post('blog-by-category',[ApiController::class, 'getBlogByCategory']);
        Route::post('blog-by-tag',[ApiController::class, 'getBlogByTag']);
        Route::post('add-blog-comment',[ApiController::class, 'addBlogComment']);
        Route::post('delete-blog-comment',[ApiController::class, 'deleteBlogComment']);
        Route::post('forum',[ApiController::class, 'getForum']);
        Route::post('create-discussion',[ApiController::class, 'createDiscussion']);
        Route::post('store-response',[ApiController::class, 'storeResponse']);
        Route::post('update-response',[ApiController::class, 'updateResponse']);
        Route::post('delete-response',[ApiController::class, 'deleteResponse']);
        Route::post('messages',[ApiController::class, 'getMessages']);
        Route::post('compose-message',[ApiController::class, 'composeMessage']);
        Route::post('reply-message',[ApiController::class, 'replyMessage']);
        Route::post('unread-messages',[ApiController::class, 'getUnreadMessages']);
        Route::post('search-messages',[ApiController::class, 'searchMessages']);
        Route::post('my-certificates',[ApiController::class, 'getMyCertificates']);
        Route::post('my-purchases',[ApiController::class, 'getMyPurchases']);
        Route::post('my-account',[ApiController::class, 'getMyAccount']);
        Route::post('my-class',[ApiController::class, 'getMyClass']);
        Route::post('my-download',[ApiController::class, 'getMyDownload']);
        Route::post('my-batch-download',[ApiController::class, 'getMyBatchDownload']);
        Route::post('my-record',[ApiController::class, 'getMyRecord']);
         Route::post('join-class',[ApiController::class, 'joinClasss']);
        Route::post('update-account',[ApiController::class, 'updateMyAccount']);
        Route::post('update-password',[ApiController::class, 'updatePassword']);
        Route::post('get-page',[ApiController::class, 'getPage']);
        Route::post('subscribe-newsletter',[ApiController::class, 'subscribeNewsletter']);
        Route::post('offers',[ApiController::class, 'getOffers']);
        Route::post('apply-coupon',[ApiController::class, 'applyCoupon']);
         Route::post('apply-coupon-new',[ApiController::class, 'applyCouponNew']);
        Route::post('remove-coupon',[ApiController::class, 'removeCoupon']);
        Route::post('order-confirmation',[ApiController::class, 'orderConfirmation']);
        Route::post('create-order',[ApiController::class, 'makeOrder']);
        Route::post('confirm-order',[ApiController::class, 'confirmOrder']);
        Route::post('change-password',[ApiController::class, 'changePassword']);
        Route::post('student-batches',[ApiController::class, 'studentClasses']);
        Route::post('mocktests',[ApiController::class, 'getMockTests']);
        Route::post('mocktest-questions',[ApiController::class, 'getMockTestQuestions']);
    });
    Route::post('send-reset-link',[ApiController::class]);
    Route::post('configs',[ApiController::class, 'getConfigs']);
    
});

