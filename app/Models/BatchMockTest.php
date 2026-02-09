<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchMockTest extends Model
{
    protected $table = 'batch_mock_tests';

    protected $fillable = [
        'batch_id',
        'mock_series_id',
        'mock_list_id',
        'sort_order',
        'is_active',
        'scheduled_at'
    ];

    /**
     * Get the batch that owns the mock test.
     */
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    /**
     * Get the mock series that owns the mock test.
     */
    public function mockSeries()
    {
        return $this->belongsTo(MockSeries::class);
    }
    
    /**
     * Get the mock test from mock_list.
     */
    public function mockList()
    {
        return $this->belongsTo(MockList::class, 'mock_list_id');
    }
}
