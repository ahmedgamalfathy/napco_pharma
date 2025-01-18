<?php

namespace App\Services\Event;

use App\Enums\Event\EventStatus;
use App\Filters\Event\EventSearchTranslatableFilter;
use App\Models\Event\Event;
use App\Services\Upload\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class EventService{

    private $event;
    private $uploadService;
    public function __construct( UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function all()
    {
        $events = QueryBuilder::for(Event::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new EventSearchTranslatableFilter()), // Add a custom search filter
            ])->get();

        return $events;

    }

    public function create(array $data): Event
    {

        $path = null;
        if(isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($data['thumbnail'], 'events');
        }

        $event = new  Event();

        $event->is_published = EventStatus::from($data['isPublished'])->value;
        $event->thumbnail = $path;
        $event->date = $data['date'];
        $event->time = $data['time'];
        $event->location = $data['location'];


        if (!empty($data['titleAr'])) {
            $event->translateOrNew('ar')->title = $data['titleAr'];
            $event->translateOrNew('ar')->slug = $data['slugAr'];
            $event->translateOrNew('ar')->description = $data['descriptionAr'];
            $event->translateOrNew('ar')->meta_data = $data['metaDataAr'];
        }

        if (!empty($data['titleEn'])) {
            $event->translateOrNew('en')->title = $data['titleEn'];
            $event->translateOrNew('en')->slug = $data['slugEn'];
            $event->translateOrNew('en')->description = $data['descriptionEn'];
            $event->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }


        $event->save();


        return $event;

    }

    public function edit(int $id)
    {
        return Event::with('translations')->find($id);
    }

    public function update(array $data): Event
    {

        $event = Event::find($data['eventId']);

        $path = null;

        if(isset($data['thumbnail']) && $data['thumbnail'] instanceof UploadedFile){
            $path =  $this->uploadService->uploadFile($data['thumbnail'], 'events');
        }

        $event->is_published = EventStatus::from($data['isPublished'])->value;
        $event->thumbnail = $path;
        $event->date = $data['date'];
        $event->time = $data['time'];
        $event->location = $data['location'];

        if($path){
            $event->thumbnail = $path;
        }

        if (!empty($data['titleAr'])) {
            $event->translateOrNew('ar')->title = $data['titleAr'];
            $event->translateOrNew('ar')->slug = $data['slugAr'];
            $event->translateOrNew('ar')->description = $data['descriptionAr'];
            $event->translateOrNew('ar')->meta_data = $data['metaDataAr'];
        }

        if (!empty($data['titleEn'])) {
            $event->translateOrNew('en')->title = $data['titleEn'];
            $event->translateOrNew('en')->slug = $data['slugEn'];
            $event->translateOrNew('en')->description = $data['descriptionEn'];
            $event->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }
        if($path){
            Storage::disk('public')->delete($path);
            $event->thumbnail = $path;
        }


        $event->save();

        return $event;


    }


    public function delete(int $id)
    {

        $event  = Event::find($id);

        if($event->thumbnail){
            Storage::disk('public')->delete($event->thumbnail);
        }

        $event->delete();

    }

    public function changeStatus(int $eventId, bool $isPublished)
    {
        $event = Event::find($eventId);
        $event->is_published = $isPublished;
        $event->save();
    }




}
