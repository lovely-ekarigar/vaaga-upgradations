<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\Auth\User\AccountController;
use App\Http\Controllers\Backend\Auth\User\ProfileController;
use \App\Http\Controllers\Backend\Auth\User\UserPasswordController;
use App\Http\Controllers\Backend\Admin\VideoLinkController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\Backend\StudentController;

use App\Http\Controllers\Backend\Admin\NoteController;
use App\Http\Controllers\Backend\Admin\TeamController;
use App\Http\Controllers\Backend\Admin\NoteCategoryController;
use App\Http\Controllers\Backend\EnquiryController;
use App\Http\Controllers\Backend\QuestionController;
use App\Http\Controllers\Backend\BtoBController;
use App\Http\Controllers\Frontend\TestSeriesController;
use App\Http\Controllers\Backend\Admin\DemoController;
use App\Http\Controllers\Backend\Admin\CategoriesController;
use App\Http\Controllers\Backend\Admin\TeachersController;
use App\Http\Controllers\Backend\Admin\BatchController;
use App\Http\Controllers\Backend\Admin\TrainingController;
use App\Http\Controllers\Backend\NotificationController;
use App\Http\Controllers\Backend\Admin\OrderController;
use App\Http\Controllers\Backend\Admin\CoursesController;
use App\Http\Controllers\Backend\Admin\AssessmentController;
use App\Http\Controllers\Backend\Admin\ConfigController;
use App\Http\Controllers\Backend\Admin\SliderController;
use App\Http\Controllers\Backend\Admin\SponsorController;
use App\Http\Controllers\Backend\Admin\TestimonialController;
use App\Http\Controllers\Backend\Admin\FaqController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\TaxController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\UpdateController;
use App\Http\Controllers\Backend\BackupController;
use App\Http\Controllers\Backend\Admin\ApiClientController;
use App\Http\Controllers\Backend\SitemapController;
use App\Http\Controllers\Backend\LangController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\Admin\MyclassController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\MenuController;
use App\Http\Controllers\Backend\Admin\BoardsController;
use App\Http\Controllers\Backend\Admin\BundlesController;
use App\Http\Controllers\Backend\Admin\LessonsController;
use App\Http\Controllers\Backend\Admin\QuestionsController;
use App\Http\Controllers\Backend\Admin\QuestionsOptionsController;
use App\Http\Controllers\Backend\Admin\TestsController;
use App\Http\Controllers\Backend\Admin\MediaController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\CertificateController;
use App\Http\Controllers\Backend\Admin\InvoiceController;
use App\Http\Controllers\Backend\Admin\BlogController;
use App\Http\Controllers\Backend\Admin\PageController;
use App\Http\Controllers\Backend\Admin\ReasonController;
use App\Http\Controllers\Backend\Admin\ForumController;
use App\Http\Controllers\Backend\Admin\FeedbackController;
use App\Http\Controllers\MessagesController;
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
    Route::resource('orders', OrderController::class);

    //===== Demo Request Routes =====//
    Route::get('demo-requests-teacher', [DemoController::class, 'indexTeacher'])->name('demo_requests_teacher');

    Route::get('update-sort', [CategoriesController::class, 'updateSort'])->name('update_sort');
    Route::get('demo-requests', [DemoController::class, 'index'])->name('demo_requests');
    Route::post('demo-requests', [DemoController::class, 'scheduleDemo'])->name('demo_requests_post');
    Route::get('get-demo-requests-data', [DemoController::class, 'getData'])->name('demo_requests.get_data');
    Route::get('get-demo-requests-data-teacher', [DemoController::class, 'getDataTeacher'])->name('demo_requests.get_data_teacher');

    Route::post('demo-requests/status-update', [DemoController::class, 'statusUpdate'])->name('demo_requests_status_update');
    
    
     Route::get('demo-batch', [DemoController::class, 'demoBatch'])->name('demo_batch');
     Route::get('demo-batch-add', [DemoController::class, 'demoBatchAdd'])->name('demo_batch.add');
     Route::post('demo-batch-add', [DemoController::class, 'demoBatchSave'])->name('demo_batch.save');
     
     Route::get('demo-batch-edit/{id}', [DemoController::class, 'demoBatchEdit'])->name('demo_batch.edit');
     Route::post('demo-batch-edit/{id}', [DemoController::class, 'demoBatchUpdate'])->name('demo_batch.update');
     
     
         Route::get('demo-batch-student/{id}', [DemoController::class, 'demoBatchStudent'])->name('demo_batch.student');
     Route::post('demo-batch-student/{id}', [DemoController::class, 'demoBatchStudentUpdate'])->name('demo_batch.student.update');
     

    Route::get('teacher-course-list', [TeachersController::class, 'teachercourseList'])->name('teacher-course-list');
    Route::get('teacher-student-list', [TeachersController::class, 'teacherstudentList'])->name('teacher-student-list');

    Route::get('teacher-attendance', [TeachersController::class, 'teacherattendanceList'])->name('teacher_attendance');
    Route::get('teacher-fees/{id}', [TeachersController::class, 'teacherFees'])->name('teacher_fees');
    Route::post('teacher-fees/{id}', [TeachersController::class, 'teacherFeescreate'])->name('teacher_fees');
    Route::get('teacher-fees-delete/{id}', [TeachersController::class, 'teacherFeesdelete'])->name('teacher_fees_delete');
    // Route::get('teacher-attendance-create', [TeachersController::class, 'teacherattendanceCreate'])->name('teacher_attendance_create');
    // Route::post('teacher-attendance-store', [TeachersController::class, 'teacherattendanceStore'])->name('teacher_attendance_store');
    Route::post('teacher-bank-details-create', [TeachersController::class, 'teacherbankdetailStore'])->name('teacher_bank_details_create');
    Route::post('teacher-document-approof-create', [TeachersController::class, 'teacherdocumentapproofStore'])->name('teacher_document_approof_create');
    Route::post('teacher-ppt-video-store', [TeachersController::class, 'teacherpptVideoStore'])->name('teacher-ppt-video-store');
    Route::get('delete/{id}', [TeachersController::class, 'teacherpptVideoDelete'])->name('teacher-ppt-delete');

    Route::get('batch/batch-progress-list/{id}', [BatchController::class, 'batchprogressList'])->name('batch-progress-list');

    Route::get('batch-progress-list-teacher/{id}', [BatchController::class, 'batchprogressteacherList'])->name('batch-progress-list-teacher');
});


