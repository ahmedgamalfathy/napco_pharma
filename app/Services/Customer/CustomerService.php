<?php
namespace App\Services\Customer;
use App\Models\Customer\Customer;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\Customer\FilterCustomer;



class CustomerService{

    private $customer;
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
    public function all()
    {
        $customers = QueryBuilder::for(Customer::class)
            ->allowedFilters([
                AllowedFilter::custom('search', new FilterCustomer()), // Add a custom search filter
            ])->get();

        return $customers;

    }
    public function create(array $data): Customer
    {

        $customer = Customer::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'description' => $data['description'],
        ]);

        return $customer;

    }
    public function edit(int $id)
    {
        return Customer::find($id);
    }
    public function update(array $data): Customer
    {

        $customer = Customer::find($data['customerId']);

        $customer->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'address' => $data['address'],
            'description' => $data['description'],
        ]);

        return $customer;


    }

    public function delete(int $id)
    {

        return Customer::find($id)->delete();

    }
}
?>
