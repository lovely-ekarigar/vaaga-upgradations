<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\TeacherProfile;
use App\Models\Batch;
use App\Models\TeacherBatch;
use App\Models\DemoRequest;
use Illuminate\Database\Eloquent\Model;

class Unavailability extends Model
{
    protected $guarded = [];

    /**
    * Get the teacher profile that owns the user.
    */

    public function getAvailSlot($tid,$total=30){

        $user = User::find($tid);
 $tbs = TeacherBatch::where("tid",$tid)->where("active","1")->get();

        $dateList = [];
        $leaveDateList = [];

        foreach($tbs as $tb){

            $batch = Batch::find($tb->id);

            if($batch){
            $start = $batch->start_date;
            $end = $batch->end_date;
            $days = json_decode($batch->occur,true);

            $date = $start;
            while($date<=$end){

                $cd = date("w", strtotime($date));
                if($cd==0){
                    $cd = 7;
                }
                if(in_array($cd-1, $days)){
                    $dateList[$date][] = 

                    array(
                        
                        
                        "start"=>date("h:iA",strtotime($batch->start_time)),
                        "end"=>date("h:iA",strtotime($batch->end_time)),
                      
                        
                    );
                }

                $date = date("Y-m-d",strtotime("+1 days",strtotime($date)));

            }
        }

        }

        $unlist = Unavailability::where("user_id",$tid)->get();

        foreach($unlist as $un){

             $dateList[date("Y-m-d",strtotime($un->date_time))][] =  array(
                        
                        "start"=>"00:00AM",
                        "end"=>"11:59PM",
                        
                        
                    );
             $leaveDateList[] = date("Y-m-d",strtotime($un->date_time));
             // dd($unx);
             // $dateList[] = $unx;

        }

        $demos = DemoRequest::where('teacher_id',$tid)->get();

        foreach($demos as $un){

             $dateList[date("Y-m-d",strtotime($un->demo_date_time))][] =  array(
                 "start"=>date("h:iA",strtotime($un->demo_date_time)),
                        "end"=>date("h:iA",strtotime("+1 hours",strtotime($un->demo_date_time))),
                       

                    
                        
                    );

        }


        $tp = TeacherProfile::where('user_id',$tid)->first();
$days  = array('monday','tuesday','wednesday','thursday','friday','saturday','sunday');
 $ex = ["s"=>[],"e"=>[]];
    if(!$tp){
      $data = array('monday'=>$ex,'tuesday'=>$ex,'wednesday'=>$ex,'thursday'=>$ex,'friday'=>$ex,'saturday'=>$ex,'sunday'=>$ex); 
    }else{
      

     if(!$tp->availability){
       
       $data = array('monday'=>$ex,'tuesday'=>$ex,'wednesday'=>$ex,'thursday'=>$ex,'friday'=>$ex,'saturday'=>$ex,'sunday'=>$ex); 
     }else{
        $data = json_decode($tp->availability,true);
     }
 }

$freeSlot=[];
$freeSlotIn=[];
$dates=[];
$finaldateList=[];

// dd($dateList);
for($i=0;$i<=$total;$i++){

$ndate = date("Y-m-d",strtotime("+".$i." days",time()));
$dates[] = $ndate;
$weekDay = date("w",strtotime($ndate));
 if($weekDay==0){
                    $weekDay = 7;
                }
$daySlot = $data[$days[$weekDay-1]];
$freeSlots=[];

foreach($daySlot["s"] as $k=>$ds){

    $freeSlots[] =array("start"=>date("h:iA",strtotime($ndate." ".$daySlot["s"][$k])),"end"=>date("h:iA",strtotime($ndate." ".$daySlot["e"][$k])),"c"=>"0x");
}

if(array_key_exists($ndate,$dateList)){
foreach ($dateList[$ndate] as $bkslot) {
$freeSlots = $this->intersectTimeslots($freeSlots, $bkslot,$ndate);
}
}


foreach($freeSlots as $fs){
    if(strtotime($ndate." ".$fs["start"]) < strtotime($ndate." ".$fs["end"])){
 $finaldateList[] = 

                    array(
                         "groupId"=>$user->id,
                        "title"=>$user->name." Free Slot",
                        
                        "start"=>$ndate."T".date("H:i:s",strtotime($ndate." ".$fs["start"]))."+00:00",
                        "end"=>$ndate."T".date("H:i:s",strtotime($ndate." ".$fs["end"]))."+00:00",
                        "extendedProps"=>array("url"=>"","description"=>"Free Slot  available between ".date("h:i A",strtotime($ndate." ".$fs["start"]))." to ".date("h:i A",strtotime($ndate." ".$fs["end"])),"background"=>"#5bf16187",),
                        
                        "textColor"=>"black",
                        
                    );

                }
            }





}







                return $finaldateList;


    }



function intersectTimeslots($fslts, $bkslts,$ndate)
{
    // print_r($fslts);
    $freeSlot=[];
   
    for($j=0;$j<count($fslts);$j++){
$x1 = strtotime($ndate." ".$fslts[$j]["start"]);//slot
$x2 = strtotime($ndate." ".$fslts[$j]["end"]);


    $x3 = strtotime($ndate." ".$bkslts["start"]);//slot
$x4 = strtotime($ndate." ".$bkslts["end"]);


        $n1 = $x3 - $x1;
        $n2 = $x4 - $x2;

        if($x3<$x2 && $x4<$x1){
            $freeSlot[] = array("start"=>date("h:iA",$x1),"end"=>date("h:iA",$x2),"c"=>"c00");

        }else if($x2<$x3 && $x2<$x4){
            $freeSlot[] = array("start"=>date("h:iA",$x1),"end"=>date("h:iA",$x2),"c"=>"c0");
        }else{



            if($n1>=0 && $n2>=0){

                $freeSlot[] = array("start"=>date("h:iA",$x1),"end"=>date("h:iA",$x3),"c"=>"c1");

            }else if($n1>=0 && $n2<=0){
                  $freeSlot[] = array("start"=>date("h:iA",$x1),"end"=>date("h:iA",$x3),"c"=>"c2x");
                    $freeSlot[] = array("start"=>date("h:iA",$x4),"end"=>date("h:iA",$x2),"c"=>"c2");

            }else if($n1<=0 && $n2>=0){


            }else if($n1<=0 && $n2<=0){
 $freeSlot[] = array("start"=>date("h:iA",$x4),"end"=>date("h:iA",$x2),"c"=>"c4");
            }


}

}

return $freeSlot;
}



