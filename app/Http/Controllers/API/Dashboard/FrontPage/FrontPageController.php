<?php

namespace App\Http\Controllers\API\Dashboard\FrontPage;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\FrontPage\FrontPageService;
use App\Http\Resources\FrontPage\FrontPageResource;
use App\Http\Requests\FrontPage\CreateFrontPageRequest;
use App\Http\Requests\FrontPage\UpdateFrontPageRequest;
use App\Http\Resources\FrontPage\AllFrontPageCollection;


class FrontPagecontroller extends Controller
{
    protected $frontPageService;

    public function __construct(FrontPageService $frontPageService)
    {
        // $this->middleware('auth:api');
        $this->frontPageService = $frontPageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $frontPages = $this->frontPageService->all();

        return response()->json(
            new AllFrontPageCollection(PaginateCollection::paginate($frontPages, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateFrontPageRequest $createFrontPageRequest)
    {

        try {
            DB::beginTransaction();

            $this->frontPageService->create($createFrontPageRequest->validated());

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
        $frontPage  =  $this->frontPageService->edit($request->frontPageId);

        return response()->json(
            new FrontPageResource($frontPage)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFrontPageRequest $updateFrontPageRequest)
    {

        try {
            DB::beginTransaction();
            $this->frontPageService->update($updateFrontPageRequest->validated());
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
            $this->frontPageService->delete($request->frontPageId);
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
        $this->frontPageService->changeStatus($request->frontPageId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }


}
