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
use App\Http\Controllers\Backend\Admin\MyclassController;
use App\Http\Controllers\Backend\Admin\MarketingController;
use App\Http\Controllers\Frontend\EnquiryController;
use App\Http\Controllers\Frontend\TestSeriesController;
use App\Http\Controllers\WhiteboardController;
use App\Http\Controllers\Backend\Admin\CoursesController as AdminCoursesController;
use App\Http\Controllers\Backend\Admin\DemoController;
use App\Http\Controllers\Frontend\AssesmentController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Backend\MessagesController;
use App\Http\Controllers\Frontend\MockSeriesController;
use App\Http\Controllers\Backend\CertificateController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\BundlesController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Backend\CertificateController as FrontendCertificateController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\NotificationController;



use Illuminate\Support\Facades\Route;

/* 
 * Global Routes    
 * Routes that are used between both frontend and backend. 
 */  

// Route::get('/check-otp', [HomeController::class, 'checkOtp'])->name('home.checkOtp');
Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/class-length', [MyclassController::class, 'getLengthOfClass'])->name('home.class-length');




Route::get('test-series-purchase-cron', [TestSeriesController::class, 'purchaseCron']); 


Route::post('/ajax-login', [TestSeriesController::class, 'ajaxLogin'])->name('frontend.login.ajax');
Route::post('/ajax-register', [TestSeriesController::class, 'ajaxRegister'])->name('frontend.register.ajax');
Route::get('/buy-test/{id}', [TestSeriesController::class, 'buyTest'])->name('frontend.buyTest');

Route::get('/whiteboard/create', [WhiteboardController::class, 'createRoomAndToken']);
Route::get('/whiteboard/view', [WhiteboardController::class, 'viewWhiteboard'])->name('whiteboard.view');

Route::get('/run-attendance', [HomeController::class, 'cronAttendance'])->name('cron.attendance');
Route::get('/run-attendance-rec', [HomeController::class, 'cronAttendanceRec'])->name('cron.attendance-rec');
Route::get('/run-subs-dues', [HomeController::class, 'cronSubscriptionDue'])->name('cron.subs-dues');
Route::get('/run-feedback', [HomeController::class, 'cronFeedbackEmail'])->name('cron.feedback');

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

// FIXED: Secure file download route for training materials
Route::get('/download/training/{filename}', function ($filename) {
    $path = public_path('storage/training/' . $filename);
    
    if (!file_exists($path)) {
        abort(404, 'File not found');
    }
    
    // Check if user is authenticated
    if (!Auth::check()) {
        abort(403, 'Unauthorized');
    }
    
    return response()->file($path);
})->name('training.download')->middleware('auth');

Route::get('/our-classes', [HomeController::class, 'ourClasses'])->name('frontend.our_classes');
Route::get('/userlogin', [HomeController::class, 'login'])->name('frontend.auth.login');
Route::post('/userlogin', [HomeController::class, 'dologin']);
Route::post('/auth/google', [HomeController::class, 'google']);

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



Route::get('/demo/{id}', [DemoController::class, 'join'])->name('demo.join');


Route::get('/demo-check/{id}', [DemoController::class, 'joinCheck'])->name('demo.join.check');


Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('support-and-helpdesk', [HomeController::class, 'supportHelpdesk'])->name('support-and-helpdesk');

