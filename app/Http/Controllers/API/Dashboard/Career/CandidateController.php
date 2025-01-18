<?php

namespace App\Http\Controllers\API\Dashboard\Career;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Career\CandidateService;
use App\Http\Resources\Career\Candidate\CandidateResource;
use App\Http\Requests\Career\Candidate\CreateCandidateRequest;
use App\Http\Resources\Career\Candidate\AllCandidateCollection;

class CandidateController extends Controller
{
    protected $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->middleware('auth:api');
        $this->candidateService = $candidateService;
    }
    public function index(Request $request)
    {
        $candidates = $this->candidateService->all();

        return response()->json(
            new AllCandidateCollection(PaginateCollection::paginate($candidates, $request->pageSize?$request->pageSize:10))
        , 200);

    }
    public function create(CreateCandidateRequest $createCandidateRequest)
    {

        try {
            DB::beginTransaction();

            $this->candidateService->create($createCandidateRequest->validated());

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
        $candidate  =  $this->candidateService->edit($request->candidateId);

        return response()->json(
            new CandidateResource($candidate)//new UserResource($user)
        ,200);

    }
    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            $this->candidateService->delete($request->candidateId);
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
