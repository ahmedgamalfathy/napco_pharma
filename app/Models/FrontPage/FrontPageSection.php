<?php

namespace App\Models\FrontPage;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use App\Models\FrontPage\FrontPageSectionImage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class FrontPageSection extends Model implements TranslatableContract
{
    use HasFactory, Translatable;
    public $translatedAttributes = ['content'];

    protected $fillable = [
        'name',
        'is_active',
    ];

    public function images()
    {
        return $this->hasMany(FrontPageSectionImage::class);
    }

    // public function translations(): HasMany
    // {
    //     return $this->hasMany(FrontPageSectionTranslation::class);
    // }

    public function frontPage()
    {
        return $this->belongsToMany(FrontPage::class, 'page_sections', 'front_page_section_id', 'front_page_id');
    }

}
