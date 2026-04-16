<?php

namespace App\Models;

use App\Models\Auth\User;
use App\Models\Category;
use App\Models\Batch;
use App\Models\Board;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * Class Course
 *
 * @package App
 * @property string $title
 * @property string $slug
 * @property text $description
 * @property decimal $price
 * @property string $course_image
 * @property string $start_date
 * @property tinyInteger $published
 */
class Course extends Model
{
    use SoftDeletes;

      protected $fillable = ['category_id', 'title', 'slug', 'pre_requisite', 'description', 'price', 'price_1', 'monthly_price', 'monthly_price_1', 'regular_monthly', 'regular_monthly_1', 'duration', 'duration_text', 'course_image', 'course_video', 'start_date', 'published', 'free', 'featured', 'trending', 'popular', 'meta_title', 'meta_description', 'meta_keywords'];

    protected $appends = ['image'];


    protected static function boot()
    {
        parent::boot();
        // if (auth()->check()) {
        //     if (auth()->user()->hasRole('teacher')) {
        //         static::addGlobalScope('filter', function (Builder $builder) {
        //             $builder->whereHas('teachers', function ($q) {
        //                 $q->where('course_user.user_id', '=', auth()->user()->id);
        //             });
        //         });
        //     }
        // }

        static::deleting(function ($course) { // before delete() method call this
            if ($course->isForceDeleting()) {
                if (File::exists(public_path('/storage/uploads/' . $course->course_image))) {
                    File::delete(public_path('/storage/uploads/' . $course->course_image));
                    File::delete(public_path('/storage/uploads/thumb/' . $course->course_image));
                }
            }
        });


    }

    public function isCourseExpired($cid,$uid){

        $expired=false;

        $orders = Order::where("user_id",$uid)->where("status",'1')->where("course_mode","like","%monthly%")->get();
        $order=null;
        foreach($orders as $o){

            $oitems = OrderItem::where("order_id",$o->id)->where("item_type","App\Models\Course")->where("item_id",$cid)->first();
            if($oitems){
                $order = $o;
                break;
            }
        }
        if($order){

            if(date("Y-m-d")>date("Y-m-d",strtotime($order->end_date))){
                $expired=true;
            }

        }

return $expired;
    }


    public function getCouseNameWithCat($id){
         $course = Course::find($id);
                
                if( $course){
                $cat = Category::find($course->category_id);
                if(!$cat){
                    return $course->title;
                }
                if($cat->board_id==0){
                    $pat = Category::find($cat->parent);
                    if($pat){
                        return
               $pat->name." | ".$course->title;
                    }else{
                      return
               $cat->name." | ".$course->title;  
                    }
          
                }else{
                    $board = Board::find($cat->board_id);
                    if($board){
                        return $board->name." | ". $cat->name." | ".$course->title;
                    }
                    return $cat->name." | ".$course->title;  
                }
                }else{
                    return "";
                }
    }


    public function getImageAttribute()
    {
        if ($this->course_image != null) {
            return url('storage/uploads/'.$this->course_image);
        }
        return NULL;
    }

    public function getPriceAttribute()
    {
        if (($this->attributes['price'] == null)) {
            return round(0.00);
        }
        return $this->attributes['price'];
    }


    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPriceAttribute($input)
    {
        $this->attributes['price'] = $input ? $input : null;
    }

    /**
     * Set attribute to date format
     * @param $input
     */
    public function setStartDateAttribute($input)
    {
        if ($input != null && $input != '') {
            $this->attributes['start_date'] = Carbon::createFromFormat(config('app.date_format'), $input)->format('Y-m-d');
        } else {
            $this->attributes['start_date'] = null;
        }
    }

    /**
     * Get attribute from date format
     * @param $input
     *
     * @return string
     */
    public function getStartDateAttribute($input)
    {
        $zeroDate = str_replace(['Y', 'm', 'd'], ['0000', '00', '00'], config('app.date_format'));

        if ($input != $zeroDate && $input != null) {
            return Carbon::createFromFormat('Y-m-d', $input)->format(config('app.date_format'));
        } else {
            return '';
        }
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'course_user')->withPivot('user_id');
    }

     public function batches()
    {
        return $this->hasMany(Batch::class, 'cid');
    }


    public function students()
    {
        return $this->belongsToMany(User::class, 'course_student')->withTimestamps()->withPivot(['rating']);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('position');
    }

    public function publishedLessons()
    {
        return $this->hasMany(Lesson::class)->where('published', 1);
    }

    public function scopeOfTeacher($query)
    {
        if (Auth::check() && !Auth::user()->isAdmin()) {
            return $query->whereHas('teachers', function ($q) {
                $q->where('user_id', Auth::user()->id);
            });
        }
        return $query;
    }

    public function getRatingAttribute()
    {
        return $this->reviews->avg('rating');
    }

    public function orderItem()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tests()
    {
        return $this->hasMany('App\Models\Test');
    }

    public function courseTimeline()
    {
        return $this->hasMany(CourseTimeline::class);
    }

    public function getIsAddedToCart(){
        if(auth()->check() && (auth()->user()->hasRole('student')) && (\Cart::session(auth()->user()->id)->get( $this->id))){
            return true;
        }
        return false;
    }


    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'reviewable');
    }

    public function progress()
    {
        $main_chapter_timeline = $this->lessons()->pluck('id')->merge($this->tests()->pluck('id'));

        $completed_lessons = auth()->user()->chapters()->where('course_id', $this->id)->pluck('model_id');
        if ($completed_lessons->count() > 0) {
            return intval($completed_lessons->count() / $main_chapter_timeline->count() * 100);
        } else {
            return 0;
        }
    }

    public function isUserCertified()
    {
        $status = false;
        $certified = auth()->user()->certificates()->where('course_id', '=', $this->id)->first();
        if ($certified != null) {
            $status = true;
        }
        return $status;
    }

    public function item()
    {
        return $this->morphMany(OrderItem::class, 'item');
    }

    public function bundles()
    {
        return $this->belongsToMany(Bundle::class, 'bundle_courses');
    }

    /**
     * Mock tests assigned to this course (class).
     */
    public function mockTests()
    {
        return $this->belongsToMany(\App\Models\MockTest::class, 'mock_test_courses', 'course_id', 'mock_test_id')
            ->withTimestamps();
    }

    public function chapterCount()
    {
        $timeline = $this->courseTimeline;
        $chapters = 0;
        foreach ($timeline as $item) {
            if (isset($item->model) && ($item->model->published == 1)) {
                $chapters++;
            }
        }
        return $chapters;
    }

    public function mediaVideo()
    {
        $types = ['youtube', 'vimeo', 'upload', 'embed'];
        return $this->morphOne(Media::class, 'model')
            ->whereIn('type', $types);

    }



}
