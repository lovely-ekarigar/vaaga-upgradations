<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestSeries extends Model
{
    

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'test_series';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'detail',
        'course_id',
        'status',
        'price',
        'offer_price',
        'total_test',
        'validity',
        'difficulty',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'offer_price' => 'decimal:2',
        'total_test' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => '0',
        'price' => 0,
        'offer_price' => 0,
        'total_test' => 0,
    ];

    /**
     * Get the course that owns the test series.
     */
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * Scope a query to only include active test series.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', '1');
    }

    /**
     * Scope a query to only include featured test series (with offers).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithOffer($query)
    {
        return $query->where('offer_price', '>', 0)
                    ->whereColumn('offer_price', '<', 'price');
    }

    /**
     * Get the discounted price.
     *
     * @return float
     */
    public function getDiscountedPriceAttribute()
    {
        return $this->offer_price > 0 ? $this->offer_price : $this->price;
    }

    /**
     * Get the discount percentage.
     *
     * @return float|null
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->offer_price > 0 && $this->price > 0) {
            return round((($this->price - $this->offer_price) / $this->price) * 100);
        }
        
        return null;
    }

    /**
     * Check if the test series has a discount.
     *
     * @return bool
     */
    public function getHasDiscountAttribute()
    {
        return $this->offer_price > 0 && $this->offer_price < $this->price;
    }

    /**
     * Get the validity in months.
     *
     * @return int|null
     */
    public function getValidityInMonthsAttribute()
    {
        if (preg_match('/(\d+)\s*month/i', $this->validity, $matches)) {
            return (int) $matches[1];
        }
        
        if (preg_match('/(\d+)\s*day/i', $this->validity, $matches)) {
            return ceil((int) $matches[1] / 30);
        }
        
        return null;
    }

    /**
     * Get the display price with currency.
     *
     * @return string
     */
    public function getDisplayPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    /**
     * Get the display offer price with currency.
     *
     * @return string
     */
    public function getDisplayOfferPriceAttribute()
    {
        return '₹' . number_format($this->offer_price, 2);
    }
}