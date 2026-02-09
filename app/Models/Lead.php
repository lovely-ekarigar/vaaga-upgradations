<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
 

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'status',
        'grade',
        'subject',
        'skip',
        'notes' // Add this line
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the campaigns that belong to the lead.
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_lead');
    }

    /**
     * Scope a query to only include active leads.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive leads.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}