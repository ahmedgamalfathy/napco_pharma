<?php

namespace App\Models\Newsletter;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Newsletter\NewsletterStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Newsletter extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'content',
        'is_sent',
    ];

    protected $casts = [
        'is_sent' => NewsletterStatus::class,
    ];
}
