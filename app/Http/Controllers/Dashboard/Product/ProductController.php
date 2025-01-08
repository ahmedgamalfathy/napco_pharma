<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Http\Resources\Product\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadService;
use App\Services\Product\ProductService;
use App\Services\Product\ProductImageService;
use App\Http\Resources\Product\AllProductResouce;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;

class ProductController extends Controller
{
  protected $productService;
  protected $uploadService;
  protected $productImageService;
    public function __construct(ProductService $productService, UploadService $uploadService,ProductImageService $productImageService)
    {
        $this->productService =$productService;
        $this->uploadService = $uploadService;
        $this->productImageService = $productImageService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->all();

        return response()->json([
          "data"=>$products,
          'pagination' => [
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
            'total' => $products->total(),
               ],
        ], 200);

    }
    public function create(CreateProductRequest $createProductRequest)
    {
        try {
            DB::beginTransaction();
            $data=$createProductRequest->validated();
            $product =  $this->productService->create($data);
            if (isset($data['images'])) {
                foreach ($data['images'] as $key => $image) {
                    $path = $this->uploadService->uploadFile($image['path'], "products/$product->id");
                    $this->productImageService->create([
                        'productId' => $product->id,
                        'path' => $path
                    ]);
                }
            }

            DB::commit();
            return response()->json([
                'message' => __('messages.success.created')
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function edit(Request $request)
    {
        $product =$this->productService->edit($request->productId);
        return response()->json([
         "data"=>$product
        ],200);
    }
    public function update(UpdateProductRequest $updateProductRequest)
    {
        try {
            DB::beginTransaction();
            $this->productService->update($updateProductRequest->validated());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
           ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function delete(Request $request)
    {
       try {
           DB::beginTransaction();
           $this->productService->delete($request->productId);
           DB::commit();
           return response()->json([
            'message' => __('messages.success.deleted')
        ], 200);

        } catch (\Throwable $th) {
           DB::rollBack();
           throw $th;
       }
    }
    public function changeStatus(Request $request)
    {
        $this->productService->changeStatus($request->productId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }
}
