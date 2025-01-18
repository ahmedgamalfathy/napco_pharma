<?php

namespace App\Http\Controllers\API\Dashboard\Career;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Career\CareerService;
use App\Http\Resources\Career\CareerResource;
use App\Http\Requests\Career\CreateCareerRequest;
use App\Http\Requests\Career\UpdateCareerRequest;
use App\Http\Resources\Career\AllCareerCollection;



class CareerController extends Controller
{
    protected $careerService;

    public function __construct(CareerService $careerService)
    {
        // $this->middleware('auth:api');
        $this->careerService = $careerService;
    }
    public function index(Request $request)
    {
        $careers = $this->careerService->all();

        return response()->json(
            new AllCareerCollection(PaginateCollection::paginate($careers, $request->pageSize?$request->pageSize:10))
        , 200);

    }
    public function create(CreateCareerRequest $createCareerRequest)
    {

        try {
            DB::beginTransaction();

            $this->careerService->create($createCareerRequest->validated());

            DB::commit();

            return response()->json([
                'message' => __('messages.success.created')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }


    }
    public function edit(Request $request)
    {
        $career  =  $this->careerService->edit($request->careerId);

        return response()->json(
            new CareerResource($career)//new UserResource($user)
        ,200);

    }
    public function update(UpdateCareerRequest $updateCareerRequest)
    {

        try {
            DB::beginTransaction();
            $this->careerService->update($updateCareerRequest->validated());
            DB::commit();
            return response()->json([
                 'message' => __('messages.success.updated')
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
            $this->careerService->delete($request->careerId);
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
        $this->careerService->changeStatus($request->careerId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }

}