    public function getTeacherAvail($tid){


        $tbs = TeacherBatch::where("tid",$tid)->where("active","1")->get();

        $dateList = [];

        foreach($tbs as $tb){

            $batch = Batch::find($tb->id);

            if($batch){
            $start = $batch->start_date;
            $end = $batch->end_date;
            $days = json_decode($batch->occur,true);

            $date = $start;
            while($date<=$end){

                $cd = date("w", strtotime($date));
                if(in_array($cd-1, $days)){
                    $dateList[] = 

                    array(
                        "groupId"=>$batch->id,
                        "title"=>$batch->name,
                        
                        "start"=>$date."T".date("H:i:s",strtotime($date." ".$batch->start_time))."+00:00",
                        "end"=>$date."T".date("H:i:s",strtotime($date." ".$batch->end_time))."+00:00",
                        "extendedProps"=>array("url"=>env('APP_URL')."/user/myclass/".$batch->id,"description"=>"Classes for batch start from ".$batch->start_time." to ".$batch->end_time,"background"=>"#f965648c"),
                        
                    );
                }

                $date = date("Y-m-d",strtotime("+1 days",strtotime($date)));

            }
        }

        }

        $unlist = Unavailability::where("user_id",$tid)->get();

        foreach($unlist as $un){

             $dateList[] =  array(
                        
                        "title"=>$un->reason,
                        
                        "start"=>$un->date_time,
                        
                        "extendedProps"=>array("url"=>"x","description"=>"Marked  Unavailability with reason: ".$un->reason,"background"=>"#ffc107","lvid"=>$un->id),
                        
                        "textColor"=>"black",
                        
                    );

        }

        $demos = DemoRequest::where('teacher_id',$tid)->get();

        foreach($demos as $un){

             $dateList[] =  array(
                        
                        "title"=>"Demo Scheduled",
                        
                        "start"=>date("Y-m-d",strtotime($un->demo_date_time))."T".date("H:i:s",strtotime($un->demo_date_time))."+00:00",
                        "end"=>date("Y-m-d",strtotime("+1 hours",strtotime($un->demo_date_time)))."T".date("H:i:s",strtotime("+1 hours",strtotime($un->demo_date_time)))."+00:00",
                        
                        "extendedProps"=>array("url"=>"","description"=>"Demo Scheduled with student ".$un->name." at ".$un->demo_date_time,"background"=>"#5bfbec8c"),
                        
                        "textColor"=>"black",
                        
                    );

        }



return $dateList;

    }
   
}
