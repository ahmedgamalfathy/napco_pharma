<?php

namespace App\Http\Controllers\API\Dashboard\Customer;

use Illuminate\Http\Request;
use App\Utils\PaginateCollection;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerService;
use App\Http\Resources\user\AllUserCollection;
use App\Http\Resources\Customer\CustomerResource;
use App\Http\Resources\Customer\AllCustomerResource;
use App\Http\Requests\Customer\CreateCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\Customer\AllCustomerCollection;

class CustomerController extends Controller
{
    protected $customerService;
    public function __construct(CustomerService $customerService)
    {
        $this->middleware('auth:api');
       $this->customerService =$customerService;
    }
    public function index(Request $request)
    {
        $allCustomers = $this->customerService->all();
        return response()->json(
            new AllCustomerCollection(PaginateCollection::paginate($allCustomers, $request->pageSize?$request->pageSize:10))
        , 200);

    }
    public function create(CreateCustomerRequest $createCustomerRequest)
    {
        try {
            DB::beginTransaction();
            $customer =$this->customerService->create($createCustomerRequest->validated());
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
        $customer  =  $this->customerService->edit($request->customerId);
        return response()->json([
            "data"=>new CustomerResource($customer)// new CustomerResource($customer)//new UserResource($user)
        ],200);
    }
    public function update(UpdateCustomerRequest $updateCustomerRequest)
    {
        try {
            DB::beginTransaction();
            $this->customerService->update($updateCustomerRequest->validated());
            DB::commit();
            return response()->json([
                'message' => __('messages.success.updated')
           ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function delete(Request $request)
    {
      try {
          DB::beginTransaction();
            $this->customerService->delete($request->customerId);
          DB::commit();
          return response()->json([
            'message' => __('messages.success.deleted')
        ], 200);
      } catch (\Throwable $th) {
        DB::rollBack();
          throw $th;
      }
    }
}
