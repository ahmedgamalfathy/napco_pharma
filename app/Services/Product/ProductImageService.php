<?php
namespace App\Services\Product;

use App\Models\Product\ProductImage;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Storage;

class ProductImageService {
    private $productImage;
    public function __construct(ProductImage $productImage)
    {
        $this->productImage = $productImage;
    }
    public function all(array $filters)
    {
        $productImages = QueryBuilder::for(ProductImage::class)
            ->allowedFilters([
                //AllowedFilter::exact('title'), // Add a custom search filter
            ])
            ->where('product_id', $filters['productId'])
            ->get();

        return $productImages;

    }
    public function create($data)
    {
        $productImage = new ProductImage();
        $productImage->path = $data['path'];
        $productImage->product_id = $data['productId'];
        $productImage->save();

        return $productImage;
    }
    public function delete(int $id)
    {
        $productImage  = ProductImage::find($id);
        if($productImage->path){
            Storage::disk('public')->delete($productImage->path);
        }
        $productImage->delete();

    }
}
?>
