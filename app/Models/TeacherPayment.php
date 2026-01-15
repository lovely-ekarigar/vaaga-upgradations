<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TeacherPayment extends Model
{

    use SoftDeletes;
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'teacher_id', 'payment_mode', 'amount', 'date','remark'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->belongsTo(User::class);
    }
}
