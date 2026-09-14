<?php

namespace App\Http\Controllers\Api\V1\Customers;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CustomerResource;
use App\Models\Customer;

class ShowCustomerController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Customer $customer): CustomerResource
    {
        return new CustomerResource($customer->loadMissing(['jobs', 'appointments']));
    }
}
