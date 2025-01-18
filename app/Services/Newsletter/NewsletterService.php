<?php

namespace App\Services\Newsletter;

use App\Enums\Newsletter\NewsletterStatus;
use App\Filters\Newsletter\FilterNewsletter;
use App\Mail\NewsletterMail;
use App\Models\Newsletter\Newsletter;
use App\Models\Newsletter\Subscriber;
use Illuminate\Support\Facades\Mail;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NewsletterService{

    private $newsletter;
    public function __construct(Newsletter $newsletter)
    {
        $this->newsletter = $newsletter;
    }

    public function all()
    {
        $newsletters = QueryBuilder::for(Newsletter::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterNewsletter()), // Add a custom search filter
            ])->get();

        return $newsletters;

    }

    public function create(array $data): Newsletter
    {

        $newsletter = Newsletter::create([
            'subject' => $data['subject'],
            'content' => $data['content'],
            'is_sent' => NewsletterStatus::from($data['isSent'])->value,
        ]);

        if($newsletter->is_sent == NewsletterStatus::SENT){
            $this->sendNewsletterEmails($newsletter);
        }

        return $newsletter;

    }

    public function edit(int $id)
    {
        return Newsletter::find($id);
    }

    public function update(array $data): Newsletter
    {

        $newsletter = Newsletter::find($data['newsletterId']);

        $newsletter->subject = $data['subject'];
        $newsletter->content = $data['content'];
        $newsletter->is_sent = NewsletterStatus::from($data['isSent'])->value;

        if($newsletter->is_sent == NewsletterStatus::SENT){
            $this->sendNewsletterEmails($newsletter);
        }

        $newsletter->save();

        return $newsletter;


    }


    public function delete(int $id)
    {

        Newsletter::find($id)->delete();

    }

    public function changeStatus(int $newsletterId, int $isSent)
    {
        $newsletter = Newsletter::find($newsletterId);
        if($newsletter->is_sent == NewsletterStatus::NOT_SENT){
            $newsletter->is_sent = $isSent;
        }
        $newsletter->save();
    }

    protected function sendNewsletterEmails(Newsletter $newsletter): void
    {
        $subscribers = Subscriber::where('is_subscribed', true)->get();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail($newsletter));
        }

    }


}
