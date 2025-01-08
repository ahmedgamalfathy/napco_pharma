<?php
namespace App\Services\Product;

use App\Models\Product\Product;
use App\Enums\Product\ProductStatus;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Product\ProductSearchTranslatableFilter;

class ProductService{
    public function all()
    {
        $products = QueryBuilder::for(Product::class)
            ->withTranslation() // Fetch translations if applicable
            ->allowedFilters([
                AllowedFilter::custom('search', new ProductSearchTranslatableFilter()), // Add a custom search filter
            ])
            // ->with('images')
            ->get();

        return $products;

    }
    public function create(array $data)
    {
        $product =new Product();
        $product->is_active =ProductStatus::from($data['isActive'])->value;
        if(!empty($data['nameAr'])){
            $product->translateOrNew('ar')->name =$data['nameAr'];
            $product->translateOrNew('ar')->description =$data['descriptionAr'];
            $product->translateOrNew('ar')->content =$data['contentAr'];
            $product->translateOrNew('ar')->slug =$data['slugAr'];
            $product->translateOrNew('ar')->meta_data =$data['metaDataAr'];
        }
        if(!empty($data['nameEn'])){
            $product->translateOrNew('en')->name = $data['nameEn'];
            $product->translateOrNew('en')->description = $data['descriptionEn'];
            $product->translateOrNew('en')->content = $data['contentEn'];
            $product->translateOrNew('en')->slug = $data['slugEn'];
            $product->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }
          $product->save();
          return $product;
    }
    public function edit(int $id)
    {
      return Product::with('translations')->find($id);
    }
    public function update(array $data)
    {
      $product =Product::find($data['productId']);
      if( $product->is_active){
        $product->is_active =ProductStatus::from($data['isActive']);
       }
      if(!empty($data['nameAr'])){
        $product->translateOrNew('ar')->name = $data['nameAr'];
        $product->translateOrNew('ar')->description = $data['descriptionAr'];
        $product->translateOrNew('ar')->content = $data['contentAr'];
        $product->translateOrNew('ar')->slug = $data['slugAr'];
        $product->translateOrNew('ar')->meta_data = $data['metaDataAr'];
      }
        if(!empty($data['nameEn'])){
            $product->translateOrNew('en')->name = $data['nameEn'];
            $product->translateOrNew('en')->description = $data['descriptionEn'];
            $product->translateOrNew('en')->content = $data['contentEn'];
            $product->translateOrNew('en')->slug = $data['slugEn'];
            $product->translateOrNew('en')->meta_data = $data['metaDataEn'];
        }
        $product->save();
        return $product;
    }
    public function delete(int $id)
    {
      $product = Product::find($id);
      $product->delete();
    }
    public function changeStatus(int $id, bool $isActive)
    {
        $product = Product::find($id);
        $product->is_active = $isActive;
        $product->save();
    }
}
?>
