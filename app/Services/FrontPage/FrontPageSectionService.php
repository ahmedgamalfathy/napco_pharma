<?php

namespace App\Services\FrontPage;

use App\Enums\FrontPage\FrontPageSectionStatus;
use App\Filters\FrontPageSection\FrontPageSectionSearchTranslatableFilter;
use App\Models\FrontPage\FrontPageSection;
use App\Models\FrontPage\FrontPageSectionImage;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FrontPageSectionService{

    private $frontPageSection;

    private $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function all(array $filters)
    {
        $frontPageSections = QueryBuilder::for(FrontPageSection::class)
        ->with(['frontPage', 'translations', 'images']) // Eager load the relationships
        // ->whereHas('frontPage', function ($query) use ($filters) {
        //     $query->where('front_page_id', $filters['frontPageId']);
        // })
        ->allowedFilters([])
        ->where('is_active', true)
        ->get();

        return $frontPageSections;
    }


    public function create(array $data): FrontPageSection
    {

        $frontPageSection = new FrontPageSection();

        $frontPageSection->name = $data['name'];
        $frontPageSection->is_active = FrontPageSectionStatus::from($data['isActive'])->value;
        if(!empty($data['contentAr'])){
            $frontPageSection->translateOrNew('ar')->content = $data['contentAr'];
        }

        if(!empty($data['contentEn'])){
            $frontPageSection->translateOrNew('en')->content = $data['contentEn'];
        }

        $frontPageSection->save();

        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $this->uploadService->uploadFile($image['path'], 'frontPageSections');

                $frontPageSection = FrontPageSectionImage::create([
                    'front_page_id' => $frontPageSection->id,
                    'path' => $path
                ]);
            }
        }


        return $frontPageSection;

    }

    public function edit(int $id)
    {
        return FrontPageSection::with('translations', 'images')->find($id);
    }

    public function update(array $data)
    {

        $frontPageSection = FrontPageSection::find($data['frontPageSectionId']);

        $frontPageSection->is_active = FrontPageSectionStatus::from($data['isActive'])->value;

        if(!empty($data['contentAr'])){
            $frontPageSection->translateOrNew('ar')->content = $data['contentAr'];
        }

        if(!empty($data['contentEn'])){
            $frontPageSection->translateOrNew('en')->content = $data['contentEn'];
        }

        $frontPageSection->save();

        if (!empty($data['images'])) {
            foreach ($data['images'] as $image) {
                $path = $this->uploadService->uploadFile($image['path'], 'frontPageSections');

                $frontPageSectionImage = FrontPageSectionImage::create([
                    'front_page_section_id' => $frontPageSection->id,
                    'path' => $path
                ]);
            }
        }


        return $frontPageSection;

    }


    public function delete(int $id)
    {

        $frontPageSection  = FrontPageSection::find($id);

        $frontPageSection->delete();

    }

    public function changeStatus(int $frontPageSectionId, bool $isActive)
    {
        $frontPageSection = FrontPageSection::find($frontPageSectionId);
        $frontPageSection->is_active = $isActive;
        $frontPageSection->save();
    }




}
