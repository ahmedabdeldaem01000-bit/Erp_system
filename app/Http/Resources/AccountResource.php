<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'code' => $this->code,

            'name' => $this->name,

            'type' => $this->type,

            'parent_id' => $this->parent_id,

            'is_postable' => $this->is_postable,

            'is_active' => $this->is_active,

            'children' => AccountResource::collection(
                $this->whenLoaded('children')
            ),

            'created_at' => $this->created_at,
        ];
    }
}