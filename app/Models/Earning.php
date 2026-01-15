<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\TeacherAttendance;
use App\Models\TeacherBatch;
use App\Models\Batch;
use App\Models\TeacherFee;
use App\Helpers\General\EarningHelper;
use Illuminate\Database\Eloquent\Model;

class Earning extends Model
{
    protected $guarded = [];

    /**
    * Get the teacher that owns earning.
    */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
    * Get the order that owns earning.
    */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
    * Get the course that owns earning.
    */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function totalBalance($id){

       
        $earningHelper  = new EarningHelper();
        // $total_earnings = $earningHelper->totalEarning();
        $total_earnings = $this->totalEarnings($id);
        $total_withdrawal = $earningHelper->totalWithdrawal($id);
        $total_withdrawal_pending = $earningHelper->totalWithdrawalPending($id);
        $total_balance = $total_earnings - ($total_withdrawal);

        return $total_balance;
    }

    public function getBatchHours($id,$bid=0){
      if($bid==0){
            $tbs = TeacherAttendance::where('teacher_id',$id)->get();
        }else{
            $tbs = TeacherAttendance::where('teacher_id',$id)->where("batch_id",$bid)->get();
        }
         $total=0;

        foreach($tbs as $t){

            $total += $t->hours ;
        } 

        return $total;
    }

public function totalEarningsBatch($id=0,$bid=0){

        if($bid==0){
            $tbs = TeacherAttendance::where('teacher_id',$id)->get();
        }else{
            $tbs = TeacherAttendance::where("batch_id",$bid)->get();
        }

        $total=0;

        foreach($tbs as $t){

            $total += ($t->hours * $t->fees/60);
        }


       

return ceil($total);
    }


    public function totalEarnings($id,$bid=0){

        if($bid==0){
            $tbs = TeacherAttendance::where('teacher_id',$id)->get();
        }else{
            $tbs = TeacherAttendance::where('teacher_id',$id)->where("batch_id",$bid)->get();
        }

        $total=0;

        foreach($tbs as $t){

            $total += ($t->hours * $t->fees/60);
        }


       

return ceil($total);
    }

}
