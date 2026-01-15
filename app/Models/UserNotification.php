<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\TeacherAttendance;
use App\Models\Notification;
use App\Models\Batch;
use App\Models\TeacherFee;
use App\Helpers\General\EarningHelper;
use Illuminate\Database\Eloquent\Model;

class UserNotification extends Model
{


public function notification(){

	return $this->hasOne(Notification::class,'id','notification_id');
}

}