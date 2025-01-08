<?php

namespace App\Models\Faq;

use Illuminate\Database\Eloquent\Model;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faq extends Model
{
    use HasFactory,Translatable;
    public $translatedAttributes  =['question', 'answer'];
    protected $fillable =[
        'is_published',
        'order',
    ];
}