Route::group(['middleware' => 'role:administrator'], function () {







// Generate questions
Route::post('questions-bank/generate', [QuestionController::class, 'generate'])->name('exams.questions.generate'); 

// Questions index
Route::get('questions-bank', [QuestionController::class, 'index'])->name('exams.questions.index');

// Show form to create a question for an exam
Route::get('exams/{exam}/questions/create', [QuestionController::class, 'create'])->name('exams.questions.create');

// Store a new question for an exam
Route::post('exams/{exam}/questions', [QuestionController::class, 'store'])->name('exams.questions.store');

// Show form to edit a question
Route::get('questions-bank/{question}/edit', [QuestionController::class, 'edit'])->name('exams.questions.edit');


Route::put('questions-bank/{question}/edit', [QuestionController::class, 'update'])->name('exams.questions.edit');
Route::get('questions-bank/question/add', [QuestionController::class, 'add'])->name('exams.questions.add');
Route::post('questions-bank/question/add', [QuestionController::class, 'saveQuestion'])->name('exams.questions.add');
Route::get('questions-bank/import', [QuestionController::class, 'import'])->name('exams.questions.import'); 

Route::post('questions-bank/import', [QuestionController::class, 'importNow'])->name('exams.questions.import'); 
// Update a question
Route::put('questions-bank/{question}', [QuestionController::class, 'update'])->name('exams.questions.update');
Route::get('questions-bank/pending-verification', [QuestionController::class, 'getPendingVerification'])->name('exams.questions.pending');
    
    Route::get('questions-bank/{question}/preview', [QuestionController::class, 'preview'])->name('exams.questions.preview');
    
    Route::post('questions-bank/verify', [QuestionController::class, 'verify'])->name('exams.questions.verify');
 Route::get('questions-bank/questions/{id}/preview', [QuestionController::class, 'preview'])->name('exams.questions.preview');
// Delete a question
Route::delete('questions-bank/{question}', [QuestionController::class, 'destroy'])->name('exams.questions.destroy');


// Update question chapter
Route::post('exams/update-question-chapter', [QuestionController::class, 'updateQuestionChapter'])->name('exams.questions.updateChapter');

// Get chapters by subject
Route::get('chapters/by-subject/{subject}', [QuestionController::class, 'getBySubject'])->name('exams.questions.getBySubject');  


    // enquiry route
    Route::get('enquiry-list', [EnquiryController::class, 'index'])->name('endquiryIndex');
    Route::get('enquiry-edit/{id}', [EnquiryController::class, 'edit'])->name('endquiryEdit');
    Route::get('store-enquiry', [EnquiryController::class, 'store'])->name('store.enquiry');




    // end enquiry route
    Route::get('notifications-list', [NotificationController::class, 'index'])->name('notifications');
    Route::get('notifications-create', [NotificationController::class, 'create'])->name('create_notification');
    Route::post('notifications-create', [NotificationController::class, 'save'])->name('save_notification');
    Route::post('notifications-delete', [NotificationController::class, 'destroy'])->name('delete_notification');

    Route::get('trainings-list', [TrainingController::class, 'index'])->name('trainings');
    Route::post('trainings-list-store', [TrainingController::class, 'Store'])->name('training-store');
    Route::get('trainings-list-delete/{id}', [TrainingController::class, 'delete'])->name('training-delete');


    Route::get('demo-history/{id}', [DemoController::class, 'demoHistory'])->name('demo_history');
    Route::get('demo-feedback-list/{id}', [DemoController::class, 'demoFeedback'])->name('demo_feedback_list');
    Route::post('demo-feedback-list/{id}', [DemoController::class, 'senddemoEmail'])->name('demo_feedback_list');

    //===== Teachers Routes =====//
    Route::resource('teachers', TeachersController::class);
    Route::get('teachers-availability', [TeachersController::class, 'courseAvailability'])->name('teachers_course_availability');

    Route::get('teachers/u/delete/{id}', [TeachersController::class, 'rmUnavail'])->name('teachers_availability_rm');
    Route::get('teachers/{id}/availability', [TeachersController::class, 'availability'])->name('teachers_availability');
    Route::post('teachers/{id}/availability', [TeachersController::class, 'markUnavailability'])->name('teachers_availability');
    Route::get('teachers/{id}/availability/edit', [TeachersController::class, 'editAvailability'])->name('teachers_editavailability');
    Route::post('teachers/{id}/availability/edit', [TeachersController::class, 'updateAvailability'])->name('teachers_editavailability');
    Route::get('get-teachers-data', [TeachersController::class, 'getData'])->name('teachers.get_data');
    Route::post('teachers_mass_destroy', [TeachersController::class, 'massDestroy'])->name('teachers.mass_destroy');
    Route::post('teachers_restore/{id}', [TeachersController::class, 'restore'])->name('teachers.restore');
    Route::delete('teachers_perma_del/{id}', [TeachersController::class, 'perma_del'])->name('teachers.perma_del');
    Route::post('teacher/status', [TeachersController::class, 'updateStatus'])->name('teachers.status');
    Route::get('teacher-batch-list/{id}', [TeachersController::class, 'teacherBatchlist'])->name('teacher_batch_list');
    Route::get('teacher-ppt-list/{id}', [TeachersController::class, 'teacherPptlist'])->name('teacher-ppt-list');
    Route::get('teacher-payment-list/{id}', [TeachersController::class, 'teacherWisepayment'])->name('teacher_wise_payment');
    Route::post('teacher-payment-list/{id}', [TeachersController::class, 'teacherWisepaymentRequest'])->name('teacher_wise_payment_request');
    Route::get('teacher-payments', [TeachersController::class, 'teacherPaymentslist'])->name('teacher_payments');
    Route::get('teacher-payments-create', [TeachersController::class, 'teacherPaymentscreate'])->name('teacher_payment_create');
    Route::post('teacher-payments-store', [TeachersController::class, 'teacherPaymentstore'])->name('teacher_payments_store');
    Route::get('teacher-payment-destroy/{id}', [TeachersController::class, 'teacherPaymentdestroy'])->name('teacher_payment_destroy');
    Route::get('teacher-payment-edit/{id}', [TeachersController::class, 'teacherPaymentdedit'])->name('teacher_payment_edit');
    Route::post('teacher-payment-edit/{id}', [TeachersController::class, 'teacherPaymentupdate'])->name('teacher_payment_edit');





    Route::get('payments-stat', [PaymentController::class, 'stats'])->name('teacher_payments_stats');



    Route::get('students', [StudentController::class, 'index'])->name('students.index');
    Route::get('students/orders/{id}', [StudentController::class, 'orders'])->name('students.orders');
    Route::get('students/{id}', [StudentController::class, 'updatestatus'])->name('students.updatestatus');

    Route::get('student-recover/{id}', [StudentController::class, 'studentRecover'])->name('students_recover');
    Route::get('student-show/{id}', [StudentController::class, 'studentShow'])->name('students_show');
    Route::get('student-edit/{id}', [StudentController::class, 'studentEdit'])->name('students_edit');
    Route::post('student-edit/{id}', [StudentController::class, 'studentUpdate'])->name('students_edit');
    Route::get('student-delete/{id}', [StudentController::class, 'studentDelete'])->name('students_delete');

    Route::get('student_batch_list/{id}', [StudentController::class, 'studentBatchlist'])->name('student_batch_list');


    Route::get('feedback-list', [FeedbackController::class, 'index'])->name('feedback-list');

    //===== FORUMS Routes =====//
    Route::resource('forums-category', ForumController::class);
    Route::get('forums-category/status/{id}', [ForumController::class, 'status'])->name('forums-category.status');


    //===== Orders Routes =====//
    Route::get('subscription-reports-details/{id}', [OrderController::class, 'subscriptionDetails'])->name('subscription.detailsInfo');
    Route::post('subscription-reports-details/{id}', [OrderController::class, 'triggerEmail'])->name('subscription.triggerEmail');
    Route::get('subscription-reports', [OrderController::class, 'subscriptionReports'])->name('subscription.report');
    Route::get('gst-reports', [OrderController::class, 'gstReport'])->name('gst.report');
    Route::get('subscription-reports-data', [OrderController::class, 'subscriptionReportsData'])->name('subscription.report_data');
    Route::get('subscriptions', [OrderController::class, 'subscriptions'])->name('subscription.index');
    Route::get('get-subscriptions-data', [OrderController::class, 'getDataSubscription'])->name('subscription.get_data');
    Route::get('get-orders-data', [OrderController::class, 'getData'])->name('orders.get_data');

    Route::get('view-invoice/{oid}/{type}', [OrderController::class, 'viewInvoice']);
    Route::post('orders_mass_destroy', [OrderController::class, 'massDestroy'])->name('orders.mass_destroy');
    Route::post('orders/complete', [OrderController::class, 'complete'])->name('orders.complete');
    Route::delete('orders_perma_del/{id}', [OrderController::class, 'perma_del'])->name('orders.perma_del');

    //===Batch Routes===//
    Route::get('batches', [BatchController::class, 'index'])->name('batch');
    Route::post('batches/onesignal', [BatchController::class, 'onesignal'])->name('onesignal');
    Route::get('batch/create', [BatchController::class, 'create'])->name('batch.create');
    Route::post('batch/create', [BatchController::class, 'saveBatch'])->name('batch.save');
    Route::get('batch/edit/{id}', [BatchController::class, 'editBatch'])->name('batch.edit');
    Route::post('batch/update', [BatchController::class, 'updateBatch'])->name('batch.update');
    Route::get('batchassign/{id}', [BatchController::class, 'batchassign'])->name('batch.batchassign');
    Route::post('batchassign', [BatchController::class, 'batchAssignsave'])->name('batch.batchassign.save');
    Route::get('course', [BatchController::class, 'Course'])->name('batch.course');
    Route::post('course', [BatchController::class, 'courseSave'])->name('batch.course.save');
    Route::get('batch/delete/{id}', [BatchController::class, 'deleteBatch'])->name('batch.delete');

    Route::get('batch/batch-progress-list/{id}', [BatchController::class, 'batchprogressList'])->name('batch-progress-list');
    Route::post('batch/batch-progress-list/{id}', [BatchController::class, 'updatebatchprogressList'])->name('batch-progress-list');
    
    
    Route::get('batch/batch-is-completed/{id}', [BatchController::class, 'batchisCompleted'])->name('batch-is-completed');

    Route::get('batch/batch-recordings/{id}', [MyclassController::class, 'adminRecordings'])->name('batch-recording-list');
    Route::get('batch/batch-feedback/{id}', [MyclassController::class, 'batchFeedback'])->name('batch-feedback-list');
    Route::post('batch/batch-feedback/{id}', [MyclassController::class, 'sendBatchEmail'])->name('batch-email-list');
    
    // Batch Mock Tests Routes
    Route::get('batch/{id}/available-mock-tests', [BatchController::class, 'availableMockTests'])->name('batch.available-mock-tests');
    Route::post('batch/{id}/save-mock-tests', [BatchController::class, 'saveMockTests'])->name('batch.save-mock-tests');
    Route::get('batch/{id}/mock-results', [BatchController::class, 'mockResults'])->name('batch.mockResults');
    Route::get('batch/{id}/students-list', [BatchController::class, 'getStudentsList'])->name('batch.studentsList');
    Route::get('batch/{id}/mock-tests-list', [BatchController::class, 'getMockTestsList'])->name('batch.mockTestsList');
    Route::get('batch/student-mock-result/{student_id}/{mock_id}', [BatchController::class, 'getStudentMockResult'])->name('batch.studentMockResult');




    //===== Assessment Routes =====//



    Route::get('assessment', [AssessmentController::class, 'index'])->name('assessment');
    Route::get('assessment/create', [AssessmentController::class, 'create'])->name('assessment.create');
    Route::post('assessment/create', [AssessmentController::class, 'save'])->name('assessment.save');
    Route::get('assessment/delete/{id}', [AssessmentController::class, 'deleteAssessment'])->name('assessment.delete');
    Route::get('assessment/edit/{id}', [AssessmentController::class, 'edit'])->name('assessment.edit');
    Route::post('assessment/edit/{id}', [AssessmentController::class, 'update'])->name('assessment.update');


    //===== Assessment Question Routes =====//


    Route::get('assessment/ques/list/{id}', [AssessmentController::class, 'questionList'])->name('assessment.question.list');
    Route::get('assessment/ques/{id}', [AssessmentController::class, 'question'])->name('assessment.question');
    Route::post('assessment/ques/{id}', [AssessmentController::class, 'questionCreate'])->name('assessment.question.create');
    Route::get('assessment/ques/edit/{id}', [AssessmentController::class, 'questionEdit'])->name('assessment.question.edit');
    Route::post('assessment/ques/edit/{id}', [AssessmentController::class, 'questionUpdate'])->name('assessment.question.update');
    Route::get('assessment/ques/delete/{id}', [AssessmentController::class, 'deleteAssQuestion'])->name('assessment.question.delete');

    Route::get('assessment/users/list/{id}', [AssessmentController::class, 'userList'])->name('assessment.users.list');

    //===== Settings Routes =====//
    Route::get('settings/general', [ConfigController::class, 'getGeneralSettings'])->name('general-settings');

    Route::post('settings/general', [ConfigController::class, 'saveGeneralSettings'])->name('general-settings');

    Route::get('settings/social', [ConfigController::class, 'getSocialSettings'])->name('social-settings');

    Route::post('settings/social', [ConfigController::class, 'saveSocialSettings'])->name('social-settings');

    Route::get('contact', [ConfigController::class, 'getContact'])->name('contact-settings');

    Route::get('footer', [ConfigController::class, 'getFooter'])->name('footer-settings');

    Route::get('newsletter', [ConfigController::class, 'getNewsletterConfig'])->name('newsletter-settings');

    Route::post('newsletter/sendgrid-lists', [ConfigController::class, 'getSendGridLists'])->name('newsletter.getSendGridLists');


    //===== Slider Routes =====/
    Route::resource('sliders', SliderController::class);
    Route::get('sliders/status/{id}', [SliderController::class, 'status'])->name('sliders.status');
    Route::post('sliders/save-sequence', [SliderController::class, 'saveSequence'])->name('sliders.saveSequence');
    Route::post('sliders/status', [SliderController::class, 'updateStatus'])->name('sliders.status');


    //===== Sponsors Routes =====//
    Route::resource('sponsors', SponsorController::class);
    Route::get('get-sponsors-data', [SponsorController::class, 'getData'])->name('sponsors.get_data');
    Route::post('sponsors_mass_destroy', [SponsorController::class, 'massDestroy'])->name('sponsors.mass_destroy');
    Route::get('sponsors/status/{id}', [SponsorController::class, 'status'])->name('sponsors.status');
    Route::post('sponsors/status', [SponsorController::class, 'updateStatus'])->name('sponsors.status');

    //===== Testimonials Routes =====//
    Route::resource('testimonials', TestimonialController::class);
    Route::get('get-testimonials-data', [TestimonialController::class, 'getData'])->name('testimonials.get_data');
    Route::post('testimonials_mass_destroy', [TestimonialController::class, 'massDestroy'])->name('testimonials.mass_destroy');
    Route::get('testimonials/status/{id}', [TestimonialController::class, 'status'])->name('testimonials.status');
    Route::post('testimonials/status', [TestimonialController::class, 'updateStatus'])->name('testimonials.status');


    //===== FAQs Routes =====//
    Route::resource('faqs', FaqController::class);
    Route::get('affiliate/withdrawl', [AffiliateController::class, 'withdrawl'])->name('withdrawl');
    Route::post('affiliate/withdrawl', [AffiliateController::class, 'vwithdrawl'])->name('vwithdrawl');
    Route::get('affiliate', [AffiliateController::class, 'index'])->name('affiliate');
    Route::post('affiliate', [AffiliateController::class, 'vaffiliate'])->name('vaffiliate');
    Route::post('save-affiliate', [AffiliateController::class, 'saveAff'])->name('saveAff');
    Route::get('get-faqs-data', [FaqController::class, 'getData'])->name('faqs.get_data');
    Route::post('faqs_mass_destroy', [FaqController::class, 'massDestroy'])->name('faqs.mass_destroy');
    Route::get('faqs/status/{id}', [FaqController::class, 'status'])->name('faqs.status');
    Route::post('faqs/status', [FaqController::class, 'updateStatus'])->name('faqs.status');


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

    Route::post('/upload-image', [NoteController::class, 'imageUpload'])->name('uploadImage');


    //====== Note Routes =====//   

    Route::get('note-categories', [NoteCategoryController::class, 'index'])->name('note.category.list');
    Route::get('create-category', [NoteCategoryController::class, 'create'])->name('note.category.create');
    Route::post('create-category', [NoteCategoryController::class, 'store'])->name('note.category.store');
    Route::get('edit-category/{id}', [NoteCategoryController::class, 'edit'])->name('note.category.edit');
    Route::post('edit-category/{id}', [NoteCategoryController::class, 'update'])->name('note.category.update');

    //====== Contacts Routes =====//
    Route::resource('contact-requests', ContactController::class);
    Route::get('get-contact-requests-data', [ContactController::class, 'getData'])->name('contact_requests.get_data');

    Route::get('achievement', [ContactController::class, 'achievement'])->name('achievement.achievement');
    Route::post('achievement', [ContactController::class, 'achievementUpdate'])->name('achievement.achievement');

    //====== Tax Routes =====//
    Route::resource('tax', TaxController::class);
    Route::get('tax/status/{id}', [TaxController::class, 'status'])->name('tax.status');
    Route::post('tax/status', [TaxController::class, 'updateStatus'])->name('tax.status');


    //====== Coupon Routes =====//
    Route::resource('coupons', CouponController::class);
    Route::get('coupons/status/{id}', [CouponController::class, 'status'])->name('coupons.status');
    Route::post('coupons/status', [CouponController::class, 'updateStatus'])->name('coupons.status');


    //==== Remove Locale FIle ====//
    Route::post('delete-locale', function () {
        \Barryvdh\TranslationManager\Models\Translation::where('locale', request('locale'))->delete();

        \Illuminate\Support\Facades\File::deleteDirectory(public_path('../resources/lang/' . request('locale')));
    })->name('delete-locale');


    //==== Update Theme Routes ====//
    Route::get('update-theme', [UpdateController::class, 'index'])->name('update-theme');
    Route::post('update-theme', [UpdateController::class, 'updateTheme'])->name('update-files');
    Route::post('list-files', [UpdateController::class, 'listFiles'])->name('list-files');
    Route::get('backup', [BackupController::class, 'index'])->name('backup');
    Route::get('generate-backup', [BackupController::class, 'generateBackup'])->name('generate-backup');

    Route::post('backup', [BackupController::class, 'storeBackup'])->name('backup.store');


    //===Trouble shoot ====//
    Route::get('troubleshoot', [ConfigController::class, 'troubleshoot'])->name('troubleshoot');


    //==== API Clients Routes ====//
    Route::prefix('api-client')->group(function () {
        Route::get('all', [ApiClientController::class, 'all'])->name('api-client.all');
        Route::post('generate', [ApiClientController::class, 'generate'])->name('api-client.generate');
        Route::post('status', [ApiClientController::class, 'status'])->name('api-client.status');
    });


    //==== Sitemap Routes =====//
    Route::get('sitemap', [SitemapController::class, 'getIndex'])->name('sitemap.index');
    Route::post('sitemap', [SitemapController::class, 'saveSitemapConfig'])->name('sitemap.config');
    Route::get('sitemap/generate', [SitemapController::class, 'generateSitemap'])->name('sitemap.generate');


    Route::post('translations/locales/add', [LangController::class, 'postAddLocale']);
    Route::post('translations/locales/remove', [LangController::class, 'postRemoveLocaleFolder'])->name('delete-locale-folder');
});


