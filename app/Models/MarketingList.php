<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingList extends Model
{
    protected $table = 'lead_lists';

    protected $fillable = [
        'name',
        'description',
    ];

    public function leads()
    {
        return $this->belongsToMany(MarketingLead::class, 'lead_list_items', 'list_id', 'lead_id');
    }
}
