<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Auth\User;
use App\Models\Bundle;
use App\Models\Contact;
use App\Models\Course;
use App\Models\Order;
use App\Models\Batch;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\DemoRequest;
use App\Models\StudentTeacherBatch;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
/**
 * Class DashboardController. 
 */
class StudentController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
      $users = User::role('student')->orderBy('id','desc');


        if($request->type == 'active'){

            $users =$users->where('active','1');
        }

        if($request->type=='non-active'){ 
         
            $users =$users->where('active','0');   
        }
        if($request->show_deleted){


        $users =$users->onlyTrashed()->get();
    }else{
  $users =$users->get();
    }

        return view('backend.student.index',compact('users'));
    }

    public function studentRecover($id){

        $user = User::withTrashed()->find($id);
        if (!$user) {
            return redirect()->back()->withFlashDanger('Student not found');
        }
        $user->deleted_at=null;
        $user->update();
         return redirect()->back()->withFlashSuccess('Student recovered sucessfully');
    }

    public function orders($id){
           $user = User::find($id);
           if (!$user) {
               return redirect()->back()->withFlashDanger('Student not found');
           }
           $orders = Order::where('user_id',$id)->orderBy('updated_at', 'desc')->get();
           return view('backend.student.orders',compact('orders','user'));
    }

    public function getData(Request $request)
    {
       $students = User::role('student')->orderBy('id','desc')->get();

        return Collection::of($students)->make(true);
    }

    public function studentShow($id)
    {
      $student = User::find($id);
      if (!$student) {
          return redirect()->back()->withFlashDanger('Student not found');
      }
      return view('backend.student.show',compact('student'));
    }

    public function studentEdit($id)
    {
      $student = User::find($id);
      if (!$student) {
          return redirect()->back()->withFlashDanger('Student not found');
      }
      return view('backend.student.edit',compact('student'));
    }

    public function studentUpdate(Request $request,$id)
    {
        $this->validate($request, [
            'first_name' => 'required',
            // 'middle_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            // 'gender' => 'required',
            // 'state' => 'required',

        ], [
            'first_name.required' => 'Kindly Enter First Name',
            'middle_name.required' => 'Kindly Enter Middle Name',
            'last_name.required' => 'Kindly Enter Last Name',
            'email.required' => 'Kindly Enter Email',
            'phone.required' => 'Kindly Enter Phone',
            'gender.required' => 'Kindly Select Gender',
            'state.required' => 'Kindly Enter State Name',

        ]);

          $student = User::find($id);
        $student->first_name = $request->first_name;
        $student->middle_name = $request->middle_name;
        $student->last_name = $request->last_name;
        $student->email  = $request->email;
        $student->dob = $request->dob;
        $student->phone = $request->phone;
        $student->gender = $request->gender;
        $student->address = $request->address;
        $student->city = $request->city;
        $student->pincode = $request->pincode;
        $student->state = $request->state;
        $student->country = $request->country;

        // if ($request->has('image')) {
        //     $student->avatar_type = 'storage'; 
        //     $fname=time().".".$request->image->getClientOriginalExtension();
        //      $request->image->move(public_path('storage/profiles'), $fname);
            
        //     $student->avatar_location = 'storage/profiles/'.$fname;
        // }

              if($request->hasFile('image')){
        $image = $request->file('image');
        $filename = time().rand(10,99).$image->getClientOriginalName();
        $image->move(public_path('storage/uploads/'), $filename);
        $student->avatar_type='storage';
        $student->avatar_location = "uploads/".$filename;
}


        $student->update();
        return redirect()->route('admin.students.index')->withFlashSuccess(trans('alerts.backend.general.updated'));
    }

    public function studentDelete($id)
    {
        $student=User::find($id);
        if (!$student) {
            return redirect()->back()->withFlashDanger('Student not found');
        }
        
        $student->delete();

        return redirect()->back()->withFlashDanger("Student has been Deleted");
    }

    public function updatestatus(Request $request)
    {
        //dd($request->id);
        $student = User::find($request->id);
        if (!$student) {
            return redirect()->back()->withFlashDanger('Student not found');
        }
        
        $student->active = $student->active == 1? 0 : 1;
        $student->save();
        return back();
    }

    public function studentBatchlist($id)
    {
        $student_batch = StudentTeacherBatch::where('uid',$id)->get();

         $list=array();

        foreach($student_batch as $student){

            $student["batches"]=Batch::find($student->bid);

            if ($student["batches"] === null) {
                continue;
            }

            $student["course"]=Course::where("id",$student["batches"]->cid)->first();
             
            $list[]=$student;
         
        }
       return view('backend.student.student-batch',compact('list'));
    }
}