//Common - Shared Routes for Teacher and Administrator
Route::group(['middleware' => 'role:administrator|teacher'], function () {

    //====== Reports Routes =====// 
    Route::get('report/sales', [ReportController::class, 'getSalesReport'])->name('reports.sales');
    Route::get('report/students', [ReportController::class, 'getStudentsReport'])->name('reports.students');

    Route::get('get-course-reports-data', [ReportController::class, 'getCourseData'])->name('reports.get_course_data');
    Route::get('get-course-reports-data-subs', [ReportController::class, 'getCourseDataSubs'])->name('reports.get_course_data_subs');
    Route::get('get-bundle-reports-data', [ReportController::class, 'getBundleData'])->name('reports.get_bundle_data');
    Route::get('get-students-reports-data', [ReportController::class, 'getStudentsData'])->name('reports.get_students_data');
    Route::get('course-sort-order', [CoursesController::class, 'sortOrder'])->name('course.sort_order');

    //===MyClass Routes===//

    Route::get('availability', [MyclassController::class, 'availability'])->name('availability');
    Route::post('availability', [MyclassController::class, 'saveAvailability'])->name('availabilitysave');
    Route::get('myclass', [MyclassController::class, 'index'])->name('myclass');
    
     Route::post('myclass/suspend', [MyclassController::class, 'suspend'])->name('myclass.suspend');
     
     // Mock Test Routes
     Route::get('myclass/mock-tests/{batch_id}', [MyclassController::class, 'mockTestsPage'])->name('myclass.mockTestsPage');
     Route::get('myclass/get-mock-tests', [MyclassController::class, 'getMockTests'])->name('myclass.getMockTests');
     Route::post('myclass/toggle-mock-status', [MyclassController::class, 'toggleMockStatus'])->name('myclass.toggleMockStatus');
     Route::post('myclass/schedule-mock', [MyclassController::class, 'scheduleMock'])->name('myclass.scheduleMock');
     Route::get('myclass/mock-test-questions/{mock_id}', [MyclassController::class, 'mockTestQuestions'])->name('myclass.mockTestQuestions');
     Route::post('myclass/submit-mock/{mock_id}', [MyclassController::class, 'submitMock'])->name('myclass.submitMock');
     Route::post('myclass/refresh-question', [MyclassController::class, 'refreshQuestion'])->name('myclass.refreshQuestion');
     Route::post('myclass/report-question', [MyclassController::class, 'reportQuestion'])->name('myclass.reportQuestion');
     
     // Mock Results Routes
     Route::get('myclass/mock-results/{batch_id}', [MyclassController::class, 'mockResults'])->name('myclass.mockResults');
     Route::get('myclass/{batch_id}/students-list', [MyclassController::class, 'getStudentsList'])->name('myclass.studentsList');
     Route::get('myclass/{batch_id}/mock-tests-list', [MyclassController::class, 'getMockTestsList'])->name('myclass.mockTestsList');
     Route::get('myclass/student-mock-result/{student_id}/{mock_id}', [MyclassController::class, 'getStudentMockResult'])->name('myclass.studentMockResult');
     
     
    Route::get('calendar', [MyclassController::class, 'calendar'])->name('myclass.calendar');
    Route::post('calendar', [MyclassController::class, 'markUnavail'])->name('myclass.calendar.un');

    Route::get('myclass/{id}', [MyclassController::class, 'details'])->name('myclass.details');
    Route::get('course-tracking/{id}', [MyclassController::class, 'courseTracking'])->name('course-tracking');
    Route::post('course-tracking/{id}', [MyclassController::class, 'courseTrackingValidate'])->name('course-tracking');

    // Route::get('myclass/upload/{id}', function(){ dd('pass');});
    Route::get('myclass/upload/{id}', [MyclassController::class, 'upload'])->name('myclass.upload');
    Route::post('myclass/uploadfile', [MyclassController::class, 'uploadFile'])->name('myclass.uploadfile');
    Route::post('myclass/rmfile', [MyclassController::class, 'rmFile'])->name('myclass.rmfile');
    Route::get('myclass/recordings/{id}', [MyclassController::class, 'recordings'])->name('myclass.recordings');
    
    Route::get('myclass/class-waiting', [MyclassController::class, 'tutorwaiting'])->name('myclass.tutorwaiting');
     
    Route::post('getFacultyLaunch', [MyclassController::class, 'getLaunchURL'])->name('myclass.flaunch');
    Route::post('getDemoLaunchURL', [MyclassController::class, 'getDemoLaunchURL'])->name('myclass.demoflaunch');
    Route::post('getDemoLaunchURLAdmin', [MyclassController::class, 'getDemoLaunchURLAdmin'])->name('myclass.demoflaunchAdmin');

    Route::get('myclass/attendance/{id}', [MyclassController::class, 'Attendance'])->name('myclass.attendance');

    Route::get('myclass/assignment/{id}', [MyclassController::class, 'Assignment'])->name('myclass.assignment');
    Route::get('myclass/assignment/{id}/uploads', [MyclassController::class, 'AssignmentUploads'])->name('myclass.assignmentuploads');
    Route::post('myclass/assignment/{id}/uploads', [MyclassController::class, 'AssignmentUploadsRemarks'])->name('myclass.assignmentuploadsremarks');
    Route::post('myclass/assignment/{id}', [MyclassController::class, 'AssignmentCreate'])->name('myclass.assignment');


    Route::get('myclass/exam/{id}', [MyclassController::class, 'MyExam'])->name('myclass.exam');
     Route::get('myclass/upload-exam/{id}', [MyclassController::class, 'MyExamUpload'])->name('myclass.exam.upload');
     Route::post('myclass/upload-exam/{id}', [MyclassController::class, 'MyExamUploadGenerate'])->name('myclass.exam.upload.generate');
    Route::get('myclass/exam/{id}/uploads', [MyclassController::class, 'MyExamUploads'])->name('myclass.examuploads');
    Route::post('myclass/exam/{id}/uploads', [MyclassController::class, 'MyExamUploadsRemarks'])->name('myclass.examuploadsremarks');
    Route::post('myclass/exam/{id}', [MyclassController::class, 'MyExamCreate'])->name('myclass.exam');
    Route::get('myclass/exam/{id}/delete', [MyclassController::class, 'MyExamDelete'])->name('myclass.examdelete');


    Route::get('myclass/attend/{id}', [MyclassController::class, 'attend'])->name('myclass.attend');
    Route::get('myclass/fees/{id}', [MyclassController::class, 'batchFees'])->name('myclass.fees');
    Route::post('myclass/fees/{id}', [MyclassController::class, 'batchFeesUpdate'])->name('myclass.updatefees');
    Route::get('track/batch', [MyclassController::class, 'runningStatus'])->name('myclass.runningStatus');
     Route::get('track/exam', [MyclassController::class, 'runningStatusExam'])->name('myclass.runningStatus.exam');
   
    
    
    //====== Wallet  =====//
    Route::get('payments', [PaymentController::class, 'index'])->name('payments');
    Route::get('get-earning-data', [PaymentController::class, 'getEarningData'])->name('payments.get_earning_data');
    Route::get('get-withdrawal-data', [PaymentController::class, 'getwithdrawalData'])->name('payments.get_withdrawal_data');
    Route::get('payments/withdraw-request', [PaymentController::class, 'createRequest'])->name('payments.withdraw_request');
    Route::post('payments/withdraw-store', [PaymentController::class, 'storeRequest'])->name('payments.withdraw_store');
    Route::get('payments-requests', [PaymentController::class, 'paymentRequest'])->name('payments.requests');
    Route::get('get-payment-request-data', [PaymentController::class, 'getPaymentRequestData'])->name('payments.get_payment_request_data');
    Route::post('payments-request-update', [PaymentController::class, 'paymentsRequestUpdate'])->name('payments.payments_request_update');


    Route::get('menu-manager', [MenuController::class, 'index'])->name('menu-manager');
});


