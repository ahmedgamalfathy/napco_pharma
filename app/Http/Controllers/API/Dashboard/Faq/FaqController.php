<?php

namespace App\Http\Controllers\API\Dashboard\Faq;

use Illuminate\Http\Request;
use App\Services\Faq\FaqService;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Faq\FaqResource;
use App\Http\Resources\Faq\AllFaqResource;
use App\Http\Requests\Faq\CreateFaqRequest;
use App\Http\Requests\Faq\UpdateFaqRequest;
use App\Http\Resources\Faq\AllFaqCollection;

class FaqController extends Controller
{
    protected $faqService;
    public function __construct(FaqService $faqService)
    {
        $this->faqService =$faqService;
        $this->middleware('auth:api');
    }
    public function index(Request $request)
    {
        $faqs = $this->faqService->all();

        return response()->json(
        new AllFaqCollection(PaginateCollection::paginate($faqs, $request->pageSize?$request->pageSize:10))
        , 200);

    }
    public function create(CreateFaqRequest $createFaqRequest)
    {
        try {
            DB::beginTransaction();
            $this->faqService->create($createFaqRequest->validated());
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
        $faq  =  $this->faqService->edit($request->faqId);
        return response()->json([
            "data"=>new FaqResource($faq)
        ],200);
    }
    public function update(UpdateFaqRequest $updateFaqRequest)
    {
        try {
            DB::beginTransaction();
            $this->faqService->update($updateFaqRequest->validated());
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
            $this->faqService->delete($request->faqId);
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
        $this->faqService->changeStatus($request->faqId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }
}
