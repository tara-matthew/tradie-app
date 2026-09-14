<?php

namespace App\Http\Resources\V1;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Appointment
 */
class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_job_id' => $this->customer_job_id,
            'scheduled_at' => $this->scheduled_at,
            'customer_job' => new CustomerJobResource($this->whenLoaded('customerJob')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