//===== Boards Routes =====//
Route::resource('boards', BoardsController::class);
Route::get('delete-boards/{id}', [BoardsController::class, 'delete'])->name('boards_delete');


//===== Categories Routes =====//
Route::resource('categories', CategoriesController::class);
Route::get('get-categories-data', [CategoriesController::class, 'getData'])->name('categories.get_data');
Route::post('categories_mass_destroy', [CategoriesController::class, 'massDestroy'])->name('categories.mass_destroy');
Route::post('categories_restore/{id}', [CategoriesController::class, 'restore'])->name('categories.restore');
Route::delete('categories_perma_del/{id}', [CategoriesController::class, 'perma_del'])->name('categories.perma_del');


//===== Courses Routes =====//
Route::resource('courses', CoursesController::class);
Route::get('get-courses-data', [CoursesController::class, 'getData'])->name('courses.get_data');
Route::post('courses_mass_destroy', [CoursesController::class, 'massDestroy'])->name('courses.mass_destroy');
Route::post('courses_restore/{id}', [CoursesController::class, 'restore'])->name('courses.restore');
Route::delete('courses_perma_del/{id}', [CoursesController::class, 'perma_del'])->name('courses.perma_del');
Route::post('course-save-sequence', [CoursesController::class, 'saveSequence'])->name('courses.saveSequence');
Route::get('course-publish/{id}', [CoursesController::class, 'publish'])->name('courses.publish');


