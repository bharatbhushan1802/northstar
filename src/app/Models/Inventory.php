<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;
    
    protected $table = 'inventories';
    protected $fillable = [
        'inventory_name', 'manufacture_date', 'available_quantity'
    ];
    protected $primaryKey = 'inventory_id';
    public $timestamps = false;
    
    public function store($data) {
        $inserted =  static::create([
            'inventory_name' => $data['inventory_name'],
            'manufacture_date' => $data['manufacture_date'],
            'available_quantity' => $data['available_quantity'],
        ]);
        return $inserted->inventory_id;
    }
    
    public function list($perPage = 10) {
        return static::orderby('inventory_id','desc')->paginate($perPage);
    }
    
    public function bookInventory(int $inventoryId, int $quantity) : bool {
        $inventory = static::find($inventoryId);
        if($inventory && $inventory->available_quantity >= $quantity){
            $inventory->available_quantity = $inventory->available_quantity - $quantity;
            $inventory->save();
            return true;
        }
        return false;
    }
}
