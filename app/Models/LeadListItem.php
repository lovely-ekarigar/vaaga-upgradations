<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadListItem extends Model
{
 

    protected $table = 'lead_list_items';

    protected $fillable = [
        'lead_list_id',
        'lead_id',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function leadList()
    {
        return $this->belongsTo(LeadList::class, 'lead_list_id');
    }
}