//===== Bundles Routes =====//
Route::resource('bundles', BundlesController::class);
Route::get('get-bundles-data', [BundlesController::class, 'getData'])->name('bundles.get_data');
Route::post('bundles_mass_destroy', [BundlesController::class, 'massDestroy'])->name('bundles.mass_destroy');
Route::post('bundles_restore/{id}', [BundlesController::class, 'restore'])->name('bundles.restore');
Route::delete('bundles_perma_del/{id}', [BundlesController::class, 'perma_del'])->name('bundles.perma_del');
Route::post('bundle-save-sequence', [BundlesController::class, 'saveSequence'])->name('bundles.saveSequence');
Route::get('bundle-publish/{id}', [BundlesController::class, 'publish'])->name('bundles.publish');


//===== Lessons Routes =====//
Route::resource('lessons', LessonsController::class);
Route::get('get-lessons-data', [LessonsController::class, 'getData'])->name('lessons.get_data');
Route::post('lessons_mass_destroy', [LessonsController::class, 'massDestroy'])->name('lessons.mass_destroy');
Route::post('lessons_restore/{id}', [LessonsController::class, 'restore'])->name('lessons.restore');
Route::delete('lessons_perma_del/{id}', [LessonsController::class, 'perma_del'])->name('lessons.perma_del');


