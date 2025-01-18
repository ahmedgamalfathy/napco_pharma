<?php

namespace App\Http\Controllers\API\Dashboard\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCategory\CreateProductCategoryRequest;
use App\Http\Requests\Product\ProductCategory\UpdateProductCategoryRequest;
use App\Http\Resources\Product\ProductCategory\AllProductCategoryCollection;
use App\Http\Resources\Product\ProductCategory\ProductCategoryResource;
use App\Utils\PaginateCollection;
use App\Services\Product\ProductCategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProductCategoryController extends Controller
{
    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->middleware('auth:api');
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productCategorys = $this->productCategoryService->all();

        return response()->json(
            new AllProductCategoryCollection(PaginateCollection::paginate($productCategorys, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateProductCategoryRequest $createProductCategoryRequest)
    {

        try {
            DB::beginTransaction();

            $this->productCategoryService->create($createProductCategoryRequest->validated());

            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Request $request)
    {
        $productCategory  =  $this->productCategoryService->edit($request->productCategoryId);

        return response()->json(
            new ProductCategoryResource($productCategory)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $updateProductCategoryRequest)
    {

        try {
            DB::beginTransaction();
            $this->productCategoryService->update($updateProductCategoryRequest->validated());
            DB::commit();
            return response()->json([
                 'message' => __('messages.success.updated')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            DB::beginTransaction();
            $this->productCategoryService->delete($request->productCategoryId);
            DB::commit();
            return response()->json([
                'message' => __('messages.success.deleted')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    public function changeStatus(Request $request)
    {
        $this->productCategoryService->changeStatus($request->productCategoryId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }


}
