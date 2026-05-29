<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\LeaveRequest
 */
class LeaveRequestResource extends JsonResource
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
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'leave_type' => new LeaveTypeResource($this->whenLoaded('leaveType')),
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'days' => $this->days,
            'reason' => $this->reason,
            'status' => $this->status,
            'approved_by' => $this->whenLoaded('approvedBy', function () {
                return new EmployeeResource($this->approvedBy);
            }),
            'approved_at' => $this->approved_at?->toIso8601String(),
            'rejection_reason' => $this->rejection_reason,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
