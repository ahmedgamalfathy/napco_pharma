<?php

namespace App\Http\Controllers\API\Dashboard\Newsletter;

use App\Http\Controllers\Controller;
use App\Http\Requests\Newsletter\CreateNewsletterRequest;
use App\Http\Requests\Newsletter\UpdateNewsletterRequest;
use App\Http\Resources\Newsletter\AllNewsletterCollection;
use App\Http\Resources\Newsletter\NewsletterResource;
use App\Utils\PaginateCollection;
use App\Services\Newsletter\NewsletterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class NewsletterController extends Controller
{
    protected $newsletterService;

    public function __construct(NewsletterService $newsletterService)
    {
        $this->middleware('auth:api');
        $this->newsletterService = $newsletterService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $newsletters = $this->newsletterService->all();

        return response()->json(
            new AllNewsletterCollection(PaginateCollection::paginate($newsletters, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateNewsletterRequest $createNewsletterRequest)
    {

        try {
            DB::beginTransaction();

            $this->newsletterService->create($createNewsletterRequest->validated());

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
        $newsletter  =  $this->newsletterService->edit($request->newsletterId);

        return response()->json(
            new NewsletterResource($newsletter)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNewsletterRequest $updateNewsletterRequest)
    {

        try {
            DB::beginTransaction();
            $this->newsletterService->update($updateNewsletterRequest->validated());
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
            $this->newsletterService->delete($request->newsletterId);
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
        $this->newsletterService->changeStatus($request->newsletterId, $request->isSent);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }


}
