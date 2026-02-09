<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
 

    protected $fillable = [
        'name',
        'description',
        'type',
        'status',
        'audience',
        'grade',
        'subject',
        'skip',
        'aisensy_campaign_id' // Add this line
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the leads that belong to the campaign.
     */
    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'campaign_lead');
    }

    /**
     * Get the leads count for the campaign.
     */
    public function getLeadsCountAttribute()
    {
        return $this->leads()->count();
    }

    /**
     * Scope a query to only include active campaigns.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}