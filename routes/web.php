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
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Backend\MessagesController;
use App\Http\Controllers\Backend\Admin\CoursesController as AdminCoursesController;
use App\Http\Controllers\Backend\Admin\MyclassController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\CertificateController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BundlesController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\AssesmentController;
/* 
 * Global Routes    
 * Routes that are used between both frontend and backend. 
 */  

// Route::get('/check-otp', [HomeController::class, 'checkOtp'])->name('home.checkOtp');
Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/run-attendance', [HomeController::class, 'cronAttendance'])->name('home.index');
Route::get('/run-attendance-rec', [HomeController::class, 'cronAttendanceRec'])->name('home.index');
Route::get('/run-subs-dues', [HomeController::class, 'cronSubscriptionDue'])->name('home.index');
Route::get('/run-feedback', [HomeController::class, 'cronFeedbackEmail'])->name('home.index');

Route::post('/demo-request-home', [CoursesController::class, 'demoRequestHome'])->name('home.demorequest'); 
 
Route::get('/get-demo-course', [CoursesController::class, 'demoCourse'])->name('home.democourse');

Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('home.testimonials');

// Enquriy route
Route::post('/enquiry-form', [EnquiryController::class, 'submitForm'])->name('home.submitForm');
// Route::get('/',function(){ echo "Hello"; });
Route::get('/affiliate', [AffiliateController::class, 'index'])->name('aff.index');
Route::get('/affiliate/register', [AffiliateController::class, 'register'])->name('aff.register');
Route::post('/affiliate/register', [AffiliateController::class, 'vregister'])->name('aff.pregister');
Route::get('/affiliate/login', [AffiliateController::class, 'login'])->name('aff.login');
Route::post('/affiliate/login', [AffiliateController::class, 'vlogin'])->name('aff.vlogin');
Route::get('/affiliate/forgot', [AffiliateController::class, 'forgot'])->name('aff.forgot');
Route::get('/affiliate/logout', [AffiliateController::class, 'logout'])->name('aff.logout');
Route::get('/affiliate/earnings', [AffiliateController::class, 'earnings'])->name('aff.earnings');
Route::get('/affiliate/withdrawl', [AffiliateController::class, 'withdrawl'])->name('aff.withdrawl');
Route::post('/affiliate/withdrawl', [AffiliateController::class, 'vwithdrawl'])->name('aff.vwithdrawl');
Route::get('/affiliate/bank', [AffiliateController::class, 'bank'])->name('aff.bank');
Route::post('/affiliate/bank', [AffiliateController::class, 'vbank'])->name('aff.vbank');
Route::get('/affiliate/edit-profile', [AffiliateController::class, 'edit'])->name('aff.edit-profile');
Route::post('/affiliate/edit-profile', [AffiliateController::class, 'vedit'])->name('aff.vedit-profile');


Route::get('/usertraining', [HomeController::class, 'userTraining'])->name('frontend.userTraining');

Route::get('/our-classes', [HomeController::class, 'ourClasses'])->name('frontend.our_classes');
Route::get('/userlogin', [HomeController::class, 'login'])->name('frontend.auth.login');
Route::post('/userlogin', [HomeController::class, 'dologin']);
Route::get('/userregister', [HomeController::class, 'register'])->name('home.register');
Route::post('/userregister', [HomeController::class, 'doregister']);
Route::get('/become-tutor', [HomeController::class, 'becometeacherRegister']);
Route::post('/become-tutor', [HomeController::class, 'becometeacherCreate']);
Route::get('/otp', [HomeController::class, 'otp']);
Route::post('/otp', [HomeController::class, 'sendotp']);
Route::get('/forgot/password', [HomeController::class, 'forgotPassword']);
Route::post('/forgot/password', [HomeController::class, 'setForgotPassword']);

Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset/{token}', [ResetPasswordController::class, 'reset'])->name('frontend.auth.password.reset');

Route::get('our-teams',[TeamController::class, 'ourTeams'])->name('frontend.ourTeams');
Route::get('team/{slug}',[TeamController::class, 'teamDeatails'])->name('frontend.teamDeatails');

