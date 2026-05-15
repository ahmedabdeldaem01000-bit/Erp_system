<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
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

            'name' => $this->name,

            'salary' => $this->salary,

            'address' => $this->address,

            'image' => asset('storage/employees/' . $this->image),

            'phone' => $this->phone,

            'gender' => $this->gender,

            'hire_date' => $this->hire_date,

            'email' => $this->email,

            'department_id' => $this->department_id,

            'roles' => $this->getRoleNames(),
        ];
    }
}
