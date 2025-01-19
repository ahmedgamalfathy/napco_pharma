<?php

namespace App\Services\FrontPage;

use App\Enums\FrontPage\FrontPageStatus;
use App\Filters\FrontPage\FrontPageSearchTranslatableFilter;
use App\Models\FrontPage\FrontPage;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FrontPageService{

    private $frontPage;
    public function __construct(FrontPage $frontPage)
    {
        $this->frontPage = $frontPage;
    }

    public function all()
    {
        $frontPages = QueryBuilder::for(FrontPage::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
                AllowedFilter::custom('search', new FrontPageSearchTranslatableFilter() ), // Add a custom search filter
            ])->get();

        return $frontPages;

    }

    public function create(array $data): FrontPage
    {

        $frontPage = new FrontPage();

        $frontPage->is_active = FrontPageStatus::from($data['isActive'])->value;
        $frontPage->controller_name = $data['controllerName'] ?? 'defaultControll';

        if(!empty($data['titleAr'])){
            $frontPage->translateOrNew('ar')->title = $data['titleAr'];
            $frontPage->translateOrNew('ar')->slug = $data['slugAr'];
            $frontPage->translateOrNew('ar')->meta_data = $data['metaDataAr'];
        }

        if(!empty($data['titleEn'])){
            $frontPage->translateOrNew('en')->title = $data['titleEn'];
            $frontPage->translateOrNew('en')->slug = $data['slugEn'];
            $frontPage->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }

        $frontPage->save();

        return $frontPage;

    }

    public function edit(int $id)
    {
        return FrontPage::with('translations')->find($id);
    }

    public function update(array $data): FrontPage
    {

        $frontPage = FrontPage::find($data['frontPageId']);

        $frontPage->is_active = FrontPageStatus::from($data['isActive'])->value;
        $frontPage->controller_name =$data['controllerName'];
        if(!empty($data['titleAr'])){
            $frontPage->translateOrNew('ar')->title = $data['titleAr'];
            $frontPage->translateOrNew('ar')->slug = $data['slugAr'];
            $frontPage->translateOrNew('ar')->meta_data = $data['metaDataAr'];
        }

        if(!empty($data['titleEn'])){
            $frontPage->translateOrNew('en')->title = $data['titleEn'];
            $frontPage->translateOrNew('en')->slug = $data['slugEn'];
            $frontPage->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }

        $frontPage->save();

        return $frontPage;

    }


    public function delete(int $id)
    {
        $frontPage  = FrontPage::find($id);
        $frontPage->delete();
    }

    public function changeStatus(int $frontPageId, bool $isActive)
    {
        $frontPage = FrontPage::find($frontPageId);
        $frontPage->is_active = $isActive;
        $frontPage->save();
    }




}
