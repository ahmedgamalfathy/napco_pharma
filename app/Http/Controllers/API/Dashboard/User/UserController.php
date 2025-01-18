<?php

namespace App\Http\Controllers\API\Dashboard\User;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use App\Services\User\UserService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResouce;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\user\AllUserCollection;
use App\Http\Resources\User\AllUserDataResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService){
       $this->userService =$userService;
       $this->middleware('auth:api');
       $this->middleware('permission:all_users',['only'=>['index']]);
       $this->middleware('permission:create_user',['only'=>['create']]);
       $this->middleware('permission:edit_user',['only'=>'edit']);
       $this->middleware('permission:update_user',['only'=>'update']);
       $this->middleware('permission:delete_user',['only'=>'delete']);
    }
    public function index(Request $request)
    {
        $allUsers = $this->userService->all();
        return response()->json(
            new AllUserCollection(PaginateCollection::paginate($allUsers, $request->pageSize?$request->pageSize:10))
        //   'pagination' => [
        //     'current_page' => $allUser->currentPage(),
        //     'last_page' => $allUser->lastPage(),
        //     'per_page' => $allUser->perPage(),
        //     'total' => $allUser->total(),
        // ],
       ,200);
    }
    public function create(CreateUserRequest $createUserRequest)
    {
    try{
        DB::beginTransaction();
        $user = $this->userService->create($createUserRequest->validated());
        DB::commit();
        return response()->json([
            'message' => __('messages.success.created')
        ], 200);
    }catch(\Exception $e){
        DB::rollBack();
        throw $e;
    }
    }
    public function edit(Request $request)
    {
        try {
            $user=$this->userService->edit($request->userId);
            return response()->json([
             "data"=>new AllUserDataResource($user)
            ],200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                 "message"=>"model not found"
               ],200);
        }
    }
    public function update(UpdateUserRequest $updateUserRequest)
    {
        try {
            DB::beginTransaction();
            $this->userService->update($updateUserRequest->validated());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
           ], 200);
        } catch (\Throwable $e) {
           DB::rollBack();
           throw $e;
        }

    }
    public function delete(Request $request)
    {
      try {
          DB::beginTransaction();
          $this->userService->delete($request->userId);
          DB::commit();
          return response()->json([
            'message' => __('messages.success.deleted')
        ], 200);
      } catch (\Throwable $e) {
        DB::rollBack();
        throw $e;
      }
    }
    public function changeUserStatus(Request $request)
    {
        try {
           DB::beginTransaction();
            $this->userService->changeUserStatus($request->userId,$request->status);
           DB::commit();
           return response()->json([
            'message' => 'تم تغيير حالة المستخدم!'
           ], 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

}
