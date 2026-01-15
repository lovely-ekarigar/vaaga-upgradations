<?php

namespace App\Http\Controllers\Backend;

use Auth;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\General\EarningHelper;
use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\Batch;
use App\Models\Auth\User;
use App\Models\StudentTeacherBatch;
use App\Models\TeacherBatch;
use App\Mail\Frontend\Demo\TeacherPaymentEmail;
use Mail;
class NotificationController extends Controller
{


	public function index(){


		$notifications = Notification::orderBy("id","desc")->get();

		return view('backend.notifications.admin',compact('notifications'));

	}

	public function teacher(){


		$notifications = UserNotification::with('notification')->where('user_id',Auth::user()->id)->orderBy("id","desc")->get();
			$unotifications = UserNotification::where('user_id',Auth::user()->id)->where('status','0')->get();
			foreach($unotifications as $n){

				$nf = UserNotification::find($n->id);

				$nf->status='1';
				$nf->update();
			}

		return view('backend.notifications.teacher',compact('notifications'));


	}


	public function myNotifications(){


			$notifications = UserNotification::with('notification')->where('user_id',Auth::user()->id)->orderBy("id","desc")->get();
			$unotifications = UserNotification::where('user_id',Auth::user()->id)->where('status','0')->get();
			foreach($unotifications as $n){

				$nf = UserNotification::find($n->id);

				$nf->status='1';
				$nf->update();
			}


			return view('backend.notifications.student',compact('notifications'));
	}


	public function create(Request $request){


		$batches = Batch::orderBy('id','desc')->get();

		return view('backend.notifications.create',compact('batches'));

	}

	public function destroy(Request $request){


		$not = Notification::find($request->nid);
		if($not){
			$not->delete();
		}


		$unf = UserNotification::where('notification_id',$request->nid)->get();
		foreach($unf as $n){

			$un = UserNotification::find($n->id);
			$un->delete();
		}

return redirect()->back()->withFlashDanger("Notification has been deleted");

	}

	public function save(Request $request){

		$batch_type = $request->batch_type;
		$title = $request->title;
		$user_type = $request->user_type;
		$batch_list = $request->batch_list;
		$message = $request->message;

		$bids = [];
		$batches = Batch::orderBy("id","desc");
		if($batch_type=='active'){
			$batches = $batches->where('is_completed','0');
		}
		if($batch_type=='completed'){
			$batches = $batches->where('is_completed','1');
		}
		if($batch_type=='selected'){
			$batches = $batches->whereIn('id',$batch_list);
		}
		$batches = $batches->get();

		foreach($batches as $b){
			$bids[] = $b->id;
		}
		$students = [];
		$teachers = [];
		
if($request->user_type=='student' || $request->user_type=='both' ){
		$stbs = StudentTeacherBatch::whereIn("bid",$bids)->get();
			foreach($stbs as $s){
				$students[] = $s->uid;
			}
		}
			if($request->user_type=='tutor' || $request->user_type=='both' ){

		$tbs = TeacherBatch::whereIn("bid",$bids)->get();
		
		foreach($tbs as $t){

			$students[] = $t->tid;
		}	
	}
	$students = array_unique($students);

		$not = new Notification;
		$not->title = $title;
		$not->message = $message;
		$not->batch_type = $batch_type;
		$not->user_type = $user_type;
		$not->batch_list = json_encode($batch_list);
		$not->created_by = Auth::user()->id;
		$not->save();
		$id = $not->id;
		foreach($students as $s){

			$un = new UserNotification;
			$un->notification_id = $id;
			$un->user_id = $s;
			$un->status = '0';
			$un->save();

		}



		return redirect("/user/notifications-list")->withFlashSuccess("Notification has been created successfully");



	}



}