<?php

namespace App\Models\Product;

use App\Enums\Product\ProductStatus;
use App\Models\Product\ProductImage;
use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable  as TranslatableContract;

class Product extends Model implements TranslatableContract
{
    use HasFactory ,Translatable;
    protected $translatedAttributes = ['name', 'description', 'slug', 'content', 'meta_data'];
    protected $fillable =['is_active'];
    protected $casts =[
       'is_active'=>ProductStatus::class
    ];
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

}
