<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lesson;
use App\Models\Recording;
class Batch extends Model
{
    protected  $guarded = [];




    public function bacthCompletion($batch_id,$tid=null){

        $batch = Batch::find($batch_id);
        $total=0;
        $completed = 0;
        if($batch){

            $total = Lesson::where("course_id",$batch->cid)->count();
            if($tid){
 $completed = Recording::where('parent',$batch->parent_api_class_id)->where('tid',$tid)->distinct()->count('lesson_id');
            }else{
            $completed = Recording::where('parent',$batch->parent_api_class_id)->distinct()->count('lesson_id');
        }

        }


        return array("total"=>$total,"completed"=>$completed);
    }
    
}
 