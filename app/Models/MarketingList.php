<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingList extends Model
{
    use SoftDeletes;

    protected $table = 'marketing_lists';

    protected $fillable = [
        'name',
        'description',
    ];

    public function leads()
    {
        return $this->belongsToMany(MarketingLead::class, 'marketing_list_lead', 'list_id', 'lead_id');
    }
}
