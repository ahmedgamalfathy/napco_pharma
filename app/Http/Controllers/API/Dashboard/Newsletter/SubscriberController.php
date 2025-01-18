<?php

namespace App\Http\Controllers\API\Dashboard\Newsletter;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Services\Newsletter\SubscriberService;
use App\Http\Resources\Newsletter\Subscriber\SubscriberResource;
use App\Http\Requests\Newsletter\Subscriber\CreateSubscriberRequest;
use App\Http\Requests\Newsletter\Subscriber\UpdateSubscriberRequest;
use App\Http\Resources\Newsletter\Subscriber\AllSubscriberCollection;


class SubscriberController extends Controller
{
    protected $subcsciberService;

    public function __construct(SubscriberService $subcsciberService)
    {
        $this->middleware('auth:adpi');
        $this->subcsciberService = $subcsciberService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $newsletters = $this->subcsciberService->all();

        return response()->json(
            new AllSubscriberCollection(PaginateCollection::paginate($newsletters, $request->pageSize?$request->pageSize:10))
        , 200);

    }

    /**
     * Show the form for creating a new resource.
     */

    public function create(CreateSubscriberRequest $createSubscriberRequest)
    {

        try {
            DB::beginTransaction();

            $this->subcsciberService->create($createSubscriberRequest->validated());

            DB::commit();

            return response()->json([
                'message' => 'تم اضافة بلد جديد بنجاح'
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
        $subscriber  =  $this->subcsciberService->edit($request->subscriberId);

        return response()->json(
            new SubscriberResource($subscriber)//new UserResource($user)
        ,200);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriberRequest $updateSubscriberRequest)
    {

        try {
            DB::beginTransaction();
            $this->subcsciberService->update($updateSubscriberRequest->validated());
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
            $this->subcsciberService->delete($request->subscriberId);
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
        $this->subcsciberService->changeStatus($request->subscriberId, $request->isSubscribed);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }


}
