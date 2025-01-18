<?php

namespace App\Services\Newsletter;

use App\Enums\Newsletter\NewsletterSubsciberStatus;
use App\Filters\Newsletter\FilterSubscriber;
use App\Models\Newsletter\Subscriber;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SubscriberService{


    public function all()
    {
        $subscribers = QueryBuilder::for(Subscriber::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterSubscriber()), // Add a custom search filter
                AllowedFilter::exact('isSubscribed'), // Add a custom search filter
            ])->get();

        return $subscribers;

    }

    public function create(array $data): Subscriber
    {

        $subscriber = Subscriber::create([
            'email' => $data['email'],
            'is_subscribed' => NewsletterSubsciberStatus::from($data['isSubscribed'])->value,
        ]);

        return $subscriber;

    }

    public function edit(int $id)
    {
        return Subscriber::find($id);
    }

    public function update(array $data): Subscriber
    {

        $subscriber = Subscriber::find($data['subscriberId']);
        $subscriber->update([
            'email' => $data['email'],
            'is_subscribed' => NewsletterSubsciberStatus::from($data['isSubscribed'])->value,
        ]);

        return $subscriber;


    }


    public function delete(int $id)
    {

        Subscriber::find($id)->delete();

    }

    public function changeStatus(int $subscriberId, bool $isSubscribed)
    {
        $subscriber = Subscriber::find($subscriberId);
        $subscriber->is_subscribed = $isSubscribed;
        $subscriber->save();
    }




}
