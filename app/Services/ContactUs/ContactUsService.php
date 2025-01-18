<?php

namespace App\Services\ContactUs;

use App\Enums\ContactUs\ContactMessagesStatus;
use App\Enums\ContactUs\ContactUsStatus;
use App\Filters\ContactUs\FilterContactUs;
use App\Models\ContactUs\ContactUs;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ContactUsService{

    public function all()
    {
        $contactUss = QueryBuilder::for(ContactUs::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterContactUs()), // Add a custom search filter
            ])->get();

        return $contactUss;

    }

    public function create(array $data)
    {

        $contactUs = ContactUs::create([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => ContactMessagesStatus::from($data['status'])->value,
        ]);


        return $contactUs;

    }

    public function edit(int $id)
    {
        return ContactUs::with('messages')->find($id);
    }

    public function update(array $data)
    {

        $contactUs = ContactUs::find($data['contactUsId']);
        $contactUs->update([
            'name' => $data['name'],
            'subject' => $data['subject'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'status' => ContactMessagesStatus::from($data['status'])->value,
        ]);
        return $contactUs;


    }


    public function delete(int $id)
    {

        $contactUs  = ContactUs::find($id);

        $contactUs->delete();

    }

    public function changeStatus(int $contactUsId, bool $status)
    {
        $contactUs = ContactUs::find($contactUsId);
        $contactUs->status = $status;
        $contactUs->save();
    }




}