Route::get('note-categories',[NoteController::class, 'categories'])->name('frontend.note.categories');
Route::get('note/category/{slug}',[NoteController::class, 'notes'])->name('frontend.note.categoryDetails');





Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('support-and-helpdesk', [HomeController::class, 'supportHelpdesk'])->name('support-and-helpdesk');

// Start Course Sub Category

 Route::get('category/{slug}', [HomeController::class, 'category'])->name('category');
 Route::get('academic/{slug}/{cat}', [HomeController::class, 'academicFind'])->name('academic');
 Route::get('academic-course/{board}/{slug}', [HomeController::class, 'academicCourse'])->name('academic-course');

// End Course Sub Category 

Route::get('/assetments', [AssesmentController::class, 'index']);


Route::get('/resource/{id}', [ResourceController::class, 'index']);

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
Route::get('/sitemap-' .str_slug(config('app.name')) . '/{file?}', [SitemapController::class, 'index']);


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
Route::group(['namespace' => 'App\Http\Controllers\Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {
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

Route::group(['namespace' => 'App\Http\Controllers\Backend', 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {

//==== Messages Routes =====//
    Route::get('messages', [MessagesController::class, 'index'])->name('messages');
    Route::post('messages/unread', [MessagesController::class, 'getUnreadMessages'])->name('messages.unread');
    Route::post('messages/send', [MessagesController::class, 'send'])->name('messages.send');
    Route::post('messages/reply', [MessagesController::class, 'reply'])->name('messages.reply');
});


Route::group([ 'middleware' => 'auth'], function () {
    Route::get('user/feedback/{id}', [HomeController::class, 'feedback'])->name('feedback');
    Route::get('user/demo-feedback/{id}', [HomeController::class, 'demoFeedback'])->name('demo-feedback');
    Route::post('user/demo-feedback/{id}', [HomeController::class, 'demofeedbackCreate'])->name('demo-feedback-create');
Route::post('user/feedback/{id}', [HomeController::class, 'feedbackCreate'])->name('feedback');
    Route::get('user/resource', [ResourceController::class, 'admin'])->name('admin.resource.index');
    Route::get('thank-you', [CoursesController::class, 'thankYou'])->name('purchase.thank');
Route::get('user/resource/create', [ResourceController::class, 'create'])->name('admin.resource.create');
Route::post('user/resource/create', [ResourceController::class, 'save'])->name('admin.resource.save');
Route::get('user/content', [AdminCoursesController::class, 'listContent'])->name('admin.content.index');
Route::post('user/content', [AdminCoursesController::class, 'updateContent'])->name('admin.content.update');
Route::get('user/content/sort/{id}/{sort}', [AdminCoursesController::class, 'contentSortOrder'])->name('admin.content.sortOrder');
Route::get('user/lesson/sort/{id}/{sort}', [AdminCoursesController::class, 'lessonSortOrder'])->name('admin.lesson.sortOrder');
Route::get('note/{slug}',[NoteController::class, 'noteDeatails'])->name('frontend.note.noteDeatails');
Route::get('download/{id}',[NoteController::class, 'download'])->name('frontend.note.download');

Route::get('user/content/create', [AdminCoursesController::class, 'createContent'])->name('admin.content.create');
Route::post('user/content/create', [AdminCoursesController::class, 'saveContent'])->name('admin.content.save');
Route::get('user/content/delete', [AdminCoursesController::class, 'deleteContent'])->name('admin.content.delete');
Route::get('user/download/{cid}/{mid}', [CoursesController::class, 'download'])->name('user.content.download');
Route::get('/recordings', [CoursesController::class, 'recordings']);
Route::get('/runclass', [LessonsController::class, 'runClass']);

   Route::post('order', [CoursesController::class, 'order'])->name('courses.order');
Route::post('completeOrder', [CoursesController::class, 'completeOrder'])->name('courses.completeOrder');

Route::get('user/classes/{slug}', [MyclassController::class, 'studentClasses'])->name('classes.show'); 

Route::get('user/lession-progress/{id}', [MyclassController::class, 'lessionProgress'])->name('lession-progress'); 
Route::get('user/getLaunch/{id}/{meetid}', [MyclassController::class, 'joinClasss'])->name('myclass.slaunch');
Route::get('user/getDemoLaunch/{id}/{meetid}', [MyclassController::class, 'joinDemoClasss'])->name('myclass.demoslaunch');
Route::get('user/downloads/{id}/', [MyclassController::class, 'Downloads'])->name('myclass.sdownloads');
Route::get('user/pastClasses/{id}/', [MyclassController::class, 'pastClasses'])->name('myclass.pastclass');
Route::get('user/assignments/{id}/', [MyclassController::class, 'assignments'])->name('myclass.assignments');
Route::get('user/assignments-upload/{id}/{bid}', [MyclassController::class, 'assignmentsUpload'])->name('myclass.assignmentsupload');
Route::post('user/assignments-upload/{id}/{bid}', [MyclassController::class, 'assignmentsUploadSave'])->name('myclass.assignmentsuploadsave');


Route::get('user/sexams/{id}/', [MyclassController::class, 'exams'])->name('myclass.exams');
Route::get('user/exams-upload/{id}/{bid}', [MyclassController::class, 'examsUpload'])->name('myclass.examuploads');
Route::post('user/exams-upload/{id}/{bid}', [MyclassController::class, 'examsUploadSave'])->name('myclass.examsuploadsave');

 Route::get('user/student-test/{id}', [MyclassController::class, 'testPages'])->name('student-test');
 Route::get('user/test-result-analysis/{id}', [MyclassController::class, 'testanalysis'])->name('student-test-analysis');
 Route::get('user/attempt-test/{id}', [MyclassController::class, 'attemptTest'])->name('student-test-attempt');
 Route::post('user/submit-test', [MyclassController::class, 'submitTest'])->name('student-test-submit');
Route::get('courses/{slug}/buy', [CoursesController::class, 'checkout'])->name('courses.checkout'); 
// Route::post('courses/{slug}/buy', [CoursesController::class, 'checkoutPay'])->name('courses.checkout');
Route::post('courses/{slug}/buy', [CoursesController::class, 'order'])->name('courses.checkout');
Route::get('pay/{ref_id}', [CoursesController::class, 'pay'])->name('courses.pay');
Route::post('pay-confirm/{ref_id}/{type}', [CoursesController::class, 'payConfirm'])->name('courses.payConfirm');
// Route::get('payment/success/{ref_id}', [CoursesController::class, 'successPay'])->name('courses.successPay');

Route::post('user/renew-subscription', [CoursesController::class, 'renew'])->name('subscription.renew'); 
Route::get('user/my-notifications', [NotificationController::class, 'myNotifications'])->name('myNotifications.index');

// Tutor Mock Test Routes
Route::group(['prefix' => 'user/tutor', 'as' => 'tutor.', 'middleware' => ['auth', 'role:teacher']], function () {
    Route::get('mocktests/available', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'availableTests'])->name('mocktests.available');
    Route::get('mocktests/scheduled', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduledTests'])->name('mocktests.scheduled');
    Route::get('mocktests/preview/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'previewTest'])->name('mocktests.preview');
    Route::post('mocktests/approve/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'approveTest'])->name('mocktests.approve');
    Route::get('mocktests/schedule/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduleForm'])->name('mocktests.schedule_form');
    Route::post('mocktests/schedule', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduleTest'])->name('mocktests.schedule');
    Route::get('mocktests/reschedule/{scheduleId}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'rescheduleForm'])->name('mocktests.reschedule_form');
    Route::post('mocktests/reschedule', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'rescheduleTest'])->name('mocktests.reschedule');
    Route::get('mocktests/batch-results/{scheduleId}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'batchResults'])->name('mocktests.batch_results');
    Route::post('mocktests/report-question', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'reportQuestion'])->name('mocktests.report_question');
});

// Student Mock Test Routes
Route::group(['prefix' => 'user/student', 'as' => 'student.', 'middleware' => ['auth', 'role:student']], function () {
    Route::get('mocktests', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'dashboard'])->name('mocktests.dashboard');
    Route::get('mocktests/attempt/{scheduleId}', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'attemptTest'])->name('mocktests.attempt');
    Route::post('mocktests/submit', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'submitTest'])->name('mocktests.submit');
    Route::get('mocktests/result/{resultId}', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'viewResult'])->name('mocktests.result');
});


});
Route::post('payment/success', [CoursesController::class, 'successPay'])->name('courses.successPay');
Route::post('payment/fail', [CoursesController::class, 'failedPay'])->name('courses.failedPay');
Route::get('certificates', [CertificateController::class, 'getCertificates'])->name('certificates.index');
Route::post('certificates/generate', [CertificateController::class, 'generateCertificate'])->name('certificates.generate');

Route::get('category/{category}/blogs', [BlogController::class, 'getByCategory'])->name('blogs.category');
Route::get('tag/{tag}/blogs', [BlogController::class, 'getByTag'])->name('blogs.tag');
Route::get('blog/{slug?}', [BlogController::class, 'getIndex'])->name('blogs.index');
Route::post('blog/{id}/comment', [BlogController::class, 'storeComment'])->name('blogs.comment');
Route::get('blog/comment/delete/{id}', [BlogController::class, 'deleteComment'])->name('blogs.comment.delete');

Route::get('teachers', [HomeController::class, 'getTeachers'])->name('teachers.index');
Route::get('teachers/{id}/show', [HomeController::class, 'showTeacher'])->name('teachers.show');


Route::post('newsletter/subscribe', [HomeController::class, 'subscribe'])->name('subscribe');
   
//============Course Routes=================//
Route::get('courses', [CoursesController::class, 'all'])->name('courses.all');
Route::get('categories', [CoursesController::class, 'categoryPage'])->name('courses.categorypage');
Route::post('serach-course', [CoursesController::class, 'search'])->name('courses.search');

Route::get('courses/{slug}', [CoursesController::class, 'show'])->name('courses.show');

Route::post('courses/{slug}', [CoursesController::class, 'demoRequest'])->name('courses.demo');

Route::get('courses-checkout/apply-coupon',[CoursesController::class, 'checkoutCoupon'])->name('courses.checkoutCoupon');

// Route::get('details', [CoursesController::class, 'details'])->name('courses.details');

Route::get('course/study/{slug}/{les}', [CoursesController::class, 'study'])->name('courses.study');
Route::get('course/study/{slug}', [CoursesController::class, 'study'])->name('courses.study');


//Route::post('course/payment', [CoursesController::class, 'payment'])->name('courses.payment');
Route::post('course/{course_id}/rating', [CoursesController::class, 'rating'])->name('courses.rating');
Route::get('category/{category}/courses', [CoursesController::class, 'getByCategory'])->name('courses.category');
Route::post('courses/{id}/review', [CoursesController::class, 'addReview'])->name('courses.review');
Route::get('courses/review/{id}/edit', [CoursesController::class, 'editReview'])->name('courses.review.edit');
Route::post('courses/review/{id}/edit', [CoursesController::class, 'updateReview'])->name('courses.review.update');
Route::get('courses/review/{id}/delete', [CoursesController::class, 'deleteReview'])->name('courses.review.delete');





//============Bundle Routes=================//
Route::get('bundles', [BundlesController::class, 'all'])->name('bundles.all');
Route::get('bundle/{slug}', [BundlesController::class, 'show'])->name('bundles.show');
//Route::post('course/payment', [CoursesController::class, 'payment'])->name('courses.payment');
Route::post('bundle/{bundle_id}/rating', [BundlesController::class, 'rating'])->name('bundles.rating');
Route::get('category/{category}/bundles', [BundlesController::class, 'getByCategory'])->name('bundles.category');
Route::post('bundles/{id}/review', [BundlesController::class, 'addReview'])->name('bundles.review');
Route::get('bundles/review/{id}/edit', [BundlesController::class, 'editReview'])->name('bundles.review.edit');
Route::post('bundles/review/{id}/edit', [BundlesController::class, 'updateReview'])->name('bundles.review.update');
Route::get('bundles/review/{id}/delete', [BundlesController::class, 'deleteReview'])->name('bundles.review.delete');


Route::group(['middleware' => 'auth'], function () {
    Route::get('lesson/{course_id}/{slug}/', [LessonsController::class, 'show'])->name('lessons.show');
    Route::post('lesson/{slug}/test', [LessonsController::class, 'test'])->name('lessons.test');
    Route::post('lesson/{slug}/retest', [LessonsController::class, 'retest'])->name('lessons.retest');
    Route::post('video/progress', [LessonsController::class, 'videoProgress'])->name('update.videos.progress');
    Route::post('lesson/progress', [LessonsController::class, 'courseProgress'])->name('update.course.progress');
});

Route::get('/search', [HomeController::class, 'searchCourse'])->name('search');
Route::get('/search-course', [HomeController::class, 'searchCourse'])->name('search-course');
Route::get('/search-bundle', [HomeController::class, 'searchBundle'])->name('search-bundle');
Route::get('/search-blog', [HomeController::class, 'searchBlog'])->name('blogs.search');


Route::get('/faqs', [HomeController::class, 'getFaqs'])->name('faqs');


/*=============== Theme blades routes ends ===================*/
Route::group(['middleware' => ['web']], function () {


Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send'); 
});

Route::get('download', [HomeController::class, 'getDownload'])->name('download');

Route::group(['middleware' => 'auth'], function () {
    Route::post('cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('cart/add', [CartController::class, 'addToCart'])->name('cart.addToCart');
    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('cart/apply-coupon',[CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('cart/remove-coupon',[CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
    Route::post('cart/stripe-payment', [CartController::class, 'stripePayment'])->name('cart.stripe.payment');
    Route::post('cart/paypal-payment', [CartController::class, 'paypalPayment'])->name('cart.paypal.payment');
    Route::get('cart/paypal-payment/status', [CartController::class, 'getPaymentStatus'])->name('cart.paypal.status');

    Route::get('status', function () {
        return view('frontend.cart.status');
    })->name('status');
    Route::post('cart/offline-payment', [CartController::class, 'offlinePayment'])->name('cart.offline.payment');
    Route::post('cart/getnow',[CartController::class, 'getNow'])->name('cart.getnow');
});

//============= Menu  Manager Routes ===============//
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => config('menu.middleware')], function () {
    //Route::get('wmenuindex', [\Harimayco\Menu\Controllers\MenuController::class, 'wmenuindex']);
    Route::post('add-custom-menu', [MenuController::class, 'addcustommenu'])->name('haddcustommenu');
    Route::post('delete-item-menu', [MenuController::class, 'deleteitemmenu'])->name('hdeleteitemmenu');
    Route::post('delete-menug', [MenuController::class, 'deletemenug'])->name('hdeletemenug');
    Route::post('create-new-menu', [MenuController::class, 'createnewmenu'])->name('hcreatenewmenu');
    Route::post('generate-menu-control', [MenuController::class, 'generatemenucontrol'])->name('hgeneratemenucontrol');
    Route::post('update-item', [MenuController::class, 'updateitem'])->name('hupdateitem');
    Route::post('save-custom-menu', [MenuController::class, 'saveCustomMenu'])->name('hcustomitem');
    Route::post('change-location', [MenuController::class, 'updateLocation'])->name('update-location');
});

Route::get('certificate-verification',[CertificateController::class, 'getVerificationForm'])->name('frontend.certificates.getVerificationForm');
Route::post('certificate-verification',[CertificateController::class, 'verifyCertificate'])->name('frontend.certificates.verify');
Route::get('certificates/download', [CertificateController::class, 'download'])->name('certificates.download');


if(config('show_offers') == 1){
    Route::get('offers',[CartController::class, 'getOffers'])->name('frontend.offers');
}

Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/{page?}', [HomeController::class, 'index'])->name('index');
});
// Auth::routes(['verify' => true]); // Commented out - using custom frontend auth routes instead

