<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Auth\User\AccountController;
use App\Http\Controllers\Backend\Auth\User\ProfileController;
use \App\Http\Controllers\Backend\Auth\User\UserPasswordController;
use \App\Http\Controllers\Backend\Auth\User\VideoLinkController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Backend\StudentController;

use App\Http\Controllers\Backend\Admin\DemoController;
use App\Http\Controllers\Backend\Admin\NoteController;
use App\Http\Controllers\Backend\Admin\TeamController;
use App\Http\Controllers\Backend\Admin\NoteCategoryController;
use App\Http\Controllers\Backend\Admin\OrderController;
use App\Http\Controllers\Backend\EnquiryController;
use App\Http\Controllers\Backend\BtoBController;
/*
 * All route names are prefixed with 'admin.'.
 */

//===== General Routes =====//
Route::redirect('/', '/user/dashboard', 301);
Route::group(['middleware' => 'isverified'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
// route::post('/tutor/details'[DashboardController::class,'Details']);


Route::get('btob-users', [BtoBController::class, 'users'])->name('btob.user.lists');



Route::group(['middleware' => 'role:teacher|administrator|author'], function () {
    Route::resource('orders', '\App\Http\Controllers\Backend\Admin\OrderController');

    //===== Demo Request Routes =====//
    Route::get('demo-requests-teacher', [DemoController::class, 'indexTeacher'])->name('demo_requests_teacher');

    Route::get('update-sort', ['uses' => 'Admin\CategoriesController@updateSort', 'as' => 'update_sort']);
    Route::get('demo-requests', [DemoController::class, 'index'])->name('demo_requests');
    Route::post('demo-requests', [DemoController::class, 'scheduleDemo'])->name('demo_requests_post');
    Route::get('get-demo-requests-data', [DemoController::class, 'getData'])->name('demo_requests.get_data');
    Route::get('get-demo-requests-data-teacher', [DemoController::class, 'getDataTeacher'])->name('demo_requests.get_data_teacher');

    Route::post('demo-requests/status-update', [DemoController::class, 'statusUpdate'])->name('demo_requests_status_update');

    Route::get('teacher-course-list', ['uses' => 'Admin\TeachersController@teachercourseList'])->name('teacher-course-list');
    Route::get('teacher-student-list', ['uses' => 'Admin\TeachersController@teacherstudentList'])->name('teacher-student-list');

    Route::get('teacher-attendance', ['uses' => 'Admin\TeachersController@teacherattendanceList'])->name('teacher_attendance');
    Route::get('teacher-fees/{id}', ['uses' => 'Admin\TeachersController@teacherFees'])->name('teacher_fees');
    Route::post('teacher-fees/{id}', ['uses' => 'Admin\TeachersController@teacherFeescreate'])->name('teacher_fees');
    Route::get('teacher-fees-delete/{id}', ['uses' => 'Admin\TeachersController@teacherFeesdelete'])->name('teacher_fees_delete');
    // Route::get('teacher-attendance-create', ['uses' => 'Admin\TeachersController@teacherattendanceCreate'])->name('teacher_attendance_create');
    // Route::post('teacher-attendance-store', ['uses' => 'Admin\TeachersController@teacherattendanceStore'])->name('teacher_attendance_store');
    Route::post('teacher-bank-details-create', ['uses' => 'Admin\TeachersController@teacherbankdetailStore'])->name('teacher_bank_details_create');
    Route::post('teacher-document-approof-create', ['uses' => 'Admin\TeachersController@teacherdocumentapproofStore'])->name('teacher_document_approof_create');
    Route::post('teacher-ppt-video-store', ['uses' => 'Admin\TeachersController@teacherpptVideoStore'])->name('teacher-ppt-video-store');
    Route::get('delete/{id}', ['uses' => 'Admin\TeachersController@teacherpptVideoDelete'])->name('teacher-ppt-delete');

    Route::get('batch/batch-progress-list/{id}', ['uses' => 'Admin\BatchController@batchprogressList'])->name('batch-progress-list');

    Route::get('batch-progress-list-teacher/{id}', ['uses' => 'Admin\BatchController@batchprogressteacherList'])->name('batch-progress-list-teacher');
});
Route::group(['middleware' => 'role:administrator'], function () {




    // enquiry route
    Route::get('enquiry-list', ['uses' => 'EnquiryController@index', 'as' => 'endquiryIndex'])->name('admin.endquiryIndex');
    Route::get('enquiry-edit/{id}', ['uses' => 'EnquiryController@edit', 'as' => 'endquiryEdit']);
    Route::get('store-enquiry', ['uses' => 'EnquiryController@store'])->name('admin.store.enquiry');




    // end enquiry route
    Route::get('notifications-list', ['uses' => 'NotificationController@index', 'as' => 'notifications']);
    Route::get('notifications-create', ['uses' => 'NotificationController@create', 'as' => 'create_notification']);
    Route::post('notifications-create', ['uses' => 'NotificationController@save', 'as' => 'save_notification']);
    Route::post('notifications-delete', ['uses' => 'NotificationController@destroy', 'as' => 'delete_notification']);

    Route::get('trainings-list', ['uses' => 'Admin\TrainingController@index'])->name('trainings');
    Route::post('trainings-list-store', ['uses' => 'Admin\TrainingController@Store'])->name('training-store');
    Route::get('trainings-list-delete/{id}', ['uses' => 'Admin\TrainingController@delete'])->name('training-delete');


    Route::get('demo-history/{id}', [DemoController::class, 'demoHistory'])->name('demo_history');
    Route::get('demo-feedback-list/{id}', [DemoController::class, 'demoFeedback'])->name('demo_feedback_list');
    Route::post('demo-feedback-list/{id}', [DemoController::class, 'senddemoEmail'])->name('demo_feedback_list');

    //===== Teachers Routes =====//
    Route::resource('teachers', 'Admin\TeachersController');
    Route::get('teachers-availability', ['uses' => 'Admin\TeachersController@courseAvailability', 'as' => 'teachers_course_availability']);

    Route::get('teachers/u/delete/{id}', ['uses' => 'Admin\TeachersController@rmUnavail', 'as' => 'teachers_availability_rm']);
    Route::get('teachers/{id}/availability', ['uses' => 'Admin\TeachersController@availability', 'as' => 'teachers_availability']);
    Route::post('teachers/{id}/availability', ['uses' => 'Admin\TeachersController@markUnavailability', 'as' => 'teachers_availability']);
    Route::get('teachers/{id}/availability/edit', ['uses' => 'Admin\TeachersController@editAvailability', 'as' => 'teachers_editavailability']);
    Route::post('teachers/{id}/availability/edit', ['uses' => 'Admin\TeachersController@updateAvailability', 'as' => 'teachers_editavailability']);
    Route::get('get-teachers-data', ['uses' => 'Admin\TeachersController@getData', 'as' => 'teachers.get_data']);
    Route::post('teachers_mass_destroy', ['uses' => 'Admin\TeachersController@massDestroy', 'as' => 'teachers.mass_destroy']);
    Route::post('teachers_restore/{id}', ['uses' => 'Admin\TeachersController@restore', 'as' => 'teachers.restore']);
    Route::delete('teachers_perma_del/{id}', ['uses' => 'Admin\TeachersController@perma_del', 'as' => 'teachers.perma_del']);
    Route::post('teacher/status', ['uses' => 'Admin\TeachersController@updateStatus', 'as' => 'teachers.status']);
    Route::get('teacher-batch-list/{id}', ['uses' => 'Admin\TeachersController@teacherBatchlist'])->name('teacher_batch_list');
    Route::get('teacher-ppt-list/{id}', ['uses' => 'Admin\TeachersController@teacherPptlist'])->name('teacher-ppt-list');
    Route::get('teacher-payment-list/{id}', ['uses' => 'Admin\TeachersController@teacherWisepayment'])->name('teacher_wise_payment');
    Route::post('teacher-payment-list/{id}', ['uses' => 'Admin\TeachersController@teacherWisepaymentRequest'])->name('teacher_wise_payment_request');
    Route::get('teacher-payments', ['uses' => 'Admin\TeachersController@teacherPaymentslist'])->name('teacher_payments');
    Route::get('teacher-payments-create', ['uses' => 'Admin\TeachersController@teacherPaymentscreate'])->name('teacher_payment_create');
    Route::post('teacher-payments-store', ['uses' => 'Admin\TeachersController@teacherPaymentstore'])->name('teacher_payments_store');
    Route::get('teacher-payment-destroy/{id}', ['uses' => 'Admin\TeachersController@teacherPaymentdestroy'])->name('teacher_payment_destroy');
    Route::get('teacher-payment-edit/{id}', ['uses' => 'Admin\TeachersController@teacherPaymentdedit'])->name('teacher_payment_edit');
    Route::post('teacher-payment-edit/{id}', ['uses' => 'Admin\TeachersController@teacherPaymentupdate'])->name('teacher_payment_edit');





    Route::get('payments-stat', ['uses' => 'PaymentController@stats'])->name('teacher_payments_stats');



    Route::get('students', ['uses' => 'StudentController@index'])->name('students.index');
    Route::get('students/orders/{id}', ['uses' => 'StudentController@orders'])->name('students.orders');
    Route::get('students/{id}', ['uses' => 'StudentController@updatestatus'])->name('students.updatestatus');

    Route::get('student-recover/{id}', ['uses' => 'StudentController@studentRecover'])->name('students_recover');
    Route::get('student-show/{id}', ['uses' => 'StudentController@studentShow'])->name('students_show');
    Route::get('student-edit/{id}', ['uses' => 'StudentController@studentEdit'])->name('students_edit');
    Route::post('student-edit/{id}', ['uses' => 'StudentController@studentUpdate'])->name('students_edit');
    Route::get('student-delete/{id}', ['uses' => 'StudentController@studentDelete'])->name('students_delete');

    Route::get('student_batch_list/{id}', ['uses' => 'StudentController@studentBatchlist'])->name('student_batch_list');


    Route::get('feedback-list', ['uses' => 'Admin\FeedbackController@index'])->name('feedback-list');

    //===== FORUMS Routes =====//
    Route::resource('forums-category', 'Admin\ForumController');
    Route::get('forums-category/status/{id}', 'Admin\ForumController@status')->name('forums-category.status');


    //===== Orders Routes =====//
    Route::get('subscription-reports-details/{id}', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@subscriptionDetails', 'as' => 'subscription.detailsInfo']);
    Route::post('subscription-reports-details/{id}', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@triggerEmail', 'as' => 'subscription.triggerEmail']);
    Route::get('subscription-reports', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@subscriptionReports', 'as' => 'subscription.report']);
    Route::get('gst-reports', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@gstReport', 'as' => 'gst.report']);
    Route::get('subscription-reports-data', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@subscriptionReportsData', 'as' => 'subscription.report_data']);
    Route::get('subscriptions', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@subscriptions', 'as' => 'subscription.index']);
    Route::get('get-subscriptions-data', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@getDataSubscription', 'as' => 'subscription.get_data']);
    Route::get('get-orders-data', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@getData', 'as' => 'orders.get_data']);

    Route::get('view-invoice/{oid}/{type}', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@viewInvoice']);
    Route::post('orders_mass_destroy', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@massDestroy', 'as' => 'orders.mass_destroy']);
    Route::post('orders/complete', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@complete', 'as' => 'orders.complete']);
    Route::delete('orders_perma_del/{id}', ['uses' => '\App\Http\Controllers\Backend\Admin\OrderController@perma_del', 'as' => 'orders.perma_del']);

    //===Batch Routes===//
    Route::get('batches', ['uses' => 'Admin\BatchController@index', 'as' => 'batch']);
    Route::post('batches/onesignal', ['uses' => 'Admin\BatchController@onesignal', 'as' => 'onesignal']);
    Route::get('batch/create', ['uses' => 'Admin\BatchController@create', 'as' => 'batch.create']);
    Route::post('batch/create', ['uses' => 'Admin\BatchController@saveBatch', 'as' => 'batch.save']);
    Route::get('batch/edit/{id}', ['uses' => 'Admin\BatchController@editBatch', 'as' => 'batch.edit']);
    Route::post('batch/update', ['uses' => 'Admin\BatchController@updateBatch', 'as' => 'batch.update']);
    Route::get('batchassign/{id}', ['uses' => 'Admin\BatchController@batchassign', 'as' => 'batch.batchassign']);
    Route::post('batchassign', ['uses' => 'Admin\BatchController@batchAssignsave', 'as' => 'batch.batchassign.save']);
    Route::get('course', ['uses' => 'Admin\BatchController@Course', 'as' => 'batch.course']);
    Route::post('course', ['uses' => 'Admin\BatchController@courseSave', 'as' => 'batch.course.save']);
    Route::get('batch/delete/{id}', ['uses' => 'Admin\BatchController@deleteBatch', 'as' => 'batch.delete']);

    Route::get('batch/batch-progress-list/{id}', ['uses' => 'Admin\BatchController@batchprogressList'])->name('batch-progress-list');
    Route::get('batch/batch-is-completed/{id}', ['uses' => 'Admin\BatchController@batchisCompleted'])->name('batch-is-completed');

    Route::get('batch/batch-recordings/{id}', ['uses' => 'Admin\MyclassController@adminRecordings'])->name('batch-recording-list');
    Route::get('batch/batch-feedback/{id}', ['uses' => 'Admin\MyclassController@batchFeedback'])->name('batch-feedback-list');
    Route::post('batch/batch-feedback/{id}', ['uses' => 'Admin\MyclassController@sendBatchEmail'])->name('batch-email-list');




    //===== Assessment Routes =====//



    Route::get('assessment', ['uses' => 'Admin\AssessmentController@index', 'as' => 'assessment']);
    Route::get('assessment/create', ['uses' => 'Admin\AssessmentController@create', 'as' => 'assessment.create']);
    Route::post('assessment/create', ['uses' => 'Admin\AssessmentController@save', 'as' => 'assessment.save']);
    Route::get('assessment/delete/{id}', ['uses' => 'Admin\AssessmentController@deleteAssessment', 'as' => 'assessment.delete']);
    Route::get('assessment/edit/{id}', ['uses' => 'Admin\AssessmentController@edit', 'as' => 'assessment.edit']);
    Route::post('assessment/edit/{id}', ['uses' => 'Admin\AssessmentController@update', 'as' => 'assessment.update']);


    //===== Assessment Question Routes =====//


    Route::get('assessment/ques/list/{id}', ['uses' => 'Admin\AssessmentController@questionList', 'as' => 'assessment.question.list']);
    Route::get('assessment/ques/{id}', ['uses' => 'Admin\AssessmentController@question', 'as' => 'assessment.question']);
    Route::post('assessment/ques/{id}', ['uses' => 'Admin\AssessmentController@questionCreate', 'as' => 'assessment.question.create']);
    Route::get('assessment/ques/edit/{id}', ['uses' => 'Admin\AssessmentController@questionEdit', 'as' => 'assessment.question.edit']);
    Route::post('assessment/ques/edit/{id}', ['uses' => 'Admin\AssessmentController@questionUpdate', 'as' => 'assessment.question.update']);
    Route::get('assessment/ques/delete/{id}', ['uses' => 'Admin\AssessmentController@deleteAssQuestion', 'as' => 'assessment.question.delete']);

    Route::get('assessment/users/list/{id}', ['uses' => 'Admin\AssessmentController@userList', 'as' => 'assessment.users.list']);

    //===== Settings Routes =====//
    Route::get('settings/general', ['uses' => 'Admin\ConfigController@getGeneralSettings', 'as' => 'general-settings']);

    Route::post('settings/general', ['uses' => 'Admin\ConfigController@saveGeneralSettings'])->name('general-settings');

    Route::get('settings/social', ['uses' => 'Admin\ConfigController@getSocialSettings'])->name('social-settings');

    Route::post('settings/social', ['uses' => 'Admin\ConfigController@saveSocialSettings'])->name('social-settings');

    Route::get('contact', ['uses' => 'Admin\ConfigController@getContact'])->name('contact-settings');

    Route::get('footer', ['uses' => 'Admin\ConfigController@getFooter'])->name('footer-settings');

    Route::get('newsletter', ['uses' => 'Admin\ConfigController@getNewsletterConfig'])->name('newsletter-settings');

    Route::post('newsletter/sendgrid-lists', ['uses' => 'Admin\ConfigController@getSendGridLists'])->name('newsletter.getSendGridLists');


    //===== Slider Routes =====/
    Route::resource('sliders', 'Admin\SliderController');
    Route::get('sliders/status/{id}', 'Admin\SliderController@status')->name('sliders.status', 'id');
    Route::post('sliders/save-sequence', ['uses' => 'Admin\SliderController@saveSequence', 'as' => 'sliders.saveSequence']);
    Route::post('sliders/status', ['uses' => 'Admin\SliderController@updateStatus', 'as' => 'sliders.status']);


    //===== Sponsors Routes =====//
    Route::resource('sponsors', 'Admin\SponsorController');
    Route::get('get-sponsors-data', ['uses' => 'Admin\SponsorController@getData', 'as' => 'sponsors.get_data']);
    Route::post('sponsors_mass_destroy', ['uses' => 'Admin\SponsorController@massDestroy', 'as' => 'sponsors.mass_destroy']);
    Route::get('sponsors/status/{id}', 'Admin\SponsorController@status')->name('sponsors.status', 'id');
    Route::post('sponsors/status', ['uses' => 'Admin\SponsorController@updateStatus', 'as' => 'sponsors.status']);

    //===== Testimonials Routes =====//
    Route::resource('testimonials', 'Admin\TestimonialController');
    Route::get('get-testimonials-data', ['uses' => 'Admin\TestimonialController@getData', 'as' => 'testimonials.get_data']);
    Route::post('testimonials_mass_destroy', ['uses' => 'Admin\TestimonialController@massDestroy', 'as' => 'testimonials.mass_destroy']);
    Route::get('testimonials/status/{id}', 'Admin\TestimonialController@status')->name('testimonials.status', 'id');
    Route::post('testimonials/status', ['uses' => 'Admin\TestimonialController@updateStatus', 'as' => 'testimonials.status']);


    //===== FAQs Routes =====//
    Route::resource('faqs', 'Admin\FaqController');
    Route::get('affiliate/withdrawl', ['uses' => 'AffiliateController@withdrawl', 'as' => 'withdrawl']);
    Route::post('affiliate/withdrawl', ['uses' => 'AffiliateController@vwithdrawl', 'as' => 'vwithdrawl']);
    Route::get('affiliate', ['uses' => 'AffiliateController@index', 'as' => 'affiliate']);
    Route::post('affiliate', ['uses' => 'AffiliateController@vaffiliate', 'as' => 'vaffiliate']);
    Route::post('save-affiliate', ['uses' => 'AffiliateController@saveAff', 'as' => 'saveAff']);
    Route::get('get-faqs-data', ['uses' => 'Admin\FaqController@getData', 'as' => 'faqs.get_data']);
    Route::post('faqs_mass_destroy', ['uses' => 'Admin\FaqController@massDestroy', 'as' => 'faqs.mass_destroy']);
    Route::get('faqs/status/{id}', 'Admin\FaqController@status')->name('faqs.status');
    Route::post('faqs/status', ['uses' => 'Admin\FaqController@updateStatus', 'as' => 'faqs.status']);


    //====== Teams Routes =====//

    Route::get('teams', [TeamController::class, 'index'])->name('team.list');
    Route::get('team-create', [TeamController::class, 'create'])->name('team.create');
    Route::post('team-create', [TeamController::class, 'store'])->name('team.store');
    Route::get('edit-team/{id}', [TeamController::class, 'edit'])->name('team.edit');
    Route::post('edit-team/{id}', [TeamController::class, 'update'])->name('team.update');


    //====== Note Routes =====//   


    //====== BtoB Routes =====//   

    Route::get('b-to-b-lists', [BtoBController::class, 'index'])->name('btob.list');
    Route::get('b-to-b-create', [BtoBController::class, 'create'])->name('btob.create');
    Route::post('b-to-b-create', [BtoBController::class, 'store'])->name('btob.store');
    Route::get('edit-b-to-b/{id}', [BtoBController::class, 'edit'])->name('btob.edit');
    Route::post('edit-b-to-b/{id}', [BtoBController::class, 'update'])->name('btob.update');





    //====== BtoB Routes =====//   

    Route::get('notes', [NoteController::class, 'index'])->name('note.list');
    Route::get('create-note', [NoteController::class, 'create'])->name('note.create');
    Route::post('create-note', [NoteController::class, 'store'])->name('note.store');
    Route::get('edit-note/{id}', [NoteController::class, 'edit'])->name('note.edit');
    Route::post('edit-note/{id}', [NoteController::class, 'update'])->name('note.update');

    Route::post('/upload-image', [NoteController::class, 'imageUpload'])->name('admin.uploadImage');


    //====== Note Routes =====//   

    Route::get('note-categories', [NoteCategoryController::class, 'index'])->name('note.category.list');
    Route::get('create-category', [NoteCategoryController::class, 'create'])->name('note.category.create');
    Route::post('create-category', [NoteCategoryController::class, 'store'])->name('note.category.store');
    Route::get('edit-category/{id}', [NoteCategoryController::class, 'edit'])->name('note.category.edit');
    Route::post('edit-category/{id}', [NoteCategoryController::class, 'update'])->name('note.category.update');

    //====== Contacts Routes =====//
    Route::resource('contact-requests', 'ContactController');
    Route::get('get-contact-requests-data', ['uses' => 'ContactController@getData', 'as' => 'contact_requests.get_data']);

    Route::get('achievement', ['uses' => 'ContactController@achievement', 'as' => 'achievement.achievement']);
    Route::post('achievement', ['uses' => 'ContactController@achievementUpdate', 'as' => 'achievement.achievement']);

    //====== Tax Routes =====//
    Route::resource('tax', 'TaxController');
    Route::get('tax/status/{id}', 'TaxController@status')->name('tax.status', 'id');
    Route::post('tax/status', 'TaxController@updateStatus')->name('tax.status');


    //====== Coupon Routes =====//
    Route::resource('coupons', 'CouponController');
    Route::get('coupons/status/{id}', 'CouponController@status')->name('coupons.status', 'id');
    Route::post('coupons/status', 'CouponController@updateStatus')->name('coupons.status');


    //==== Remove Locale FIle ====//
    Route::post('delete-locale', function () {
        \Barryvdh\TranslationManager\Models\Translation::where('locale', request('locale'))->delete();

        \Illuminate\Support\Facades\File::deleteDirectory(public_path('../resources/lang/' . request('locale')));
    })->name('delete-locale');


    //==== Update Theme Routes ====//
    Route::get('update-theme', 'UpdateController@index')->name('update-theme');
    Route::post('update-theme', 'UpdateController@updateTheme')->name('update-files');
    Route::post('list-files', 'UpdateController@listFiles')->name('list-files');
    Route::get('backup', 'BackupController@index')->name('backup');
    Route::get('generate-backup', 'BackupController@generateBackup')->name('generate-backup');

    Route::post('backup', 'BackupController@storeBackup')->name('backup.store');


    //===Trouble shoot ====//
    Route::get('troubleshoot', 'Admin\ConfigController@troubleshoot')->name('troubleshoot');


    //==== API Clients Routes ====//
    Route::prefix('api-client')->group(function () {
        Route::get('all', 'Admin\ApiClientController@all')->name('api-client.all');
        Route::post('generate', 'Admin\ApiClientController@generate')->name('api-client.generate');
        Route::post('status', 'Admin\ApiClientController@status')->name('api-client.status');
    });


    //==== Sitemap Routes =====//
    Route::get('sitemap', 'SitemapController@getIndex')->name('sitemap.index');
    Route::post('sitemap', 'SitemapController@saveSitemapConfig')->name('sitemap.config');
    Route::get('sitemap/generate', 'SitemapController@generateSitemap')->name('sitemap.generate');


    Route::post('translations/locales/add', 'LangController@postAddLocale');
    Route::post('translations/locales/remove', 'LangController@postRemoveLocaleFolder')->name('delete-locale-folder');
});


//Common - Shared Routes for Teacher and Administrator
Route::group(['middleware' => 'role:administrator|teacher'], function () {

    //====== Reports Routes =====// 
    Route::get('report/sales', ['uses' => 'ReportController@getSalesReport', 'as' => 'reports.sales']);
    Route::get('report/students', ['uses' => 'ReportController@getStudentsReport', 'as' => 'reports.students']);

    Route::get('get-course-reports-data', ['uses' => 'ReportController@getCourseData', 'as' => 'reports.get_course_data']);
    Route::get('get-course-reports-data-subs', ['uses' => 'ReportController@getCourseDataSubs', 'as' => 'reports.get_course_data_subs']);
    Route::get('get-bundle-reports-data', ['uses' => 'ReportController@getBundleData', 'as' => 'reports.get_bundle_data']);
    Route::get('get-students-reports-data', ['uses' => 'ReportController@getStudentsData', 'as' => 'reports.get_students_data']);
    Route::get('course-sort-order', ['uses' => 'Admin\CoursesController@sortOrder', 'as' => 'course.sort_order']);

    //===MyClass Routes===//

    Route::get('availability', ['uses' => 'Admin\MyclassController@availability', 'as' => 'availability']);
    Route::post('availability', ['uses' => 'Admin\MyclassController@saveAvailability', 'as' => 'availabilitysave']);
    Route::get('myclass', ['uses' => 'Admin\MyclassController@index', 'as' => 'myclass']);
    Route::get('calendar', ['uses' => 'Admin\MyclassController@calendar', 'as' => 'myclass.calendar']);
    Route::post('calendar', ['uses' => 'Admin\MyclassController@markUnavail', 'as' => 'myclass.calendar.un']);

    Route::get('myclass/{id}', ['uses' => 'Admin\MyclassController@details', 'as' => 'myclass.details']);
    Route::get('course-tracking/{id}', ['uses' => 'Admin\MyclassController@courseTracking'])->name('course-tracking');
    Route::post('course-tracking/{id}', ['uses' => 'Admin\MyclassController@courseTrackingValidate'])->name('course-tracking');

    // Route::get('myclass/upload/{id}', function(){ dd('pass');});
    Route::get('myclass/upload/{id}', ['uses' => 'Admin\MyclassController@upload', 'as' => 'myclass.upload']);
    Route::post('myclass/uploadfile', ['uses' => 'Admin\MyclassController@uploadFile', 'as' => 'myclass.uploadfile']);
    Route::post('myclass/rmfile', ['uses' => 'Admin\MyclassController@rmFile', 'as' => 'myclass.rmfile']);
    Route::get('myclass/recordings/{id}', ['uses' => 'Admin\MyclassController@recordings', 'as' => 'myclass.recordings']);
    Route::post('getFacultyLaunch', ['uses' => 'Admin\MyclassController@getLaunchURL', 'as' => 'myclass.flaunch']);
    Route::post('getDemoLaunchURL', ['uses' => 'Admin\MyclassController@getDemoLaunchURL', 'as' => 'myclass.demoflaunch']);
    Route::post('getDemoLaunchURLAdmin', ['uses' => 'Admin\MyclassController@getDemoLaunchURLAdmin', 'as' => 'myclass.demoflaunchAdmin']);

    Route::get('myclass/attendance/{id}', ['uses' => 'Admin\MyclassController@Attendance', 'as' => 'myclass.attendance']);

    Route::get('myclass/assignment/{id}', ['uses' => 'Admin\MyclassController@Assignment', 'as' => 'myclass.assignment']);
    Route::get('myclass/assignment/{id}/uploads', ['uses' => 'Admin\MyclassController@AssignmentUploads', 'as' => 'myclass.assignmentuploads']);
    Route::post('myclass/assignment/{id}/uploads', ['uses' => 'Admin\MyclassController@AssignmentUploadsRemarks', 'as' => 'myclass.assignmentuploadsremarks']);
    Route::post('myclass/assignment/{id}', ['uses' => 'Admin\MyclassController@AssignmentCreate', 'as' => 'myclass.assignment']);


    Route::get('myclass/exam/{id}', ['uses' => 'Admin\MyclassController@MyExam', 'as' => 'myclass.exam']);
    Route::get('myclass/exam/{id}/uploads', ['uses' => 'Admin\MyclassController@MyExamUploads', 'as' => 'myclass.examuploads']);
    Route::post('myclass/exam/{id}/uploads', ['uses' => 'Admin\MyclassController@MyExamUploadsRemarks', 'as' => 'myclass.examuploadsremarks']);
    Route::post('myclass/exam/{id}', ['uses' => 'Admin\MyclassController@MyExamCreate', 'as' => 'myclass.exam']);
    Route::get('myclass/exam/{id}/delete', ['uses' => 'Admin\MyclassController@MyExamDelete', 'as' => 'myclass.examdelete']);


    Route::get('myclass/attend/{id}', ['uses' => 'Admin\MyclassController@attend', 'as' => 'myclass.attend']);
    Route::get('myclass/fees/{id}', ['uses' => 'Admin\MyclassController@batchFees', 'as' => 'myclass.fees']);
    Route::post('myclass/fees/{id}', ['uses' => 'Admin\MyclassController@batchFeesUpdate', 'as' => 'myclass.updatefees']);
    //====== Wallet  =====//
    Route::get('payments', ['uses' => 'PaymentController@index', 'as' => 'payments']);
    Route::get('get-earning-data', ['uses' => 'PaymentController@getEarningData', 'as' => 'payments.get_earning_data']);
    Route::get('get-withdrawal-data', ['uses' => 'PaymentController@getwithdrawalData', 'as' => 'payments.get_withdrawal_data']);
    Route::get('payments/withdraw-request', ['uses' => 'PaymentController@createRequest', 'as' => 'payments.withdraw_request']);
    Route::post('payments/withdraw-store', ['uses' => 'PaymentController@storeRequest', 'as' => 'payments.withdraw_store']);
    Route::get('payments-requests', ['uses' => 'PaymentController@paymentRequest', 'as' => 'payments.requests']);
    Route::get('get-payment-request-data', ['uses' => 'PaymentController@getPaymentRequestData', 'as' => 'payments.get_payment_request_data']);
    Route::post('payments-request-update', ['uses' => 'PaymentController@paymentsRequestUpdate', 'as' => 'payments.payments_request_update']);


    Route::get('menu-manager', ['uses' => 'MenuController@index'])->name('menu-manager');
});


//===== Boards Routes =====//
Route::resource('boards', 'Admin\BoardsController');
Route::get('delete-boards/{id}', ['uses' => 'Admin\BoardsController@delete'])->name('boards_delete');


//===== Categories Routes =====//
Route::resource('categories', 'Admin\CategoriesController');
Route::get('get-categories-data', ['uses' => 'Admin\CategoriesController@getData', 'as' => 'categories.get_data']);
Route::post('categories_mass_destroy', ['uses' => 'Admin\CategoriesController@massDestroy', 'as' => 'categories.mass_destroy']);
Route::post('categories_restore/{id}', ['uses' => 'Admin\CategoriesController@restore', 'as' => 'categories.restore']);
Route::delete('categories_perma_del/{id}', ['uses' => 'Admin\CategoriesController@perma_del', 'as' => 'categories.perma_del']);


//===== Courses Routes =====//
Route::resource('courses', 'Admin\CoursesController');
Route::get('get-courses-data', ['uses' => 'Admin\CoursesController@getData', 'as' => 'courses.get_data']);
Route::post('courses_mass_destroy', ['uses' => 'Admin\CoursesController@massDestroy', 'as' => 'courses.mass_destroy']);
Route::post('courses_restore/{id}', ['uses' => 'Admin\CoursesController@restore', 'as' => 'courses.restore']);
Route::delete('courses_perma_del/{id}', ['uses' => 'Admin\CoursesController@perma_del', 'as' => 'courses.perma_del']);
Route::post('course-save-sequence', ['uses' => 'Admin\CoursesController@saveSequence', 'as' => 'courses.saveSequence']);
Route::get('course-publish/{id}', ['uses' => 'Admin\CoursesController@publish', 'as' => 'courses.publish']);


//===== Bundles Routes =====//
Route::resource('bundles', 'Admin\BundlesController');
Route::get('get-bundles-data', ['uses' => 'Admin\BundlesController@getData', 'as' => 'bundles.get_data']);
Route::post('bundles_mass_destroy', ['uses' => 'Admin\BundlesController@massDestroy', 'as' => 'bundles.mass_destroy']);
Route::post('bundles_restore/{id}', ['uses' => 'Admin\BundlesController@restore', 'as' => 'bundles.restore']);
Route::delete('bundles_perma_del/{id}', ['uses' => 'Admin\BundlesController@perma_del', 'as' => 'bundles.perma_del']);
Route::post('bundle-save-sequence', ['uses' => 'Admin\BundlesController@saveSequence', 'as' => 'bundles.saveSequence']);
Route::get('bundle-publish/{id}', ['uses' => 'Admin\BundlesController@publish', 'as' => 'bundles.publish']);


//===== Lessons Routes =====//
Route::resource('lessons', 'Admin\LessonsController');
Route::get('get-lessons-data', ['uses' => 'Admin\LessonsController@getData', 'as' => 'lessons.get_data']);
Route::post('lessons_mass_destroy', ['uses' => 'Admin\LessonsController@massDestroy', 'as' => 'lessons.mass_destroy']);
Route::post('lessons_restore/{id}', ['uses' => 'Admin\LessonsController@restore', 'as' => 'lessons.restore']);
Route::delete('lessons_perma_del/{id}', ['uses' => 'Admin\LessonsController@perma_del', 'as' => 'lessons.perma_del']);


//===== Questions Routes =====//
Route::resource('questions', 'Admin\QuestionsController');
Route::get('get-questions-data', ['uses' => 'Admin\QuestionsController@getData', 'as' => 'questions.get_data']);
Route::post('questions_mass_destroy', ['uses' => 'Admin\QuestionsController@massDestroy', 'as' => 'questions.mass_destroy']);
Route::post('questions_restore/{id}', ['uses' => 'Admin\QuestionsController@restore', 'as' => 'questions.restore']);
Route::delete('questions_perma_del/{id}', ['uses' => 'Admin\QuestionsController@perma_del', 'as' => 'questions.perma_del']);


//===== Questions Options Routes =====//
Route::resource('questions_options', 'Admin\QuestionsOptionsController');
Route::get('get-qo-data', ['uses' => 'Admin\QuestionsOptionsController@getData', 'as' => 'questions_options.get_data']);
Route::post('questions_options_mass_destroy', ['uses' => 'Admin\QuestionsOptionsController@massDestroy', 'as' => 'questions_options.mass_destroy']);
Route::post('questions_options_restore/{id}', ['uses' => 'Admin\QuestionsOptionsController@restore', 'as' => 'questions_options.restore']);
Route::delete('questions_options_perma_del/{id}', ['uses' => 'Admin\QuestionsOptionsController@perma_del', 'as' => 'questions_options.perma_del']);


//===== Tests Routes =====//
Route::resource('tests', 'Admin\TestsController');
Route::post('tests/assign-batch', ['uses' => 'Admin\TestsController@assignBatch', 'as' => 'tests.assign_batch']);
Route::get('test-result/{id}', ['uses' => 'Admin\TestsController@testResult', 'as' => 'tests.result']);
Route::get('test-analysis/{id}/{sid}', ['uses' => 'Admin\TestsController@testAnalysis', 'as' => 'tests.analysis']);
Route::get('get-tests-data', ['uses' => 'Admin\TestsController@getData', 'as' => 'tests.get_data']);
// Route::get('get-tests-resultdata', ['uses' => 'Admin\TestsController@getData', 'as' => 'tests.get_result_data']);
Route::post('tests_mass_destroy', ['uses' => 'Admin\TestsController@massDestroy', 'as' => 'tests.mass_destroy']);
Route::post('tests_restore/{id}', ['uses' => 'Admin\TestsController@restore', 'as' => 'tests.restore']);
Route::delete('tests_perma_del/{id}', ['uses' => 'Admin\TestsController@perma_del', 'as' => 'tests.perma_del']);


//===== Media Routes =====//
Route::post('media/remove', ['uses' => 'Admin\MediaController@destroy', 'as' => 'media.destroy']);


//===== User Account Routes =====//
Route::group(['middleware' => ['auth', 'password_expires']], function () {
    Route::get('account', [AccountController::class, 'index'])->name('account');
    Route::post('account', [AccountController::class, 'profileUpdate'])->name('update.profile');

    Route::get('password', [AccountController::class, 'changePassword'])->name('change.password');
    Route::post('password', [AccountController::class, 'updatePassword'])->name('update.password');

    Route::patch('account/{email?}', [UserPasswordController::class, 'update'])->name('account.post');
    Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');
});


Route::group(['middleware' => 'role:teacher'], function () {
    //====== Review Routes =====//
    Route::get('/training', ['uses' => 'Admin\TeachersController@teacherTraining', 'as' => 'teacherTraining']);
    Route::get('notifications-info', ['uses' => 'NotificationController@teacher', 'as' => 'teacher_notifications']);

    Route::resource('reviews', 'ReviewController');
    Route::get('get-reviews-data', ['uses' => 'ReviewController@getData', 'as' => 'reviews.get_data']);
});


Route::group(['middleware' => 'role:student'], function () {

    //==== Certificates ====//
    Route::get('certificates', 'CertificateController@getCertificates')->name('certificates.index');
    Route::post('certificates/generate', 'CertificateController@generateCertificate')->name('certificates.generate');
    Route::get('certificates/download', ['uses' => 'CertificateController@download', 'as' => 'certificates.download']);
});


//==== Messages Routes =====//
Route::get('messages', ['uses' => 'MessagesController@index', 'as' => 'messages']);
Route::post('messages/unread', ['uses' => 'MessagesController@getUnreadMessages', 'as' => 'messages.unread']);
Route::post('messages/send', ['uses' => 'MessagesController@send', 'as' => 'messages.send']);
Route::post('messages/reply', ['uses' => 'MessagesController@reply', 'as' => 'messages.reply']);


//=== Invoice Routes =====//
Route::get('invoice/download', ['uses' => 'Admin\InvoiceController@getInvoice', 'as' => 'invoice.download']);
Route::get('invoices', ['uses' => 'Admin\InvoiceController@getIndex', 'as' => 'invoices.index']);

Route::get('student-view-invoice/{oid}/{type}', ['uses' => 'Admin\InvoiceController@viewInvoicestudent']);
Route::get('student-view-invoice/{oid}/{type}/{sid}', ['uses' => 'Admin\InvoiceController@viewSubsInvoicestudent']);


//======= Blog Routes =====//
Route::group(['prefix' => 'blog'], function () {
    Route::get('/create', 'Admin\BlogController@create');
    Route::post('/create', 'Admin\BlogController@store');
    Route::get('delete/{id}', 'Admin\BlogController@destroy')->name('blogs.delete');
    Route::get('edit/{id}', 'Admin\BlogController@edit')->name('blogs.edit');
    Route::post('edit/{id}', 'Admin\BlogController@update');
    Route::get('view/{id}', 'Admin\BlogController@show');
    //        Route::get('{blog}/restore', 'BlogController@restore')->name('blog.restore');
    Route::post('{id}/storecomment', 'Admin\BlogController@storeComment')->name('storeComment');
});
Route::resource('blogs', 'Admin\BlogController');
Route::get('get-blogs-data', ['uses' => 'Admin\BlogController@getData', 'as' => 'blogs.get_data']);
Route::post('blogs_mass_destroy', ['uses' => 'Admin\BlogController@massDestroy', 'as' => 'blogs.mass_destroy']);
Route::get('blog-categories', ['uses' => 'Admin\BlogController@cat', 'as' => 'blogs-cat.index']);
Route::get('add-blog-category', ['uses' => 'Admin\BlogController@addCat', 'as' => 'blogs-cat.add']);
Route::post('add-blog-category', ['uses' => 'Admin\BlogController@saveCat', 'as' => 'blogs-cat.add']);

Route::get('edit-blog-category/{id}', ['uses' => 'Admin\BlogController@editCat', 'as' => 'blogs-cat.edit']);
Route::post('edit-blog-category/{id}', ['uses' => 'Admin\BlogController@updateCat', 'as' => 'blogs-cat.update']);

//======= Pages Routes =====//
Route::resource('pages', 'Admin\PageController');
Route::get('get-pages-data', ['uses' => 'Admin\PageController@getData', 'as' => 'pages.get_data']);
Route::post('pages_mass_destroy', ['uses' => 'Admin\PageController@massDestroy', 'as' => 'pages.mass_destroy']);
Route::post('pages_restore/{id}', ['uses' => 'Admin\PageController@restore', 'as' => 'pages.restore']);
Route::delete('pages_perma_del/{id}', ['uses' => 'Admin\PageController@perma_del', 'as' => 'pages.perma_del']);


//==== Reasons Routes ====//
Route::resource('reasons', 'Admin\ReasonController');
Route::get('get-reasons-data', ['uses' => 'Admin\ReasonController@getData', 'as' => 'reasons.get_data']);
Route::post('reasons_mass_destroy', ['uses' => 'Admin\ReasonController@massDestroy', 'as' => 'reasons.mass_destroy']);
Route::get('reasons/status/{id}', 'Admin\ReasonController@status')->name('reasons.status');
Route::post('reasons/status', ['uses' => 'Admin\ReasonController@updateStatus', 'as' => 'reasons.status']);

//==== Home page video Routes ====//
Route::get('video-link', ['uses' => 'Admin\VideoLinkController@homeVideo'])->name('homeVideo');
Route::post('update-link', ['uses' => 'Admin\VideoLinkController@updateLink'])->name('updateLink');
