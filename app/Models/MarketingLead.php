<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingLead extends Model
{
    use SoftDeletes;

    protected $table = 'marketing_leads';

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
        return $this->belongsToMany(MarketingCampaign::class, 'marketing_campaign_lead', 'lead_id', 'campaign_id');
    }

    public function lists()
    {
        return $this->belongsToMany(MarketingList::class, 'marketing_list_lead', 'lead_id', 'list_id');
    }
}
