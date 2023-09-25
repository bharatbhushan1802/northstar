<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id, 
            'customer_id' => $this->customer_id, 
            'inventory_id' => $this->inventory_id,
            'store_id' => $this->store_id,
            'quantity' => $this->quantity,
            'status' => ($this->status == 0)? 'PENDING': (($this->status == 1)? 'COMPLETED': (($this->status == 2)? 'FAILED': 'INVALID')),
            'create_date' => date('Y-m-d H:i:s', strtotime($this->create_date)), 
            ];
    }
}
