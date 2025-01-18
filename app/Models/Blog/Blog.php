<?php

namespace App\Models\Blog;

use App\Enums\Blog\BlogStatus;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    protected $translatedAttributes = ['title', 'content', 'slug', 'meta_data'];
    protected $fillable = [
        'thumbnail',
        'is_published',
        'category_id',
    ];
    protected $casts = [
        'is_published'=>BlogStatus::class
    ];
    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if($model->is_published == BlogStatus::PUBLISHED){
                $model->published_at = now();
            }
        });
        static::updating(function ($model) {
            if($model->is_published == BlogStatus::PUBLISHED){
                $model->published_at = now();
            }
        });
    }
    public function blogCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
    //booted
}