// Start Course Sub Category

 Route::get('category/{slug}', [HomeController::class, 'category'])->name('category');
 
 Route::get('test-series/{slug}', [TestSeriesController::class, 'category'])->name('testSeries.category');
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
Route::group(['prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {
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

Route::group(['prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {

//==== Messages Routes =====//
    Route::get('messages', [MessagesController::class, 'index'])->name('messages');
    Route::post('messages/unread', [MessagesController::class, 'getUnreadMessages'])->name('messages.unread');
    Route::post('messages/send', [MessagesController::class, 'send'])->name('messages.send');
    Route::post('messages/reply', [MessagesController::class, 'reply'])->name('messages.reply');
    
    
  

});
Route::group([ 'prefix' => 'user', 'as' => 'admin.', 'middleware' => 'auth'], function () {
    
    
   // Marketing Routes
Route::get('marketing', [MarketingController::class, 'index'])->name('marketing.index');

Route::get('marketing/list', [MarketingController::class, 'list'])->name('marketing.list');


Route::post('marketing/assign-list', [MarketingController::class, 'assignList'])->name('marketing.assign-list');


Route::post('leads', [MarketingController::class, 'storeLead'])->name('marketing.store-lead');

Route::post('campaigns', [MarketingController::class, 'storeCampaign'])->name('marketing.store-campaign');

Route::post('import-leads', [MarketingController::class, 'importLeads'])->name('marketing.import-leads');

Route::get('download-template', [MarketingController::class, 'downloadTemplate'])->name('marketing.download-template');

// Lead Management Routes
Route::get('leads', [MarketingController::class, 'leads'])->name('marketing.leads');

Route::get('leads/{lead}/edit', [MarketingController::class, 'editLead'])->name('marketing.leads.edit');

Route::put('leads/{lead}', [MarketingController::class, 'updateLead'])->name('marketing.leads.update');

Route::delete('leads/{lead}', [MarketingController::class, 'destroyLead'])->name('marketing.leads.destroy');

    
    
    
  Route::get('test-series', [TestSeriesController::class, 'index'])->name('testseries.index'); 


  Route::get('purchase/test-series', [TestSeriesController::class, 'purchaseList'])->name('testseries.purchaseList'); 


  Route::get('question/report', [TestSeriesController::class, 'questionReport'])->name('testseries.questionReport'); 


 Route::get('test-series/subjects/{id}', [TestSeriesController::class, 'subjects'])->name('testseries.subjects'); 

 Route::get('test-series/chapters/{id}', [TestSeriesController::class, 'chapters'])->name('testseries.chapters'); 


 Route::post('test-series/chapters/{id}', [TestSeriesController::class, 'chapterSave'])->name('testseries.chapters.save');

 Route::post('test-series/subjects/{id}/save', [TestSeriesController::class, 'storeSubject'])->name('subjects.store'); 

 Route::put('test-series/subjects/update/{id}', [TestSeriesController::class, 'updateSubject'])->name('subjects.update'); 


 Route::delete('test-series/subjects/{id}/delete', [TestSeriesController::class, 'destroySubject'])->name('subjects.destroy'); 


 Route::get('test-series', [TestSeriesController::class, 'index'])->name('testseries.index'); 

 Route::post('test-series', [TestSeriesController::class, 'store'])->name('test-series.store'); 
 Route::get('test-series/{id}/edit', [TestSeriesController::class, 'edit'])->name('testseries.edit'); 
 Route::put('test-series/{id}', [TestSeriesController::class, 'update'])->name('test-series.update'); 
Route::delete('test-series/{id}', [TestSeriesController::class, 'destroy'])->name('test-series.destroy'); 


 Route::get('test-series/{id}/test-list', [TestSeriesController::class, 'testList'])->name('testseries.testlist'); 

 Route::get('test-series/{id}/add-test', [TestSeriesController::class, 'addTest'])->name('testseries.add-test');
   
   
   Route::delete('test-series/{id}/delete-test', [TestSeriesController::class, 'deleteTest'])->name('testseries.delete-test');


    Route::get('test-series/{id}/edit-test', [TestSeriesController::class, 'editTest'])->name('testseries.edit-test');
   
      Route::put('test-series/{id}/edit-test', [TestSeriesController::class, 'updateTest'])->name('testseries.update-test');
   
   
   
   
    Route::post('test-series/{id}/add-test', [TestSeriesController::class, 'saveTest'])->name('testseries.add-test');



});


// Mock Series Routes
Route::group(['middleware' => ['auth', 'permission:lesson_create'], 'prefix' => 'user'], function () {
    
    Route::get('mock', [MockSeriesController::class, 'index'])->name('mockseries.index'); 
    
    Route::post('mock-series', [MockSeriesController::class, 'store'])->name('mock-series.store'); 
    
    Route::get('mock-series/{id}/edit', [MockSeriesController::class, 'edit'])->name('mockseries.edit'); 
    
    Route::put('mock-series/{id}', [MockSeriesController::class, 'update'])->name('mock-series.update'); 
    
    Route::delete('mock-series/{id}', [MockSeriesController::class, 'destroy'])->name('mock-series.destroy'); 
    
    Route::get('mock-series/{id}/test-list', [MockSeriesController::class, 'testList'])->name('mockseries.testlist'); 
    
    Route::get('mock-series/{id}/add-test', [MockSeriesController::class, 'addTest'])->name('mockseries.add-test-form');
    
    Route::post('mock-series/{id}/add-test', [MockSeriesController::class, 'saveTest'])->name('mockseries.save-test');
    
    Route::get('mock-series/{id}/subjects', [MockSeriesController::class, 'subjects'])->name('mockseries.subjects');
    
    Route::post('mock-series/{id}/subjects/store', [MockSeriesController::class, 'storeSubject'])->name('mockseries.subjects.store');
    
    Route::put('mock-series/subjects/update/{id}', [MockSeriesController::class, 'updateSubject'])->name('mockseries.subjects.update');
    
    Route::delete('mock-series/subjects/{id}', [MockSeriesController::class, 'destroySubject'])->name('mockseries.subjects.destroy');
    
    Route::get('mock-series/subjects/{id}/chapters', [MockSeriesController::class, 'chapters'])->name('mockseries.chapters');
    
    Route::post('mock-series/subjects/{id}/chapters/store', [MockSeriesController::class, 'storeChapters'])->name('mockseries.chapters.store');
    
    Route::delete('mock-series/{id}/delete-test', [MockSeriesController::class, 'deleteTest'])->name('mockseries.delete-test');
    
    Route::get('mock-series/{id}/edit-test', [MockSeriesController::class, 'editTest'])->name('mockseries.edit-test');
    
    Route::put('mock-series/{id}/edit-test', [MockSeriesController::class, 'updateTest'])->name('mockseries.update-test');

});


Route::post('pay-confirm-test/{id}', [TestSeriesController::class, 'payConfirm'])->name('test.payConfirm');
Route::get('/purchase/success', [TestSeriesController::class, 'paySuccess'])->name('test.paySuccess'); 
Route::group([ 'middleware' => 'auth'], function () {
    
    Route::post('user/report-question', [TestSeriesController::class, 'reportQuestion'])->name('question.report'); 


    Route::get('user/feedback/{id}', [HomeController::class, 'feedback'])->name('feedback');
    Route::get('user/demo-feedback/{id}', [HomeController::class, 'demoFeedback'])->name('demo-feedback');
    Route::post('user/demo-feedback/{id}', [HomeController::class, 'demofeedbackCreate'])->name('demo-feedback-create');
Route::post('user/feedback/{id}', [HomeController::class, 'feedbackCreate'])->name('feedback.store');
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

Route::get('user/my-courses', [MyclassController::class, 'myCourses'])->name('student.courses');
Route::get('user/classes/{slug}', [MyclassController::class, 'studentClasses'])->name('classes.show'); 
Route::get('user/waiting-area/{id}', [MyclassController::class, 'waitingArea'])->name('classes.waitingArea');
Route::get('user/find-meeting', [MyclassController::class, 'meetingLink'])->name('classes.meetingLink');
 
Route::get('user/lession-progress/{id}', [MyclassController::class, 'lessionProgress'])->name('lession-progress'); 
Route::get('user/getLaunch/{id}/{meetid}', [MyclassController::class, 'joinClasss'])->name('myclass.slaunch');

Route::get('user/waiting/{id}', [MyclassController::class, 'waiting'])->name('myclass.waiting');
Route::get('user/check-waiting/{id}', [MyclassController::class, 'checkWaiting'])->name('myclass.checkWaiting');

Route::get('user/getDemoLaunch/{id}/{meetid}', [MyclassController::class, 'joinDemoClasss'])->name('myclass.demoslaunch');
Route::get('user/downloads/{id}/', [MyclassController::class, 'Downloads'])->name('myclass.sdownloads');
Route::get('user/pastClass/{id}/', [MyclassController::class, 'pastClasses'])->name('myclass.pastclass');
Route::get('user/commitment/{id}/', [MyclassController::class, 'commitment'])->name('myclass.commitment');

Route::post('user/commitment-objection', [MyclassController::class, 'storeObjection'])->name('myclass.commitment.objection');

Route::get('user/assignments/{id}/', [MyclassController::class, 'assignments'])->name('myclass.assignments');
Route::get('user/assignments-upload/{id}/{bid}', [MyclassController::class, 'assignmentsUpload'])->name('myclass.assignmentsupload');
Route::post('user/assignments-upload/{id}/{bid}', [MyclassController::class, 'assignmentsUploadSave'])->name('myclass.assignmentsuploadsave');


Route::get('user/sexams/{id}/', [MyclassController::class, 'exams'])->name('myclass.exams');
Route::get('user/exams-upload/{id}/{bid}', [MyclassController::class, 'examsUpload'])->name('myclass.examuploads');
Route::post('user/exams-upload/{id}/{bid}', [MyclassController::class, 'examsUploadSave'])->name('myclass.examsuploadsave');

// Live Exam Tracking Route
Route::get('user/track/exam', [MyclassController::class, 'runningStatusExam'])->name('myclass.trackLiveExam');

 Route::get('user/student-test/{id}', [MyclassController::class, 'testPages'])->name('student-test');
 
  Route::get('user/exam/waiting/{batch}/{user}/{test}/{mytest}', [MyclassController::class, 'waitingExam'])->name('student-test-waiting');
  
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

// Live Exam Tracking - Ping Pong Route
Route::post('user/ping-pong', [TestSeriesController::class, 'pingPong'])->name('exam.pingPong');

// Test Series Routes
Route::get('user/my-test', [TestSeriesController::class, 'myTestSeries'])->name('myTestSeries.index');
Route::get('user/my-test/{id}', [TestSeriesController::class, 'myTestList'])->name('myTestSeries.list');
Route::get('user/my-test/{id}/{tsid}/attempt', [TestSeriesController::class, 'myAttempt'])->name('myTestSeries.myAttempt');
Route::get('user/attempt/{id}', [TestSeriesController::class, 'startExam'])->name('myTestSeries.startExam');
Route::get('user/take/{id}', [TestSeriesController::class, 'takeExam'])->name('myTestSeries.takeExam');
Route::post('user/submit-exam', [TestSeriesController::class, 'submitExam'])->name('myTestSeries.submitExam');
Route::get('user/exam/thank-you', [TestSeriesController::class, 'thankYou'])->name('myTestSeries.thankYou');
Route::get('user/exam/result/{id}', [TestSeriesController::class, 'examResult'])->name('myTestSeries.examResult');


//shruti
// List lessons based on Test Series


Route::get('test-series/{testSeries}/study-material', [TestSeriesController::class, 'studyMaterial'])
     ->name('testSeries.study-material');

// List lesson files
Route::get('lesson/{lesson}/materials', [TestSeriesController::class, 'lessonMaterials'])
     ->name('lesson.materials');


Route::get('/test-series/{id}/videos', [TestSeriesController::class, 'videos'])
    ->name('testSeries.videos');

Route::get('/lesson/{lessonId}/videos', [TestSeriesController::class, 'lessonVideos'])
    ->name('lesson.videos');
    
    
    

// Mock Test Series Routes (attempt route must be before list so /x/y/attempt is not matched as list /x)
Route::get('user/my-mock-series', [MockSeriesController::class, 'myMockSeries'])->name('myMockSeries.index');
Route::get(
    'user/mock-exam/waiting/{mock_id}/{batch_mock_test_id}',
    [MockSeriesController::class, 'waitingMockExam']
    )->name('myMockSeries.waiting');
Route::get('user/my-mock-series/{mock_id}/{batch_mock_test_id}/attempt', [MockSeriesController::class, 'myMockAttempt'])->name('myMockSeries.myAttempt');
Route::get('user/my-mock-series/{id}', [MockSeriesController::class, 'myMockList'])->name('myMockSeries.list');
Route::get('user/mock-attempt/{id}', [MockSeriesController::class, 'startMockExam'])->name('myMockSeries.startExam');
Route::get('user/mock-exam/{id}', [MockSeriesController::class, 'takeMockExam'])->name('myMockSeries.takeExam');
Route::post('user/submit-mock-exam', [MockSeriesController::class, 'submitMockExam'])->name('myMockSeries.submitExam');
Route::get('user/mock-thank-you', [MockSeriesController::class, 'mockThankYou'])->name('myMockSeries.thankYou');

// Tutor Mock Test Routes
Route::group(['prefix' => 'user/tutor/mocktests', 'as' => 'tutor.mocktests.', 'middleware' => ['auth', 'role:teacher']], function () {
    Route::get('available', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'availableTests'])->name('available');
    Route::get('preview/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'previewTest'])->name('preview');
    Route::post('approve/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'approveTest'])->name('approve');
    Route::get('schedule/{id}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduleForm'])->name('schedule_form');
    Route::post('schedule', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduleTest'])->name('schedule');
    Route::get('scheduled', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'scheduledTests'])->name('scheduled');
    Route::get('reschedule/{scheduleId}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'rescheduleForm'])->name('reschedule_form');
    Route::post('reschedule', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'rescheduleTest'])->name('reschedule');
    Route::get('batch-results/{scheduleId}', [\App\Http\Controllers\Backend\Tutor\TutorMockTestController::class, 'batchResults'])->name('batch_results');
});

// Student Mock Test Routes
Route::group(['prefix' => 'user/student/mocktests', 'as' => 'student.mocktests.', 'middleware' => ['auth', 'role:student|administrator']], function () {
    Route::get('/', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'dashboard'])->name('index');
    Route::get('dashboard', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'dashboard'])->name('dashboard');
    Route::get('attempt/{scheduleId}', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'attemptTest'])->name('attempt');
    Route::post('submit', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'submitTest'])->name('submit');
    Route::get('result/{resultId}', [\App\Http\Controllers\Frontend\StudentMockTestController::class, 'viewResult'])->name('result');
});


});

