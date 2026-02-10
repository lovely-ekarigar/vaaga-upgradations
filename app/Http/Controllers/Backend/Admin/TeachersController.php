<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Traits\FileUploadTrait;
use App\Http\Requests\Admin\StoreTeachersRequest;
use App\Http\Requests\Admin\UpdateTeachersRequest;
use App\Models\Auth\User;
use App\Models\Batch;
use App\Models\TeacherProfile;
use App\Models\TeacherPayment;
use App\Models\TeacherAttendance;
use App\Models\TeacherBatch;
use App\Models\StudentTeacherBatch;
use App\Models\Course;
use App\Models\TeacherFee;
use App\Models\Withdraw;
use App\Models\TeacherPpt;
use App\Models\Training;

use App\Models\CourseUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;
use App\Helpers\General\EarningHelper;
use App\Models\Earning;
use App\Models\Unavailability;
use App\Models\Elearn;
// use App\Models\TeacherAttendance;
use App\Mail\Frontend\Demo\TeacherActivationEmail;
use App\Mail\Frontend\Demo\TeacherPaymentEmail;
use Mail;
class TeachersController extends Controller
{
    use FileUploadTrait;

    /**
     * Display a listing of Category.
     *
     * @return \Illuminate\Http\Response
     */


     public function teacherTraining()
     {
    
        $tutor_list = Training::where('training_for','tutor')->get();
        return view('backend.teachers.training',compact('tutor_list'));

     }


    public function editAvailability($id){
$user = User::find($id);

    $tp = TeacherProfile::where('user_id',$user->id)->first();

    if(!$tp){
        return abort(404);
    }
     $days  = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday'); 

     if(!$tp->availability){
        $ex = ["s"=>[],"e"=>[]];
       $data = array('monday'=>$ex,'tuesday'=>$ex,'wednesday'=>$ex,'thursday'=>$ex,'friday'=>$ex,'saturday'=>$ex,'sunday'=>$ex); 
     }else{
        $data = json_decode($tp->availability,true);
     }
     // dd($data);

    return view('backend.teachers.edit_availability',compact('user','data'));

    }

    public function updateAvailability(Request $request,$id){
 $start=$request->start;
    $end=$request->end;
    $slots=[];

    foreach($start as $k=>$s){

        $lists=[];
        $liste=[];
        for($i=0;$i<count($s);$i++){

            if($s[$i] && $end[$k][$i]){
            $lists[]=$s[$i];
            $liste[]=$end[$k][$i];
}

        }

        $slots[$k] = array("s"=>$lists,"e"=>$liste);

    }



   // dd($slots);


     $tp = TeacherProfile::where('user_id',$id)->first();
     $tp->availability = json_encode($slots);
     $tp->update();

     return redirect()->back()->withFlashSuccess("Availability has been updated");

    }

    public function teacherpptVideoStore(Request $request){
       
        $ppt = new TeacherPpt();
        $ppt->teacher_id = auth()->user()->id;
        $ppt->title = $request->ppt_title;

        if ($request->has('ppt_file')) {
            $idproofp=time().".".$request->ppt_file->getClientOriginalExtension();
             $request->ppt_file->move(public_path('storage/ppt'), $idproofp);
           
            $ppt->file_path = 'storage/ppt/'.$idproofp;
        }

        $ppt->save();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.created'));

    }
    public function teacherpptVideoDelete(Request $request,$id)
    {
        $ppt = TeacherPpt::find($id);
        $ppt->delete();

        return redirect()->back()->withFlashDanger("Delete Successfully");
    }

    public function rmUnavail($id){

            $un = Unavailability::find($id);
            if($un){
                $un->delete();
            }
             return redirect()->back()->withFlashSuccess("Adhoc Unavailability deleted.");
    }