//===== Questions Routes =====//
Route::resource('questions', QuestionsController::class);
Route::get('get-questions-data', [QuestionsController::class, 'getData'])->name('questions.get_data');
Route::post('questions_mass_destroy', [QuestionsController::class, 'massDestroy'])->name('questions.mass_destroy');
Route::post('questions_restore/{id}', [QuestionsController::class, 'restore'])->name('questions.restore');
Route::delete('questions_perma_del/{id}', [QuestionsController::class, 'perma_del'])->name('questions.perma_del');


//===== Questions Options Routes =====//
Route::resource('questions_options', QuestionsOptionsController::class);
Route::get('get-qo-data', [QuestionsOptionsController::class, 'getData'])->name('questions_options.get_data');
Route::post('questions_options_mass_destroy', [QuestionsOptionsController::class, 'massDestroy'])->name('questions_options.mass_destroy');
Route::post('questions_options_restore/{id}', [QuestionsOptionsController::class, 'restore'])->name('questions_options.restore');
Route::delete('questions_options_perma_del/{id}', [QuestionsOptionsController::class, 'perma_del'])->name('questions_options.perma_del');


//===== Tests Routes =====//
Route::resource('tests', TestsController::class);
Route::post('tests/assign-batch', [TestsController::class, 'assignBatch'])->name('tests.assign_batch');
Route::get('test-result/{id}', [TestsController::class, 'testResult'])->name('tests.result');
Route::get('test-analysis/{id}/{sid}', [TestsController::class, 'testAnalysis'])->name('tests.analysis');
Route::get('get-tests-data', [TestsController::class, 'getData'])->name('tests.get_data');
// Route::get('get-tests-resultdata', [TestsController::class, 'getData'])->name('tests.get_result_data');
Route::post('tests_mass_destroy', [TestsController::class, 'massDestroy'])->name('tests.mass_destroy');
Route::post('tests_restore/{id}', [TestsController::class, 'restore'])->name('tests.restore');
Route::delete('tests_perma_del/{id}', [TestsController::class, 'perma_del'])->name('tests.perma_del');