// PUBLIC ROUTES: Mock exam result and answer key accessible without authentication
Route::get('user/mock-exam-result/{id}', [MockSeriesController::class, 'mockExamResult'])->name('myMockSeries.result');
Route::get('user/mock-exam-answer-key/{id}', [MockSeriesController::class, 'mockExamAnswerKey'])->name('myMockSeries.answerKey');

Route::post('payment/success', [CoursesController::class, 'successPay'])->name('courses.successPay');
Route::post('payment/fail', [CoursesController::class, 'failedPay'])->name('courses.failedPay');
Route::get('certificates', [FrontendCertificateController::class, 'getCertificates'])->name('certificates.index');
Route::post('certificates/generate', [FrontendCertificateController::class, 'generateCertificate'])->name('certificates.generate');

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

Route::get('courses-checkout/apply-coupon', [CoursesController::class, 'checkoutCoupon'])->name('courses.checkoutCoupon');

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


// shruti


Route::get('study-material/{id}', [MyclassController::class, 'studyMaterial'])
    ->name('study-material');

Route::get('/user/view-material/{lesson_id}/{batch_id}', [MyclassController::class, 'viewMaterial'])
    ->name('view-material');

Route::get('/lesson-pdf/{id}', [MyclassController::class, 'viewPdf'])
    ->name('lesson.pdf.view');
    
    
    

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
    Route::post('cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
    Route::post('cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');
    Route::post('cart/stripe-payment', [CartController::class, 'stripePayment'])->name('cart.stripe.payment');
    Route::post('cart/paypal-payment', [CartController::class, 'paypalPayment'])->name('cart.paypal.payment');
    Route::get('cart/paypal-payment/status', [CartController::class, 'getPaymentStatus'])->name('cart.paypal.status');

    Route::get('status', function () {
        return view('frontend.cart.status');
    })->name('status');
    Route::post('cart/offline-payment', [CartController::class, 'offlinePayment'])->name('cart.offline.payment');
    Route::post('cart/getnow', [CartController::class, 'getNow'])->name('cart.getnow');
});

