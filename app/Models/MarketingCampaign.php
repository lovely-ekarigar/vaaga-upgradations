<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingCampaign extends Model
{
    use SoftDeletes;

    protected $table = 'marketing_campaigns';

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'audience',
        'subject',
        'grade',
        'skip',
        'list_id',
        'aisensy_campaign_id',
    ];

    public function leads()
    {
        return $this->belongsToMany(MarketingLead::class, 'marketing_campaign_lead', 'campaign_id', 'lead_id');
    }

    public function list()
    {
        return $this->belongsTo(MarketingList::class, 'list_id');
    }

    public function getLeadsCountAttribute()
    {
        return $this->leads()->count();
    }
}
