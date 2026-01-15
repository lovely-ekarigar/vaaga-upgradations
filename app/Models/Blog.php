<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Blog extends Model
{
    protected $appends = ['blog_category','blog_image','blog_author'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'deleted_at' => 'datetime',
    ];


    public function getBlogAuthorAttribute()
    {
        return $this->author->full_name;
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($blog) { // before delete() method call this
            if (File::exists(public_path('/storage/uploads/' . $blog->image))) {
                File::delete(public_path('/storage/uploads/' . $blog->image));
            }
        });
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $table = 'blogs';

    protected $guarded = ['id'];

    public function comments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function user()
    {
        return $this->hasOne(User::class,'id','user_id');
    }


    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }


    public function getBlogCategoryAttribute()
    {
        return $this->category->name;
    }

    public function getBlogImageAttribute()
    {
        return url('storage/uploads/'.$this->image);
    }

}
