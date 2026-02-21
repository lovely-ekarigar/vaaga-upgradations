<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketingLead extends Model
{
    protected $table = 'leads';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'status',
        'grade',
        'subject',
        'skip',
        'notes',
    ];

    public function campaigns()
    {
        return $this->belongsToMany(MarketingCampaign::class, 'campaign_lead', 'lead_id', 'campaign_id');
    }

    public function lists()
    {
        return $this->belongsToMany(MarketingList::class, 'lead_list_items', 'lead_id', 'list_id');
    }
}
