<?php

namespace App\Http\Controllers\API\Dashboard\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Upload\UploadService;
use App\Services\Product\ProductImageService;
use App\Http\Resources\Product\ProductImage\ProductImageResource;

class ProductImageController extends Controller
{
    protected $productImageService;
    protected $uploadService;
    public function __construct(UploadService $uploadService, ProductImageService $productImageService)
    {
        $this->middleware('auth:api');
        $this->productImageService = $productImageService;
        $this->uploadService = $uploadService;
    }
    public function index(Request $request)
    {
        $productImages = $this->productImageService->all($request->all());

        return response()->json([
            "data"=> ProductImageResource::collection($productImages)
        ],200);

    }
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();

            $data = $request->images;

            foreach ($data as $key => $image) {
                $path = $this->uploadService->uploadFile($image['path'], "products/$request->productId");

                $this->productImageService->create([
                    'productId' => $request->productId,
                    'path' => $path
                ]);
            }
            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->productImageService->delete($request->productImageId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

}
