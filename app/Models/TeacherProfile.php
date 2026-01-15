<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'facebook_link','upload_cv','aadhar_card','pan_card','photo_id_proof', 'twitter_link', 'linkedin_link','hig_qualification','total_exp','relevant_exp','subject_teach','grade_teach','lang_proficiency', 'payment_method', 'payment_details', 'description','base_salary','subject'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->belongsTo(User::class); 
    }
}