//===== Media Routes =====//
Route::post('media/remove', [MediaController::class, 'destroy'])->name('media.destroy');


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
    Route::get('/training', [TeachersController::class, 'teacherTraining'])->name('teacherTraining');
    Route::get('notifications-info', [NotificationController::class, 'teacher'])->name('teacher_notifications');

    Route::resource('reviews', ReviewController::class);
    Route::get('get-reviews-data', [ReviewController::class, 'getData'])->name('reviews.get_data');
});


Route::group(['middleware' => 'role:student'], function () {

    //==== Certificates ====//
    Route::get('certificates', [CertificateController::class, 'getCertificates'])->name('certificates.index');
    Route::post('certificates/generate', [CertificateController::class, 'generateCertificate'])->name('certificates.generate');
    Route::get('certificates/download', [CertificateController::class, 'download'])->name('certificates.download');
});


//==== Messages Routes =====//
Route::get('messages', [MessagesController::class, 'index'])->name('messages');
Route::post('messages/unread', [MessagesController::class, 'getUnreadMessages'])->name('messages.unread');
Route::post('messages/send', [MessagesController::class, 'send'])->name('messages.send');
Route::post('messages/reply', [MessagesController::class, 'reply'])->name('messages.reply');


//=== Invoice Routes =====//
Route::get('invoice/download', [InvoiceController::class, 'getInvoice'])->name('invoice.download');
Route::get('invoices', [InvoiceController::class, 'getIndex'])->name('invoices.index');

