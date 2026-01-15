<?php

use App\Http\Controllers\LanguageController;
use App\Http\Controllers\LessonsController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ResourceController;
use App\Http\Controllers\CoursesController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Frontend\Auth\ForgotPasswordController;
use App\Http\Controllers\Frontend\Auth\ResetPasswordController;
use App\Http\Controllers\Backend\Admin\TeamController;
use App\Http\Controllers\Backend\Admin\NoteController;
use App\Http\Controllers\Backend\Admin\NoteCategoryController;
use App\Http\Controllers\Frontend\EnquiryController;
/* 
 * Global Routes    
 * Routes that are used between both frontend and backend. 
 */  

// Route::get('/check-otp', 'Frontend\HomeController@checkOtp')->name('home.checkOtp');
Route::get('/', 'Frontend\HomeController@index')->name('home.index');
Route::get('/run-attendance', 'Frontend\HomeController@cronAttendance')->name('home.index');
Route::get('/run-attendance-rec', 'Frontend\HomeController@cronAttendanceRec')->name('home.index');
Route::get('/run-subs-dues', 'Frontend\HomeController@cronSubscriptionDue')->name('home.index');
Route::get('/run-feedback', 'Frontend\HomeController@cronFeedbackEmail')->name('home.index');

Route::post('/demo-request-home', 'CoursesController@demoRequestHome')->name('home.demorequest'); 
 
Route::get('/get-demo-course', 'CoursesController@demoCourse')->name('home.democourse');

Route::get('/testimonials', 'Frontend\HomeController@testimonials')->name('home.testimonials');

// Enquriy route
Route::post('/enquiry-form', 'Frontend\EnquiryController@submitForm')->name('home.submitForm');
// Route::get('/',function(){ echo "Hello"; });
Route::get('/affiliate', 'AffiliateController@index')->name('aff.index');
Route::get('/affiliate/register', 'AffiliateController@register')->name('aff.register');
Route::post('/affiliate/register', 'AffiliateController@vregister')->name('aff.pregister');
Route::get('/affiliate/login', 'AffiliateController@login')->name('aff.login');
Route::post('/affiliate/login', 'AffiliateController@vlogin')->name('aff.vlogin');
Route::get('/affiliate/forgot', 'AffiliateController@forgot')->name('aff.forgot');
Route::get('/affiliate/logout', 'AffiliateController@logout')->name('aff.logout');
Route::get('/affiliate/earnings', 'AffiliateController@earnings')->name('aff.earnings');
Route::get('/affiliate/withdrawl', 'AffiliateController@withdrawl')->name('aff.withdrawl');
Route::post('/affiliate/withdrawl', 'AffiliateController@vwithdrawl')->name('aff.vwithdrawl');
Route::get('/affiliate/bank', 'AffiliateController@bank')->name('aff.bank');
Route::post('/affiliate/bank', 'AffiliateController@vbank')->name('aff.vbank');
Route::get('/affiliate/edit-profile', 'AffiliateController@edit')->name('aff.edit-profile');
Route::post('/affiliate/edit-profile', 'AffiliateController@vedit')->name('aff.vedit-profile');


Route::get('/usertraining', 'Frontend\HomeController@userTraining')->name('frontend.userTraining');

Route::get('/our-classes', 'Frontend\HomeController@ourClasses')->name('frontend.our_classes');
Route::get('/userlogin', 'Frontend\HomeController@login')->name('frontend.auth.login');
Route::post('/userlogin', 'Frontend\HomeController@dologin');
Route::get('/userregister', 'Frontend\HomeController@register')->name('home.register');
Route::post('/userregister', 'Frontend\HomeController@doregister');
Route::get('/become-tutor', 'Frontend\HomeController@becometeacherRegister');
Route::post('/become-tutor', 'Frontend\HomeController@becometeacherCreate');
Route::get('/otp', 'Frontend\HomeController@otp');
Route::post('/otp', 'Frontend\HomeController@sendotp');
Route::get('/forgot/password', 'Frontend\HomeController@forgotPassword');
Route::post('/forgot/password', 'Frontend\HomeController@setForgotPassword');

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset/{token}', [ResetPasswordController::class, 'reset'])->name('frontend.auth.password.reset');

Route::get('our-teams',[TeamController::class, 'ourTeams'])->name('frontend.ourTeams');
Route::get('team/{slug}',[TeamController::class, 'teamDeatails'])->name('frontend.teamDeatails');

