<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'year' => $this->year, 
            'month' => $this->month, 
            'store_id' => $this->store_id,
            'store_id' => $this->store_id,
            'status' => ($this->status == 0)? 'PENDING': (($this->status == 1)? 'COMPLETED': (($this->status == 2)? 'FAILED': 'INVALID')),
            'order_count' => $this->order_count, 
            ];
    }
}