Route::get('student-view-invoice/{oid}/{type}', [InvoiceController::class, 'viewInvoicestudent']);
Route::get('student-view-invoice/{oid}/{type}/{sid}', [InvoiceController::class, 'viewSubsInvoicestudent']);


//======= Blog Routes =====//
Route::group(['prefix' => 'blog'], function () {
    Route::get('/create', [BlogController::class, 'create']);
    Route::post('/create', [BlogController::class, 'store']);
    Route::get('delete/{id}', [BlogController::class, 'destroy'])->name('blogs.delete');
    Route::get('edit/{id}', [BlogController::class, 'edit'])->name('blogs.edit');
    Route::post('edit/{id}', [BlogController::class, 'update']);
    Route::get('view/{id}', [BlogController::class, 'show']);
    //        Route::get('{blog}/restore', [BlogController::class, 'restore'])->name('blog.restore');
    Route::post('{id}/storecomment', [BlogController::class, 'storeComment'])->name('storeComment');
});
Route::resource('blogs', BlogController::class);
Route::get('get-blogs-data', [BlogController::class, 'getData'])->name('blogs.get_data');
Route::post('blogs_mass_destroy', [BlogController::class, 'massDestroy'])->name('blogs.mass_destroy');
Route::get('blog-categories', [BlogController::class, 'cat'])->name('blogs-cat.index');
Route::get('add-blog-category', [BlogController::class, 'addCat'])->name('blogs-cat.add');
Route::post('add-blog-category', [BlogController::class, 'saveCat'])->name('blogs-cat.add');

Route::get('edit-blog-category/{id}', [BlogController::class, 'editCat'])->name('blogs-cat.edit');
Route::post('edit-blog-category/{id}', [BlogController::class, 'updateCat'])->name('blogs-cat.update');

//======= Pages Routes =====//
Route::resource('pages', PageController::class);
Route::get('get-pages-data', [PageController::class, 'getData'])->name('pages.get_data');
Route::post('pages_mass_destroy', [PageController::class, 'massDestroy'])->name('pages.mass_destroy');
Route::post('pages_restore/{id}', [PageController::class, 'restore'])->name('pages.restore');
Route::delete('pages_perma_del/{id}', [PageController::class, 'perma_del'])->name('pages.perma_del');


//==== Reasons Routes ====//
Route::resource('reasons', ReasonController::class);
Route::get('get-reasons-data', [ReasonController::class, 'getData'])->name('reasons.get_data');
Route::post('reasons_mass_destroy', [ReasonController::class, 'massDestroy'])->name('reasons.mass_destroy');
Route::get('reasons/status/{id}', [ReasonController::class, 'status'])->name('reasons.status');
Route::post('reasons/status', [ReasonController::class, 'updateStatus'])->name('reasons.status');

//==== Home page video Routes ====//
Route::get('video-link', [VideoLinkController::class, 'homeVideo'])->name('homeVideo');
Route::post('update-link', [VideoLinkController::class, 'updateLink'])->name('updateLink');