Route::get('note-categories',[NoteController::class, 'categories'])->name('frontend.note.categories');
Route::get('note/category/{slug}',[NoteController::class, 'notes'])->name('frontend.note.categoryDetails');





Route::get('/about', 'Frontend\HomeController@about')->name('about');
Route::get('support-and-helpdesk', 'Frontend\HomeController@supportHelpdesk')->name('support-and-helpdesk');

// Start Course Sub Category

 Route::get('category/{slug}', 'Frontend\HomeController@category')->name('category');
 Route::get('academic/{slug}/{cat}', 'Frontend\HomeController@academicFind')->name('academic');
 Route::get('academic-course/{board}/{slug}', 'Frontend\HomeController@academicCourse')->name('academic-course');

// End Course Sub Category 

Route::get('/assetments', 'Frontend\AssesmentController@index');


Route::get('/resource/{id}', 'Frontend\ResourceController@index');

// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('frontend.auth.password.reset.form');
// Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('reset');
//     Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('frontend.auth.password.reset');
// Switch between the included languages    
Route::get('lang/{lang}', [LanguageController::class, 'swap']);

Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('frontend.password.email.post');

Route::get('/config-clear', function() {
		 Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('storage:link');
			});
Route::get('/sitemap-' .str_slug(config('app.name')) . '/{file?}', 'SitemapController@index');


//Route to clean up demo site
Route::get('reset-demo',function (){
    ini_set('memory_limit', '-1');
    ini_set('max_execution_time', 1000);
    try{
        \Illuminate\Support\Facades\Artisan::call('refresh:site');
        return 'Refresh successful!';
    }catch (\Exception $e){
        return $e->getMessage();
    }

});



/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    include_route_files(__DIR__ . '/frontend/');
});

/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    /*
     * These routes need view-backend permission
     * (good if you want to allow more than one group in the backend,
     * then limit the backend features by different roles or permissions)
     *
     * Note: Administrator has all permissions so you do not have to specify the administrator role everywhere.
     * These routes can not be hit if the password is expired
     */
    include_route_files(__DIR__ . '/backend/');
});

Route::group(['namespace' => 'Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {

//==== Messages Routes =====//
    Route::get('messages', ['uses' => 'MessagesController@index', 'as' => 'messages']);
    Route::post('messages/unread', ['uses' => 'MessagesController@getUnreadMessages', 'as' => 'messages.unread']);
    Route::post('messages/send', ['uses' => 'MessagesController@send', 'as' => 'messages.send']);
    Route::post('messages/reply', ['uses' => 'MessagesController@reply', 'as' => 'messages.reply']);
});


