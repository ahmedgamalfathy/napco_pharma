<?php

namespace App\Services\Product;

use App\Enums\Product\ProductCategoryStatus;
use App\Filters\Product\ProductCategorySearchTranslatableFilter;
use App\Models\Product\ProductCategory;
use App\Services\Upload\UploadService;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProductCategoryService{

    private $productCategory;
    private $uploadService;
    public function __construct(ProductCategory $productCategory, UploadService $uploadService)
    {
        $this->productCategory = $productCategory;
        $this->uploadService = $uploadService;
    }

    public function all()
    {
        $productCategorys = QueryBuilder::for(ProductCategory::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
                AllowedFilter::custom('search', new ProductCategorySearchTranslatableFilter() ), // Add a custom search filter
            ])->get();

        return $productCategorys;

    }

    public function create(array $data): ProductCategory
    {

        $productCategory = new ProductCategory();

        $productCategory->is_active = ProductCategoryStatus::from($data['isActive'])->value;

        $path = null;

        if (isset($data['image'])) {
            $path = $this->uploadService->uploadFile($data['image'], 'productCategories');
        }

        if(!empty($data['nameAr'])){
            $productCategory->translateOrNew('ar')->name = $data['nameAr'];
        }

        if(!empty($data['nameEn'])){
            $productCategory->translateOrNew('en')->name = $data['nameEn'];
        }

        $productCategory->image = $path;

        $productCategory->save();

        return $productCategory;

    }

    public function edit(int $id)
    {
        return ProductCategory::with('translations')->find($id);
    }

    public function update(array $data): ProductCategory
    {


        $productCategory = ProductCategory::find($data['productCategoryId']);

        $productCategory->is_active = ProductCategoryStatus::from($data['isActive'])->value;

        $path = null;


        if (isset($data['image'])) {
            $path = $this->uploadService->uploadFile($data['image'], 'productCategories');
        }

        if(!empty($data['nameAr'])){
            $productCategory->translateOrNew('ar')->name = $data['nameAr'];
        }

        if(!empty($data['nameEn'])){
            $productCategory->translateOrNew('en')->name = $data['nameEn'];
        }

        if($path){
            Storage::disk('public')->delete($productCategory->image);
            $productCategory->image = $path;
        }

        $productCategory->save();

        return $productCategory;


    }


    public function delete(int $id)
    {

        $productCategory  = ProductCategory::find($id);

        $productCategory->delete();

    }

    public function changeStatus(int $productCategoryId, bool $isActive)
    {
        $productCategory = ProductCategory::find($productCategoryId);
        $productCategory->is_active = $isActive;
        $productCategory->save();
    }

}
