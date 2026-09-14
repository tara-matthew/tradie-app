<?php

namespace App\Http\Controllers\Api\V1\Appointments;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\AppointmentResource;
use App\Models\Appointment;

class ShowAppointmentController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Appointment $appointment): AppointmentResource
    {
        return new AppointmentResource($appointment->loadMissing('customerJob.customer'));
    }
}
