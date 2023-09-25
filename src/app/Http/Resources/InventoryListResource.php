<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return ['inventory_id' => $this->inventory_id, 'inventory_name' => $this->inventory_name, 'manufacture_date' => date('d/m/Y', strtotime($this->manufacture_date)), 'available_quantity' => $this->available_quantity];
    }
}
