<?php

namespace App\Models\ContactUs;

use App\Traits\CreatedUpdatedBy;
use App\Enums\ContactUs\SenderType;
use Illuminate\Database\Eloquent\Model;
use App\Models\ContactUs\ContactUsMessage;
use App\Enums\ContactUs\ContactMessagesStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactUs extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'email',
        'phone',
        'status',
    ];

    protected $casts = [
        'status' => ContactMessagesStatus::class,
    ];

    public function messages()
    {
        return $this->hasMany(ContactUsMessage::class);
    }

    public function getNewMessagesCountAttribute()
    {
        return $this->messages()->where('is_read', null)->where('is_admin', SenderType::class::CUSTOMER)->count();
    }

}
