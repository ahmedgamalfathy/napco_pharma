<?php

namespace App\Http\Controllers\API\Dashboard\Blog;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Services\Blog\BlogService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Blog\BlogResource;
use App\Http\Requests\Blog\CreateBlogRequest;
use App\Http\Requests\Blog\UpdateBlogRequest;
use App\Http\Resources\Blog\AllBlogCollection;

class BlogController extends Controller
{
    protected $blogService;
    public function __construct(BlogService $blogService)
    {
      $this->middleware('auth:api');
      $this->blogService =$blogService;
    }
    public function index(Request $request)
    {
        $blogs= $this->blogService->all();
        return response()->json(
            new AllBlogCollection(PaginateCollection::paginate($blogs, $request->pageSize?$request->pageSize:10))
        , 200);
    }
    public function create(CreateBlogRequest $createBlogRequest)
    {
        // dd(Request()->all());
        try {
            DB::beginTransaction();

            $this->blogService->create($createBlogRequest->validated());

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
        $blog  =  $this->blogService->edit($request->blogId);

        return response()->json(
            new BlogResource($blog)//new UserResource($user)
        ,200);

    }
    public function update(UpdateBlogRequest $updateBlogRequest)
    {

        try {
            DB::beginTransaction();
            $this->blogService->update($updateBlogRequest->validated());
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
            $this->blogService->delete($request->blogId);
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
        $this->blogService->changeStatus($request->blogId, $request->isPublished);
        return response()->json([
            'message' => __('messages.success.updated')
        ], 200);
    }
}