Route::group([ 'middleware' => 'auth'], function () {
    Route::get('user/feedback/{id}', 'Frontend\HomeController@feedback')->name('feedback');
    Route::get('user/demo-feedback/{id}', 'Frontend\HomeController@demoFeedback')->name('demo-feedback');
    Route::post('user/demo-feedback/{id}', 'Frontend\HomeController@demofeedbackCreate')->name('demo-feedback-create');
Route::post('user/feedback/{id}', 'Frontend\HomeController@feedbackCreate')->name('feedback');
    Route::get('user/resource', ['uses' => 'Frontend\ResourceController@admin', 'as' => 'admin.resource.index']);
    Route::get('thank-you', ['uses' => 'CoursesController@thankYou', 'as' => 'purchase.thank']);
Route::get('user/resource/create', ['uses' => 'Frontend\ResourceController@create', 'as' => 'admin.resource.create']);
Route::post('user/resource/create', ['uses' => 'Frontend\ResourceController@save', 'as' => 'admin.resource.save']);
Route::get('user/content', ['uses' => 'Backend\Admin\CoursesController@listContent', 'as' => 'admin.content.index']);
Route::post('user/content', ['uses' => 'Backend\Admin\CoursesController@updateContent', 'as' => 'admin.content.update']);
Route::get('user/content/sort/{id}/{sort}', ['uses' => 'Backend\Admin\CoursesController@contentSortOrder', 'as' => 'admin.content.sortOrder']);
Route::get('user/lesson/sort/{id}/{sort}', ['uses' => 'Backend\Admin\CoursesController@lessonSortOrder', 'as' => 'admin.lesson.sortOrder']);
Route::get('note/{slug}',[NoteController::class, 'noteDeatails'])->name('frontend.note.noteDeatails');
Route::get('download/{id}',[NoteController::class, 'download'])->name('frontend.note.download');

Route::get('user/content/create', ['uses' => 'Backend\Admin\CoursesController@createContent', 'as' => 'admin.content.create']);
Route::post('user/content/create', ['uses' => 'Backend\Admin\CoursesController@saveContent', 'as' => 'admin.content.save']);
Route::get('user/content/delete', ['uses' => 'Backend\Admin\CoursesController@deleteContent', 'as' => 'admin.content.delete']);
Route::get('user/download/{cid}/{mid}', ['uses' => 'CoursesController@download', 'as' => 'user.content.download']);
Route::get('/recordings', 'CoursesController@recordings');
Route::get('/runclass', 'LessonsController@runClass');

   Route::post('order', ['uses' => 'CoursesController@order', 'as' => 'courses.order']);
Route::post('completeOrder', ['uses' => 'CoursesController@completeOrder', 'as' => 'courses.completeOrder']);

Route::get('user/classes/{slug}', ['uses' => 'Backend\Admin\MyclassController@studentClasses', 'as' => 'classes.show']); 

Route::get('user/lession-progress/{id}', ['uses' => 'Backend\Admin\MyclassController@lessionProgress'])->name('lession-progress'); 
Route::get('user/getLaunch/{id}/{meetid}', ['uses' => 'Backend\Admin\MyclassController@joinClasss', 'as' => 'myclass.slaunch']);
Route::get('user/getDemoLaunch/{id}/{meetid}', ['uses' => 'Backend\Admin\MyclassController@joinDemoClasss', 'as' => 'myclass.demoslaunch']);
Route::get('user/downloads/{id}/', ['uses' => 'Backend\Admin\MyclassController@Downloads', 'as' => 'myclass.sdownloads']);
Route::get('user/pastClasses/{id}/', ['uses' => 'Backend\Admin\MyclassController@pastClasses', 'as' => 'myclass.pastclass']);
Route::get('user/assignments/{id}/', ['uses' => 'Backend\Admin\MyclassController@assignments', 'as' => 'myclass.assignments']);
Route::get('user/assignments-upload/{id}/{bid}', ['uses' => 'Backend\Admin\MyclassController@assignmentsUpload', 'as' => 'myclass.assignmentsupload']);
Route::post('user/assignments-upload/{id}/{bid}', ['uses' => 'Backend\Admin\MyclassController@assignmentsUploadSave', 'as' => 'myclass.assignmentsuploadsave']);


Route::get('user/sexams/{id}/', ['uses' => 'Backend\Admin\MyclassController@exams', 'as' => 'myclass.exams']);
Route::get('user/exams-upload/{id}/{bid}', ['uses' => 'Backend\Admin\MyclassController@examsUpload', 'as' => 'myclass.examuploads']);
Route::post('user/exams-upload/{id}/{bid}', ['uses' => 'Backend\Admin\MyclassController@examsUploadSave', 'as' => 'myclass.examsuploadsave']);

 Route::get('user/student-test/{id}', ['uses' => 'Backend\Admin\MyclassController@testPages'])->name('student-test');
 Route::get('user/test-result-analysis/{id}', ['uses' => 'Backend\Admin\MyclassController@testanalysis'])->name('student-test-analysis');
 Route::get('user/attempt-test/{id}', ['uses' => 'Backend\Admin\MyclassController@attemptTest'])->name('student-test-attempt');
 Route::post('user/submit-test', ['uses' => 'Backend\Admin\MyclassController@submitTest'])->name('student-test-submit');
Route::get('courses/{slug}/buy', ['uses' => 'CoursesController@checkout', 'as' => 'courses.checkout']); 
// Route::post('courses/{slug}/buy', ['uses' => 'CoursesController@checkoutPay', 'as' => 'courses.checkout']);
Route::post('courses/{slug}/buy', ['uses' => 'CoursesController@order', 'as' => 'courses.checkout']);
Route::get('pay/{ref_id}', ['uses' => 'CoursesController@pay', 'as' => 'courses.pay']);
Route::post('pay-confirm/{ref_id}/{type}', ['uses' => 'CoursesController@payConfirm', 'as' => 'courses.payConfirm']);
// Route::get('payment/success/{ref_id}', ['uses' => 'CoursesController@successPay', 'as' => 'courses.successPay']);

Route::post('user/renew-subscription', ['uses' => 'CoursesController@renew', 'as' => 'subscription.renew']); 
Route::get('user/my-notifications', 'Backend\NotificationController@myNotifications')->name('myNotifications.index');

});
Route::post('payment/success', ['uses' => 'CoursesController@successPay', 'as' => 'courses.successPay']);
Route::post('payment/fail', ['uses' => 'CoursesController@failedPay', 'as' => 'courses.failedPay']);
Route::get('certificates', 'Backend\CertificateController@getCertificates')->name('certificates.index');
Route::post('certificates/generate', 'Backend\CertificateController@generateCertificate')->name('certificates.generate');

