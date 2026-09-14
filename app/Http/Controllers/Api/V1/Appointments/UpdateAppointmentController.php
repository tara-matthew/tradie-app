<?php

namespace App\Http\Controllers\Api\V1\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;

class UpdateAppointmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateAppointmentRequest $request, Appointment $appointment): AppointmentResource
    {
        $appointment->update($request->validated());

        return new AppointmentResource($appointment);
    }
}
