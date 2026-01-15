<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded = [];
    protected $table="notes";
    
    public function category()
    {
        return $this->hasOne(NoteCategory::class,'id','category_id');
    }
}