    public function courseAvailability(Request $request){
      
        $course = '';
        $tutors=null;
        if($request->slot){
            $course = $request->slot;
        }
        $courseList = Course::where("published",'1')->orderBy("sort_order","asc")->get();
        $crs = Course::find($course);
        $ids=[];
        if($crs){
            $tutors = $crs->teachers;
            if($request->tutors){
        $ids=$request->tutors;
    }else{
         $ids = $crs->teachers->pluck('id')->toArray(); 
    }
    }
        $dateListx = [];
        foreach($ids as $id){
$uo = new Unavailability;
$dateList1 = $uo->getAvailSlot($id,45);
$dateListx = array_merge($dateListx,$dateList1);
}

  $dateList = [];

  if($request->date){

    foreach($dateListx as $dx){
        if(date("Y-m-d",strtotime($dx['start'])) == date("Y-m-d",strtotime($request->date))){
$dateList[] = $dx;
        }

    }

  }else{
     $dateList= $dateListx;
  }



       return view('backend.teachers.coursewise-teacher-calendar', compact('dateList','courseList','course','tutors'));
        
    }


    public function availability(Request $request, $id){
        $user = User::find($id);
$slot="booked";
if($request->slot){
$slot = $request->slot;
}
 $uo = new Unavailability;
 if($slot=='booked'){
$dateList = $uo->getTeacherAvail($id);
 }else if($slot=='all'){
    $dateList1 = $uo->getTeacherAvail($id);
     $dateList2 = $uo->getAvailSlot($id,30);
     $dateList =array_merge($dateList1,$dateList2);
      
 }else{
    $dateList = $uo->getAvailSlot($id,30);
 }
   
  
          return view('backend.teachers.calendar', compact('dateList','user','slot'));
    }
    public function index(Request $request)
    {

        if (request('show_deleted') == 1) {

            $users = User::role('teacher')->onlyTrashed()->get();
        } else {
            $users = User::role('teacher')->get();
        }
        $type='all';

        if($request->type){
            $type  =$request->type;
        }



        return view('backend.teachers.index', compact('users','type'));
    }

    public function markUnavailability(Request $request,$id){
        $un = new Unavailability;
    $un->user_id = $id;
    $un->date_time = $request->date;
    $un->reason = $request->reason;
    $un->save();


    return redirect()->back()->withFlashSuccess("Unavailability has been marked.");
    }

    public function teacherWisepaymentRequest(Request $request){

        $wd = new Withdraw;
        $wd->user_id = $request->user_id;
        $wd->amount = $request->amount;
        $wd->remarks = $request->remarks;
        $wd->payment_type='0';
        $wd->status='1';
        $wd->request_from='admin';
        $wd->save();


$teacher = User::find($request->user_id);
            Mail::to($teacher)->send(new TeacherPaymentEmail($teacher,$request->amount));

            return redirect()->back()->withFlashSuccess("Payment Record has been saved");

    }

    public function teacherPaymentslist()
    {
        $teacher_payment_list = TeacherPayment::with('teacher')->orderBy('id','desc')->get();

        return view('backend.teachers.teacher_payment_list',compact('teacher_payment_list'));
    }

    public function teacherPaymentscreate(Request $request)
    {
        
        if ($request->teacher_id) {

            $teacher_list_data = User::where('id',$request->teacher_id)->first();

            return view('backend.teachers.teacher_payment_create',compact('teacher_list_data'));
        } else {

            $teacher_list = User::role('teacher')->get();

           return view('backend.teachers.teacher_payment_create',compact('teacher_list'));
        }
        
    }

