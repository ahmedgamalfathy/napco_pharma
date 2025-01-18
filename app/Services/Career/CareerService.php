<?php
namespace App\Services\Career;

use App\Models\Career\Career;
use App\Enums\Career\CareerStatus;
use Spatie\QueryBuilder\QueryBuilder;
use App\Services\Upload\UploadService;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Career\CareerSearchTranslatableFilter;

class CareerService{
    private $uploadService;
    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }
    public function all()
    {
        $careers = QueryBuilder::for(Career::class)
        ->withTranslation() // Fetch translations if applicable
        ->allowedFilters([
            AllowedFilter::custom('search', new CareerSearchTranslatableFilter()), // Add a custom search filter
        ])
        ->get();
        return $careers;
    }
    public function create(array $data)
    {
        $career = new Career();

        $career->is_active = CareerStatus::from($data['isActive'])->value;


        if (!empty($data['titleAr'])) {
            $career->translateOrNew('ar')->title = $data['titleAr'];
            $career->translateOrNew('ar')->description = $data['descriptionAr'];
            $career->translateOrNew('ar')->content = $data['contentAr'];
            $career->translateOrNew('ar')->meta_data = $data['metaDataAr'];
            $career->translateOrNew('ar')->extra_details = $data['extraDetailsAr'];
            $career->translateOrNew('ar')->slug = $data['slugAr'];


        }

        if (!empty($data['titleEn'])) {
            $career->translateOrNew('en')->title = $data['titleEn'];
            $career->translateOrNew('en')->description = $data['descriptionEn'];
            $career->translateOrNew('en')->content = $data['contentEn'];
            $career->translateOrNew('en')->meta_data = $data['metaDataEn'];
            $career->translateOrNew('en')->extra_details = $data['extraDetailsEn'];
            $career->translateOrNew('en')->slug = $data['slugEn'];

        }


        $career->save();

        return $career;
    }
    public function edit(int $id)
    {
        return Career::with('translations')->find($id);
    }
    public function update(array $data)
    {
        $career = Career::find($data['careerId']);

        $career->is_active = CareerStatus::from($data['isActive'])->value;


        if (!empty($data['titleAr'])) {
            $career->translateOrNew('ar')->title = $data['titleAr'];
            $career->translateOrNew('ar')->description = $data['descriptionAr'];
            $career->translateOrNew('ar')->content = $data['contentAr'];
            $career->translateOrNew('ar')->meta_data = $data['metaDataAr'];
            $career->translateOrNew('ar')->extra_details = $data['extraDetailsAr'];
            $career->translateOrNew('ar')->slug = $data['slugAr'];

        }

        if (!empty($data['titleEn'])) {
            $career->translateOrNew('en')->title = $data['titleEn'];
            $career->translateOrNew('en')->description = $data['descriptionEn'];
            $career->translateOrNew('en')->content = $data['contentEn'];
            $career->translateOrNew('en')->meta_data = $data['metaDataEn'];
            $career->translateOrNew('en')->extra_details = $data['extraDetailsEn'];
            $career->translateOrNew('en')->slug = $data['slugEn'];

        }


        $career->save();

        return $career;
    }
    public function delete(int $id)
    {
        $career  = Career::find($id);
        $career->delete();
    }
    public function changeStatus(int $careerId, bool $isActive)
    {
        $career = Career::find($careerId);

        $career->is_active = $isActive;
        $career->save();
    }
}
?>
