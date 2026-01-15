<?php

use Illuminate\Http\Request;

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
  Route::post('sliders','ApiController@getSliders');
 Route::post('upload-image','ApiController@uploadImage');
    Route::group([
        'prefix' => 'auth', 'middleware' => 'cors'
    ], function () {

    Route::post('login', 'ApiController@login');
    Route::post('provider-login', 'ApiController@loginProvider');
        Route::post('signup-form', 'ApiController@signupForm');
        
        Route::post('signup-save', 'ApiController@signup');

        Route::group([
            'middleware' => 'auth:api'
        ], function() {

            Route::post('logout', 'ApiController@logout');
 
        }); 
    }); 
 Route::get('download','ApiController@download');
    Route::group(['middleware' => ['auth:api','cors']],function (){
        Route::post('registerFCM','ApiController@registerFCM');
        Route::post('request-call','ApiController@requestCall');
        Route::post('update-photo','ApiController@updatePhoto'); 
        Route::post('get-single-doubt','ApiController@singleDoubt');
         Route::post('add-doubt-reponse','ApiController@resDoubt');
        Route::post('add-doubt','ApiController@addDoubt');
        Route::post('get-doubts','ApiController@getDoubts');
        Route::post('get-contact','ApiController@getContact');
        Route::post('courses','ApiController@getCourses');
         Route::post('otp-verify', 'ApiController@otpVerify');
       Route::post('check-coupon','ApiController@checkCoupon');
        Route::post('categories','ApiController@getCategories');
         Route::post('popular','ApiController@getPopular');
        Route::post('single-category','ApiController@getSingleCategory');
        Route::post('bundles','ApiController@getBundles');
        Route::post('search','ApiController@search');
        Route::post('latest-news','ApiController@getLatestNews');
        Route::post('testimonials','ApiController@getTestimonials');
        Route::post('teachers','ApiController@getTeachers');
        Route::post('single-teacher','ApiController@getSingleTeacher');
        Route::post('teacher-courses','ApiController@getTeacherCourses');
        Route::post('teacher-bundles','ApiController@getTeacherBundles');
        Route::post('get-faqs','ApiController@getFaqs');
        Route::post('why-us','ApiController@getWhyUs');
        Route::post('sponsors','ApiController@getSponsors');
        Route::post('contact-us','ApiController@saveContactUs');
        Route::post('single-course','ApiController@getSingleCourse');
        Route::post('submit-review','ApiController@submitReview');
        Route::post('update-review','ApiController@updateReview');
        Route::post('single-lesson','ApiController@getLesson');
        Route::post('video-progress','ApiController@videoProgress');
        Route::post('course-progress','ApiController@courseProgress');
        Route::post('generate-certificate','ApiController@generateCertificate');
        Route::post('single-bundle','ApiController@getSingleBundle');
        Route::post('add-to-cart','ApiController@addToCart');
        Route::post('getnow','ApiController@getNow');
        Route::post('remove-from-cart','ApiController@removeFromCart');
        Route::post('get-cart-data','ApiController@getCartData');
        Route::post('clear-cart','ApiController@clearCart');
        Route::post('payment-status','ApiController@paymentStatus');
        Route::post('get-blog','ApiController@getBlog');
        Route::post('blog-by-category','ApiController@getBlogByCategory');
        Route::post('blog-by-tag','ApiController@getBlogByTag');
        Route::post('add-blog-comment','ApiController@addBlogComment');
        Route::post('delete-blog-comment','ApiController@deleteBlogComment');
        Route::post('forum','ApiController@getForum');
        Route::post('create-discussion','ApiController@createDiscussion');
        Route::post('store-response','ApiController@storeResponse');
        Route::post('update-response','ApiController@updateResponse');
        Route::post('delete-response','ApiController@deleteResponse');
        Route::post('messages','ApiController@getMessages');
        Route::post('compose-message','ApiController@composeMessage');
        Route::post('reply-message','ApiController@replyMessage');
        Route::post('unread-messages','ApiController@getUnreadMessages');
        Route::post('search-messages','ApiController@searchMessages');
        Route::post('my-certificates','ApiController@getMyCertificates');
        Route::post('my-purchases','ApiController@getMyPurchases');
        Route::post('my-account','ApiController@getMyAccount');
        Route::post('my-class','ApiController@getMyClass');
        Route::post('my-download','ApiController@getMyDownload');
        Route::post('my-batch-download','ApiController@getMyBatchDownload');
        Route::post('my-record','ApiController@getMyRecord');
         Route::post('join-class','ApiController@joinClasss');
        Route::post('update-account','ApiController@updateMyAccount');
        Route::post('update-password','ApiController@updatePassword');
        Route::post('get-page','ApiController@getPage');
        Route::post('subscribe-newsletter','ApiController@subscribeNewsletter');
        Route::post('offers','ApiController@getOffers');
        Route::post('apply-coupon','ApiController@applyCoupon');
         Route::post('apply-coupon-new','ApiController@applyCouponNew');
        Route::post('remove-coupon','ApiController@removeCoupon');
        Route::post('order-confirmation','ApiController@orderConfirmation');
        Route::post('create-order','ApiController@makeOrder');
        Route::post('confirm-order','ApiController@confirmOrder');
        Route::post('change-password','ApiController@changePassword');
        Route::post('student-batches','ApiController@studentClasses');
    });
    Route::post('send-reset-link','ApiController');
    Route::post('configs','ApiController@getConfigs');
    
});

