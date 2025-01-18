<?php

namespace App\Services\ContactUs;

use App\Enums\ContactUs\SenderType;
use App\Mail\ContactUsMessageNotification;
use App\Models\ContactUs\ContactUs;
use App\Models\ContactUs\ContactUsMessage;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ContactMessageService{


    public function all(){
        $allContactUsMessages = QueryBuilder::for(ContactUsMessage::class)
        ->allowedFilters([
            AllowedFilter::exact('contactUsId'), // Add a custom search filter
        ])->get();

        return $allContactUsMessages;
    }

    public function create(array $data){
        $contactUsMessage = ContactUsMessage::create([
            'contact_us_Id' => $data['contactUsId'],
            'message' => $data['message'],
            'is_admin' => SenderType::from($data['isAdmin'])->value,
            'is_read' => $data['isRead']??null
        ]);

        $contactUs = ContactUs::find($data['contactUsId']);
        //Mail::to($contactUs->email)->send(new ContactUsMessageNotification($contactUsMessage, $contactUs));

        return $contactUsMessage;
    }

    public function edit(int $id){
        $contactUsMessage = ContactUsMessage::find($id);
        return $contactUsMessage;
    }

    //public function updateContactUsMessage(array $contactUsData): ContactUs;

    //public function deleteContactUsMessage(int $contactUsId);

    //public function changeStatus(int $contactUsId, bool $status);

    /*protected function sendContactUsEmail(ContactUsMessage $contactUsMessage)
    {
        // Implement email logic, e.g., using Laravel's Mail facade
        Mail::to('support@example.com')->send(new ContactUsMessageNotification($contactUsMessage));
    }*/



}