//============= Menu  Manager Routes ===============//
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'middleware' => config('menu.middleware')], function () {
    //Route::get('wmenuindex', array('uses'=>'\Harimayco\Menu\Controllers\MenuController@wmenuindex'));
    Route::post('add-custom-menu', [MenuController::class, 'addcustommenu'])->name('haddcustommenu');
    Route::post('delete-item-menu', [MenuController::class, 'deleteitemmenu'])->name('hdeleteitemmenu');
    Route::post('delete-menug', [MenuController::class, 'deletemenug'])->name('hdeletemenug');
    Route::post('create-new-menu', [MenuController::class, 'createnewmenu'])->name('hcreatenewmenu');
    Route::post('generate-menu-control', [MenuController::class, 'generatemenucontrol'])->name('hgeneratemenucontrol');
    Route::post('update-item', [MenuController::class, 'updateitem'])->name('hupdateitem');
    Route::post('save-custom-menu', [MenuController::class, 'saveCustomMenu'])->name('hcustomitem');
    Route::post('change-location', [MenuController::class, 'updateLocation'])->name('update-location');
});

Route::get('certificate-verification', [CertificateController::class, 'getVerificationForm'])->name('frontend.certificates.getVerificationForm');
Route::post('certificate-verification', [CertificateController::class, 'verifyCertificate'])->name('frontend.certificates.verify');
Route::get('certificates/download', [CertificateController::class, 'download'])->name('certificates.download');


if(config('show_offers') == 1){
    Route::get('offers', [CartController::class, 'getOffers'])->name('frontend.offers');
}
 Route::get('test-cron-missed', [MyclassController::class, 'testMissed'])->name('student-test-missed');
 
 
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
    Route::get('/{page?}', [HomeController::class, 'index'])->name('index');
});