Route::get('category/{category}/blogs', 'BlogController@getByCategory')->name('blogs.category');
Route::get('tag/{tag}/blogs', 'BlogController@getByTag')->name('blogs.tag');
Route::get('blog/{slug?}', 'BlogController@getIndex')->name('blogs.index');
Route::post('blog/{id}/comment', 'BlogController@storeComment')->name('blogs.comment');
Route::get('blog/comment/delete/{id}', 'BlogController@deleteComment')->name('blogs.comment.delete');

Route::get('teachers', 'Frontend\HomeController@getTeachers')->name('teachers.index');
Route::get('teachers/{id}/show', 'Frontend\HomeController@showTeacher')->name('teachers.show');


Route::post('newsletter/subscribe', 'Frontend\HomeController@subscribe')->name('subscribe');
   
//============Course Routes=================//
Route::get('courses', ['uses' => 'CoursesController@all', 'as' => 'courses.all']);
Route::get('categories', ['uses' => 'CoursesController@categoryPage', 'as' => 'courses.categorypage']);
Route::post('serach-course', ['uses' => 'CoursesController@search', 'as' => 'courses.search']);

Route::get('courses/{slug}', ['uses' => 'CoursesController@show', 'as' => 'courses.show']);

Route::post('courses/{slug}', ['uses' => 'CoursesController@demoRequest', 'as' => 'courses.demo']);

Route::get('courses-checkout/apply-coupon',['uses' => 'CoursesController@checkoutCoupon','as'=>'courses.checkoutCoupon']);

// Route::get('details', ['uses' => 'CoursesController@details', 'as' => 'courses.details']);

Route::get('course/study/{slug}/{les}', ['uses' => 'CoursesController@study', 'as' => 'courses.study']);
Route::get('course/study/{slug}', ['uses' => 'CoursesController@study', 'as' => 'courses.study']);


//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('course/{course_id}/rating', ['uses' => 'CoursesController@rating', 'as' => 'courses.rating']);
Route::get('category/{category}/courses', ['uses' => 'CoursesController@getByCategory', 'as' => 'courses.category']);
Route::post('courses/{id}/review', ['uses' => 'CoursesController@addReview', 'as' => 'courses.review']);
Route::get('courses/review/{id}/edit', ['uses' => 'CoursesController@editReview', 'as' => 'courses.review.edit']);
Route::post('courses/review/{id}/edit', ['uses' => 'CoursesController@updateReview', 'as' => 'courses.review.update']);
Route::get('courses/review/{id}/delete', ['uses' => 'CoursesController@deleteReview', 'as' => 'courses.review.delete']);





//============Bundle Routes=================//
Route::get('bundles', ['uses' => 'BundlesController@all', 'as' => 'bundles.all']);
Route::get('bundle/{slug}', ['uses' => 'BundlesController@show', 'as' => 'bundles.show']);
//Route::post('course/payment', ['uses' => 'CoursesController@payment', 'as' => 'courses.payment']);
Route::post('bundle/{bundle_id}/rating', ['uses' => 'BundlesController@rating', 'as' => 'bundles.rating']);
Route::get('category/{category}/bundles', ['uses' => 'BundlesController@getByCategory', 'as' => 'bundles.category']);
Route::post('bundles/{id}/review', ['uses' => 'BundlesController@addReview', 'as' => 'bundles.review']);
Route::get('bundles/review/{id}/edit', ['uses' => 'BundlesController@editReview', 'as' => 'bundles.review.edit']);
Route::post('bundles/review/{id}/edit', ['uses' => 'BundlesController@updateReview', 'as' => 'bundles.review.update']);
Route::get('bundles/review/{id}/delete', ['uses' => 'BundlesController@deleteReview', 'as' => 'bundles.review.delete']);


