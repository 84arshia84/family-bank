<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'user_id' => $this->user_id,
            'price' => $this->Price,
            'get_way' => $this->getway_result,
            'loan_id' => $this->loan_id,
            'installment_id' => $this->installment_id,
            'status' => $this->status,
            'type' => $this->type,
            'date' => $this->date,
            'tracking_code' => $this->tracking_code,
            'description' => $this->description,
            'image' => $this->getFirstMediaUrl('*'),
        ];
    }
}