    public function teacherPaymentstore(Request $request)
    {
        $this->validate($request, [
            'teacher_id' => 'required',
            'payment_mode' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'remark' => 'required',

        ], [
            'teacher_id.required' => 'Kindly Select Teacher Name',
            'payment_mode.required' => 'Kindly Select Payment Mode',
            'amount.required' => 'Kindly Enter Amount',
            'date.required' => 'Kindly Enter Date',
            'remark.required' => 'Kindly Enter Remark',

        ]);

        $teacher_payment = new TeacherPayment();
        $teacher_payment->teacher_id = $request->teacher_id;
        $teacher_payment->payment_mode = $request->payment_mode;
        $teacher_payment->amount = $request->amount;
        $teacher_payment->date = $request->date;
        $teacher_payment->remark = $request->remark;
        $teacher_payment->save();
        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function teacherPaymentdedit($id)
    {
         $teacher = TeacherPayment::findOrFail($id);
         $teacher_list = User::role('teacher')->get();

         return view('backend.teachers.teacher_payment_edit',compact('teacher_list','teacher'));
    }

    public function teacherPaymentdestroy($id)
    {

          $teacher = TeacherPayment::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teacher_payments')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    
    }

    public function teacherPaymentupdate(Request $request,$id)
    {
        $this->validate($request, [
            'teacher_id' => 'required',
            'payment_mode' => 'required',
            'amount' => 'required',
            'date' => 'required',
            'remark' => 'required',

        ], [
            'teacher_id.required' => 'Kindly Select Teacher Name',
            'payment_mode.required' => 'Kindly Select Payment Mode',
            'amount.required' => 'Kindly Enter Amount',
            'date.required' => 'Kindly Enter Date',
            'remark.required' => 'Kindly Enter Remark',

        ]);

        $teacher_payment = TeacherPayment::findOrFail($id);
        $teacher_payment->teacher_id = $request->teacher_id;
        $teacher_payment->payment_mode = $request->payment_mode;
        $teacher_payment->amount = $request->amount;
        $teacher_payment->date = $request->date;
        $teacher_payment->remark = $request->remark;
        $teacher_payment->update();
        return redirect()->route('admin.teacher_payments')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    public function teacherFees($id)
    {
        $teacher_name = User::find($id);
        $teacher_fees_list = TeacherFee::with('course')->where('teacher_id',$id)->get();
        $course_list = [];
        foreach ($teacher_fees_list as $tfl) {

            $course_list[] = $tfl->course_id;

        }

       $course_user_list =  CourseUser::with('course')->where('user_id',$id)->get();

        return view('backend.teachers.fees',compact('teacher_name','teacher_fees_list','course_user_list','course_list'));
    }

    public function teacherFeescreate(Request $request,$id)
    {
//        $teacher_fees = new TeacherFee();
//        $teacher_fees->course_id = $request->course_id;
//        $teacher_fees->fee = $request->fee;
//        $teacher_fees->teacher_id = $request->teacher_id;
//        $teacher_fees->save();

//        $tbs = TeacherBatch::where('tid',$request->teacher_id)->get();
      
//        foreach($tbs as $t){
//  $tb_id = 0;
//         $batch = Batch::find($t->bid);
//         if($batch){
//             if($batch->cid == $request->course_id){
//                 $tb_id = $t->id;
              
//                 if($batch->is_completed=='0'){
//                if($tb_id!=0){

//             $tb = TeacherBatch::find($tb_id);
//             if($tb){
//                 $tb->fees = $request->fee;
//                 $tb->update();
//             }
//        }


//             }
// }

//         }
//        }

      


//         return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function teacherFeesdelete($id)
    {
        $teacher_course = TeacherFee::find($id);
        $teacher_course->delete();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }
   
    public function teacherPptlist($id)
    {
        $teacher_ppt = TeacherPpt::where('teacher_id',$id)->get();
        $teacher_name = User::find($id);

        return view('backend.teachers.ppt-list',compact('teacher_ppt','teacher_name'));
    }

    /**
     * Display a listing of Courses via ajax DataTable.
     *
     * @return \Illuminate\Http\Response
     */
    public function getData(Request $request)
    {
        $has_view = false;
        $has_delete = false;
        $has_edit = false;
        $teachers = "";


        if (request('show_deleted') == 1) {

            $teachers = User::role('teacher')->onlyTrashed()->orderBy('created_at', 'desc');
        } else {
            $teachers = User::role('teacher')->orderBy('created_at', 'desc');
        }

         if($request->type == 'active'){

            $teachers =$teachers->where('active','1');
        }

        if($request->type=='non-active'){ 
         
            $teachers =$teachers->where('active','0');   
        }

        $teachers =$teachers->get();



        if (auth()->user()->isAdmin()) {
            $has_view = true;
            $has_edit = true;
            $has_delete = true;
        }


        return DataTables::of($teachers)
            ->addIndexColumn()
            ->addColumn('actions', function ($q) use ($has_view, $has_edit, $has_delete, $request) {
                $view = "";
                $edit = "";
                $delete = "";
                if ($request->show_deleted == 1) {
                    return view('backend.datatable.action-trashed')->with(['route_label' => 'admin.teachers', 'label' => 'teacher', 'value' => $q->id]);
                }

                if ($has_view) {
                    $view = view('backend.datatable.action-view')
                        ->with(['route' => route('admin.teachers.show', ['teacher' => $q->id])])->render();
                }

                if ($has_edit) {
                    $edit = view('backend.datatable.action-edit')
                        ->with(['route' => route('admin.teachers.edit', ['teacher' => $q->id])])
                        ->render();
                    $view .= $edit;
                }

                if ($has_delete) {
                    $delete = view('backend.datatable.action-delete')
                        ->with(['route' => route('admin.teachers.destroy', ['teacher' => $q->id])])
                        ->render();
                    $view .= $delete;
                }

                $view .= '<a class="btn btn-warning mb-1" href="' . route('admin.courses.index', ['teacher_id' => $q->id]) . '">' . trans('labels.backend.courses.title') . '</a>';

                $view .= '<a class="btn btn-outline-info mb-1 ml-1" href="' . route('admin.teacher_wise_payment', ['id' => $q->id]) . '">' . 'Payments' . '</a>';

                 $view .= '<a class="btn btn-outline-warning mb-1 ml-1" href="' . route('admin.teacher_batch_list', ['id' => $q->id]) . '">' . 'Batch' . '</a>';
                 $view .= '<a class="btn btn-outline-primary mb-1 ml-1" href="' . route('admin.teachers_availability', ['id' => $q->id]) . '">' . 'Availability' . '</a>';

                 $view .= '<a class="btn btn-outline-primary mb-1 ml-1" href="' . route('admin.teacher-ppt-list', ['id' => $q->id]) . '">' . 'PPT' . '</a>';

                return $view;

            })
            ->editColumn('status', function ($q) {
                $html = html()->label(html()->checkbox('')->id($q->id)
                ->checked(($q->active == 1) ? true : false)->class('switch-input switch-input-r')->attribute('data-id', $q->id)->value(($q->active == 1) ? 1 : 0).'<span class="switch-label"></span><span class="switch-handle"></span>')->class('switch switch-lg switch-3d switch-primary');
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
            ->editColumn('is_star', function ($q) {
                $html = html()->label(html()->checkbox('')->id($q->id)
                ->checked(($q->is_star == 1) ? true : false)->class('switch-input switch-input-s')->attribute('data-id', $q->id)->value(($q->is_star == 1) ? 1 : 0).'<span class="switch-label"></span><span class="switch-handle"></span>')->class('switch switch-lg switch-3d switch-primary');
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
             ->addColumn('star_rating', function ($q) {
                $tp = TeacherProfile::where("user_id",$q->id)->first();
                $star=0;
                if($tp){
                    $star=$tp->star_rating;
                }
                $html = '<input type="number" class="form-control cstar" min="0" max="5" data-id="'.$q->id.'" value="'.$star.'" />';
                return $html;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
              ->addColumn('image', function ($q) {
                
                return '<img src= "'.$q->picture.'"   onerror="this.src=\'/profile-user.png\'" style="height:35px;width:35px;border-radius: 50%;" />';
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
            ->addColumn('subject_teach', function ($q) {
                 $tp = TeacherProfile::where('user_id',$q->id)->first();
                 $subjects="";
                 if($tp){
                 if($tp->subject_teach){
                 $tjon =json_decode($tp->subject_teach,true);

                 if(is_array($tjon)){
                 foreach ($tjon as $v) {
                     $subjects .= $v."<br/> ";
                 }
             }else{
               $subjects =  $tp->subject_teach;
             }

             }
         }
                return $subjects;
                // return ($q->active == 1) ? "Enabled" : "Disabled";
            })
           
            ->rawColumns(['actions', 'image', 'status','subject_teach','is_star','star_rating'])
            ->make();
    }

    /**
     * Show the form for creating new Category.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.teachers.create');
    }

    public function teacherBatchlist($id)
    {
        $teacher_list = User::find($id);

        $teacher_batch_list = TeacherBatch::where('tid',$teacher_list->id)->get();

         $list=array();

        foreach($teacher_batch_list as $teacher_batch){

            $teacher_batch["batches"]=Batch::find($teacher_batch->bid);

            $teacher_batch["course"]=Course::where("id",$teacher_batch["batches"]->cid)->first();
             
            $list[]=$teacher_batch;
         

        }
        return view('backend.teachers.teacher-batch',compact('list'));
    }

    public function teachercourseList()
    {

        $courses = Course::ofTeacher()->whereHas('category')->orderBy('created_at', 'desc')->get();
        return view('backend.teachers.teacher-course',compact('courses'));
    }

    public function teacherstudentList()
    {
       $find_student_list = StudentTeacherBatch::where('tid',auth()->user()->id)->get();

       $list=array();

        foreach($find_student_list as $find_student){

            $find_student["student"]=User::where('id',$find_student->uid)->first();
            $find_student["batches"]=Batch::find($find_student->bid);
             
            $list[]=$find_student;
         
        }

          return view('backend.teachers.student-list',compact('list'));
    }

    public function teacherbankdetailStore(Request $request)
    {
        $this->validate($request, [

             'bank_name' => 'required',
             'ifsc_code' => 'required',
             'account_number' => 'required',
             'account_name' => 'required',

        ],[
            'bank_name.required' => 'Kindly Enter Bank Name',
            'ifsc_code.required' => 'Kindly Enter IFSC Code',
            'account_number.required' => 'Kindly Enter Account Number',
            'account_name.required' => 'Kindly Enter Account Name',

        ]);


        $payment_details = [
            'bank_name'         => $request->bank_name,
            'ifsc_code'         => $request->ifsc_code,
            'account_number'    => $request->account_number,
            'account_name'      => $request->account_name,
        ];

        $teacher = TeacherProfile::where('user_id',auth()->user()->id)->first();
        $teacher->payment_method = 'bank';
        $teacher->payment_details = json_encode($payment_details);
        $teacher->update();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.updated'));

    }

    public function teacherdocumentapproofStore(Request $request)
    {
         $this->validate($request, [

             'aadhar_card_photo' => 'required|mimes:png,jpg,jpeg|max:1024',
             'pan_card_photo' => 'required|mimes:png,jpg,jpeg|max:1024',
             'photo_id_proof' => 'required|mimes:png,jpg,jpeg|max:1024',

        ],[
            'aadhar_card_photo.required' => 'Kindly Enter Bank Name',
            'aadhar_card_photo.max' => 'File Size should be less than 1MB',
            'aadhar_card_photo.mimes' => 'File Size should be png,jpg,jpeg',
            'pan_card_photo.required' => 'Kindly Enter IFSC Code',
            'pan_card_photo.max' => 'File Size should be less than 1MB',
            'pan_card_photo.mimes' => 'File Size should be png,jpg,jpeg',
            'photo_id_proof.required' => 'Kindly Enter Account Number',
            'photo_id_proof.max' => 'File Size should be less than 1MB',
            'photo_id_proof.mimes' => 'File Size should be png,jpg,jpeg',

        ]);


        $teacher = TeacherProfile::where('user_id',auth()->user()->id)->first();

        if ($request->has('aadhar_card_photo')) {
            $aadharp=time().".".$request->aadhar_card_photo->getClientOriginalExtension();
             $request->aadhar_card_photo->move(public_path('storage/profiles'), $aadharp);
           
            $teacher->aadhar_card = 'storage/profiles/'.$aadharp;

        }

        if ($request->has('pan_card_photo')) {
            $panp=time().".".$request->pan_card_photo->getClientOriginalExtension();
             $request->pan_card_photo->move(public_path('storage/profiles'), $panp);
           
            $teacher->pan_card = 'storage/profiles/'.$panp;
        }

        if ($request->has('photo_id_proof')) {
            $idproofp=time().".".$request->photo_id_proof->getClientOriginalExtension();
             $request->photo_id_proof->move(public_path('storage/profiles'), $idproofp);
           
            $teacher->photo_id_proof = 'storage/profiles/'.$idproofp;
        }

        $teacher->update();

        return redirect()->back()->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    public function teacherattendanceList(Request $request)
    {
 $from = date("Y-m-01");
            $to = date("Y-m-d");
        if(auth()->user()->hasRole('teacher')){

       
           $teacher_attendance_list = TeacherAttendance::with('teacher')->with('batch')->orderBy('id','desc')->where('teacher_id',auth()->user()->id)->get();

            return view('backend.teachers.teacher_attendance_list',compact('teacher_attendance_list'));
       
        }elseif(auth()->user()->hasRole('administrator')){

           

          

              $batches = null;
            $tutors = User::role('teacher')->get();
            if($request->tid){

                $tbs = TeacherBatch::where("tid",$request->tid)->get()->pluck('bid');
                
                $batches = Batch::whereIn("id",$tbs)->orderBy("id","desc")->get();
            }
            

             $teacher_attendance_list = TeacherAttendance::with('teacher')->with('batch')->orderBy('id','desc');

             if($request->tid){
               $teacher_attendance_list = $teacher_attendance_list->where("teacher_id",$request->tid); 
             }
             if($request->bid){
               $teacher_attendance_list = $teacher_attendance_list->where("batch_id",$request->bid); 
             }
               if($request->from){
                $from = date("Y-m-d",strtotime($request->from));
                $teacher_attendance_list = $teacher_attendance_list->where("date",">=",$from);
            }

            if($request->to){
                $to = date("Y-m-d",strtotime($request->to));
                $teacher_attendance_list = $teacher_attendance_list->where("date","<=",$to);
            }


             $tnx =  $teacher_attendance_list->sum('hours');
              $teacher_attendance_list= $teacher_attendance_list->get();

            return view('backend.teachers.teacher_attendance_list',compact('teacher_attendance_list','batches','tutors','tnx','from','to'));
           
        }

       
    }

    public function teacherattendanceCreate()
    {
        $batch_list = Batch::orderBy('id','desc')->get();

       return view('backend.teachers.teacher_attendance_create',compact('batch_list'));
    }

    public function teacherattendanceStore(Request $request)
    {
       $this->validate($request, [
            'batch_id' => 'required',
            'hours' => 'required',
            'date' => 'required',

        ], [
            'batch_id.required' => 'Kindly Select Batch',
            'hours.required' => 'Kindly Enter Hours',
            'date.required' => 'Kindly Enter Date',

        ]);

        $teacher_attendance = new TeacherAttendance();
        $teacher_attendance->teacher_id = $request->teacher_id;
        $teacher_attendance->batch_id = $request->batch_id;
        $teacher_attendance->hours = $request->hours;
        $teacher_attendance->date = $request->date;
        $teacher_attendance->save();
        return redirect()->route('admin.teacher_attendance')->withFlashSuccess(trans('alerts.backend.general.created'));
    }

    public function teacherWisepayment($id,Request $request)
    {

        $bid = 0;
        if($request->bid){
            $bid=$request->bid;
        }

             $er = new Earning();
        $earningHelper  = new EarningHelper();
        // $total_earnings = $earningHelper->totalEarning();
        $total_earnings = $er->totalEarnings($id,$bid);
        $total_withdrawal = $earningHelper->totalWithdrawal($id);
        $total_withdrawal_pending = $earningHelper->totalWithdrawalPending($id);
        $total_balance = $total_earnings - ($total_withdrawal+$total_withdrawal_pending);

        $withdrawls = Withdraw::where('user_id',$id)->orderBy('id','desc')->get();
        if( $bid==0){
       $tas = TeacherBatch::where('tid',$id)->orderBy("id","desc")->get();
}else{
   $tas = TeacherBatch::where('tid',$id)->where('bid',$bid)->orderBy("id","desc")->get();  
}

        $tp = TeacherProfile::where('user_id',$id)->first();
        $bank=null;
        if($tp){
            if($tp->payment_details){
$bank = json_decode($tp->payment_details,true);
            }

        }

    

        $teacher_name = User::find($id);

        $tbs = TeacherBatch::where('tid',$id)->get();

        $batches = [];

        foreach($tbs as $b){
           $batch = Batch::find($b->bid);
           if($batch){
             $batches[] = $batch;
           }
        }



     

        return view('backend.teachers.teacher_wise_payment_list',compact('withdrawls','total_earnings','total_withdrawal','total_withdrawal_pending','total_balance','teacher_name','tas','bank','batches','id'));
    }

    /**
     * Store a newly created Category in storage.
     *
     * @param  \App\Http\Requests\StoreTeachersRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeachersRequest $request)
    {
//        $request = $this->saveFiles($request);

        $teacher = User::create($request->all());
        $teacher->confirmed = 1;
        if ($request->image) {
            $teacher->avatar_type = 'storage';
            $teacher->avatar_location = $request->image->store('/storage', 'public');
        }
        $teacher->active = isset($request->active)?1:0;
        $teacher->on_home = $request->on_home;
        $teacher->save();
        $teacher->title       = $request->title;
        $teacher->assignRole('teacher');

        $payment_details = [
            'bank_name'         => request()->payment_method == 'bank'?request()->bank_name:'',
            'ifsc_code'         => request()->payment_method == 'bank'?request()->ifsc_code:'',
            'account_number'    => request()->payment_method == 'bank'?request()->account_number:'',
            'account_name'      => request()->payment_method == 'bank'?request()->account_name:'',
            'paypal_email'      => request()->payment_method == 'paypal'?request()->paypal_email:'',
        ];
        $data = [
            'user_id'           => $teacher->id,
            'facebook_link'     => request()->facebook_link,
            'twitter_link'      => request()->twitter_link,
            'linkedin_link'     => request()->linkedin_link,
            'instagram_link'     => request()->instagram_link,
            'payment_method'    => request()->payment_method,
            'payment_details'   => json_encode($payment_details),
            'description'       => request()->description,
        ];
        TeacherProfile::create($data);


        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.created'));
    }


    /**
     * Show the form for editing Category.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $teacher = User::findOrFail($id);

        $teacher_profile = TeacherProfile::where('user_id',$teacher->id)->first();
// dd($teacher_profile);
        if($teacher_profile->payment_details == NULL)
        {

            $payment_details = [
            'bank_name'         => NULL,
            'ifsc_code'         => NULL,
            'account_number'    => NULL,
            'account_name'      => NULL,
        ];

        
        $teacher_profile->payment_method = 'bank';
        $teacher_profile->payment_details = json_encode($payment_details);
        $teacher_profile->update();
    

            return view('backend.teachers.edit', compact('teacher'));

        }else{

           
            return view('backend.teachers.edit', compact('teacher'));
        }

    }
 
    /**
     * Update Category in storage.
     *
     * @param  \App\Http\Requests\UpdateTeachersRequest $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTeachersRequest $request, $id)
    {
//        $request = $this->saveFiles($request);

        $teacher = User::findOrFail($id);
       
        $teacher->update($request->except('email'));
        // dd($request->all());
        // if ($request->has('image')) {
        //     $teacher->avatar_type = 'storage'; 
        //     $fname=time().".".$request->image->getClientOriginalExtension();
        //      $request->image->move(public_path('storage/profiles'), $fname);
        //     // $x = $request->image->store('/storage', 'public');
        //     // dd($x);
        //     $teacher->avatar_location = 'profiles/'.$fname;
        // }

          if($request->hasFile('image')){
        $image = $request->file('image');
        $filename = time().rand(10,99).$image->getClientOriginalName();
        $image->move(public_path('storage/uploads/'), $filename);
        $teacher->avatar_type='storage';
        $teacher->avatar_location = "uploads/".$filename;
}


        $teacher->title       = $request->title;
        $teacher->active = isset($request->active)?1:0;
        $teacher->on_home = $request->on_home;
        $teacher->middle_name = $request->middle_name;
        $teacher->dob = $request->dob;
        $teacher->country = $request->country;
        $teacher->city = $request->city;
        $teacher->save();

        $payment_details = [
            'bank_name'         => request()->payment_method == 'bank'?request()->bank_name:'',
            'ifsc_code'         => request()->payment_method == 'bank'?request()->ifsc_code:'',
            'account_number'    => request()->payment_method == 'bank'?request()->account_number:'',
            'account_name'      => request()->payment_method == 'bank'?request()->account_name:'',
           // 'paypal_email'      => request()->payment_method == 'paypal'?request()->paypal_email:'',
        ];
        $data = [
             'user_id'           => $teacher->id,
            'facebook_link'     => request()->facebook_link,
            'twitter_link'      => request()->twitter_link,
            'linkedin_link'     => request()->linkedin_link,
            'instagram_link'     => request()->instagram_link,
            'hig_qualification'     => request()->hig_qualification,
            'total_exp'     => request()->total_exp,
            'relevant_exp'     => request()->relevant_exp,
            'subject_teach'     => request()->subject_teach,
            'grade_teach'     => request()->grade_teach,
            'lang_proficiency'     => request()->lang_proficiency,
            'payment_method'    => request()->payment_method,
            'payment_details'   => json_encode($payment_details),
            'description'       => request()->description,
            'subject'       => request()->subject,
        ];

        if ($request->has('upload_cv')) {
            $aadharp=time().".".$request->upload_cv->getClientOriginalExtension();
             $request->upload_cv->move(public_path('storage/upload_cv'), $aadharp);
           
            $data["upload_cv"] =  'storage/upload_cv/'.$aadharp;

        }
             if ($request->has('aadhar_card')) {
            $aadharp=time().".".$request->aadhar_card->getClientOriginalExtension();
             $request->aadhar_card->move(public_path('storage/aadhar_card'), $aadharp);
           
            $data["aadhar_card"] =  'storage/aadhar_card/'.$aadharp;

        }

        if ($request->has('pan_card')) {
            $panp=time().".".$request->pan_card->getClientOriginalExtension();
             $request->pan_card->move(public_path('storage/pan_card'), $panp);
           
            $data["pan_card"] = 'storage/pan_card/'.$panp;
        }

        if ($request->has('photo_id_proof')) {
            $idproofp=time().".".$request->photo_id_proof->getClientOriginalExtension();
             $request->photo_id_proof->move(public_path('storage/photo_id_proof'), $idproofp);
           
            $data["photo_id_proof"] = 'storage/photo_id_proof/'.$idproofp;
        }


        $teacher->teacherProfile->update($data);


        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

 
    /**
     * Display Category.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $teacher = User::findOrFail($id);

        return view('backend.teachers.show', compact('teacher'));
    }


    /**
     * Remove Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $teacher = User::findOrFail($id);
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }

    /**
     * Delete all selected Category at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {

        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }


    /**
     * Restore Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->restore();

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.restored'));
    }

    /**
     * Permanently delete Category from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function perma_del($id)
    {

        $teacher = User::onlyTrashed()->findOrFail($id);
        $teacher->teacherProfile->delete();
        $teacher->forceDelete();

        return redirect()->route('admin.teachers.index')->withFlashSuccess(trans('alerts.backend.general.deleted'));
    }


    /**
     * Update teacher status
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    public function updateStatus(Request $request)
    {
        $teacher = User::find(request('id'));
        if($request->type=='cstar'){

            $tp = TeacherProfile::where("user_id",$teacher->id)->first();
            if($tp){
                $tpx = TeacherProfile::find($tp->id);
            $tpx->star_rating = $request->star;
            $tpx->update();
        }
        }else{
        if($request->type=='r'){
         if($teacher->active==0){
            $el = new Elearn;
            $el->tutorActivationSMS($teacher->name,$teacher->phone);

         Mail::to($teacher)->send(new TeacherActivationEmail($teacher));
     }
        $teacher->active = $teacher->active == 1? 0 : 1;
        $teacher->save();
    }else{
       $teacher->is_star = $teacher->is_star == '1'? '0' : '1';
        $teacher->save(); 
    }
}

    }
}
