<?php

namespace App\Http\Controllers\Api\V1\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\CustomerJob;

class StoreAppointmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreAppointmentRequest $request, CustomerJob $job): AppointmentResource
    {
        $appointment = $job->appointments()->create($request->validated());

        return new AppointmentResource($appointment);
    }
}
