<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalEntryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'reference' => $this->reference,

            'entry_date' => $this->entry_date,

            'description' => $this->description,

            'status' => $this->status,

            'total_debit' => (float) $this->lines->sum('debit'),

            'total_credit' => (float) $this->lines->sum('credit'),

            'lines' => JournalEntryLineResource::collection(
                $this->whenLoaded('lines')
            ),

            'created_at' => $this->created_at,
        ];
    }
}