Route::group(['middleware' => 'auth'], function () {
    Route::get('lesson/{course_id}/{slug}/', ['uses' => 'LessonsController@show', 'as' => 'lessons.show']);
    Route::post('lesson/{slug}/test', ['uses' => 'LessonsController@test', 'as' => 'lessons.test']);
    Route::post('lesson/{slug}/retest', ['uses' => 'LessonsController@retest', 'as' => 'lessons.retest']);
    Route::post('video/progress', 'LessonsController@videoProgress')->name('update.videos.progress');
    Route::post('lesson/progress', 'LessonsController@courseProgress')->name('update.course.progress');
});

Route::get('/search', [HomeController::class, 'searchCourse'])->name('search');
Route::get('/search-course', [HomeController::class, 'searchCourse'])->name('search-course');
Route::get('/search-bundle', [HomeController::class, 'searchBundle'])->name('search-bundle');
Route::get('/search-blog', [HomeController::class, 'searchBlog'])->name('blogs.search');


Route::get('/faqs', 'Frontend\HomeController@getFaqs')->name('faqs');


/*=============== Theme blades routes ends ===================*/
Route::group(['middleware' => ['web']], function () {


Route::get('contact', 'Frontend\ContactController@index')->name('contact');
Route::post('contact/send', 'Frontend\ContactController@send')->name('contact.send'); 
});

Route::get('download', ['uses' => 'Frontend\HomeController@getDownload', 'as' => 'download']);

Route::group(['middleware' => 'auth'], function () {
    Route::post('cart/checkout', ['uses' => 'CartController@checkout', 'as' => 'cart.checkout']);
    Route::post('cart/add', ['uses' => 'CartController@addToCart', 'as' => 'cart.addToCart']);
    Route::get('cart', ['uses' => 'CartController@index', 'as' => 'cart.index']);
    Route::get('cart/clear', ['uses' => 'CartController@clear', 'as' => 'cart.clear']);
    Route::get('cart/remove', ['uses' => 'CartController@remove', 'as' => 'cart.remove']);
    Route::post('cart/apply-coupon',['uses' => 'CartController@applyCoupon','as'=>'cart.applyCoupon']);
    Route::post('cart/remove-coupon',['uses' => 'CartController@removeCoupon','as'=>'cart.removeCoupon']);
    Route::post('cart/stripe-payment', ['uses' => 'CartController@stripePayment', 'as' => 'cart.stripe.payment']);
    Route::post('cart/paypal-payment', ['uses' => 'CartController@paypalPayment', 'as' => 'cart.paypal.payment']);
    Route::get('cart/paypal-payment/status', ['uses' => 'CartController@getPaymentStatus'])->name('cart.paypal.status');

    Route::get('status', function () {
        return view('frontend.cart.status');
    })->name('status');
    Route::post('cart/offline-payment', ['uses' => 'CartController@offlinePayment', 'as' => 'cart.offline.payment']);
    Route::post('cart/getnow',['uses'=>'CartController@getNow','as' =>'cart.getnow']);
});

//============= Menu  Manager Routes ===============//
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => config('menu.middleware')], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    Route::post('add-custom-menu', 'MenuController@addcustommenu')->name('haddcustommenu');
    Route::post('delete-item-menu', 'MenuController@deleteitemmenu')->name('hdeleteitemmenu');
    Route::post('delete-menug', 'MenuController@deletemenug')->name('hdeletemenug');
    Route::post('create-new-menu', 'MenuController@createnewmenu')->name('hcreatenewmenu');
    Route::post('generate-menu-control', 'MenuController@generatemenucontrol')->name('hgeneratemenucontrol');
    Route::post('update-item', 'MenuController@updateitem')->name('hupdateitem');
    Route::post('save-custom-menu', 'MenuController@saveCustomMenu')->name('hcustomitem');
    Route::post('change-location', 'MenuController@updateLocation')->name('update-location');
});

Route::get('certificate-verification','Backend\CertificateController@getVerificationForm')->name('frontend.certificates.getVerificationForm');
Route::post('certificate-verification','Backend\CertificateController@verifyCertificate')->name('frontend.certificates.verify');
Route::get('certificates/download', ['uses' => 'Backend\CertificateController@download', 'as' => 'certificates.download']);


if(config('show_offers') == 1){
    Route::get('offers',['uses' => 'CartController@getOffers', 'as' => 'frontend.offers']);
}

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/{page?}', [HomeController::class, 'index'])->name('index');
});
// Auth::routes(['verify' => true]); // Commented out - using custom frontend auth routes instead

