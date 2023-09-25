<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    
    protected $fillable = [ 'customer_id', 'inventory_id', 'store_id', 'quantity', 'status', 'create_date', 'update_date'];
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    
    public function store($data) : int {
        $inserted =  static::create([
            'customer_id' => $data['customer_id'],
            'inventory_id' => $data['inventory_id'],
            'store_id' => $data['store_id'],
            'quantity' => $data['quantity'],
            'status' => $data['status'] ?? 1,
            'create_date' => date('Y-m-d H:i:s'),
            'update_date' => date('Y-m-d H:i:s'),
        ]);
        return $inserted->order_id;
    }
    
    public function list($perPage = 10) {
        return static::orderby('order_id','desc')->paginate($perPage);
    }
    
    public function report(){
        
        $monthlyReport = static::select(
                DB::raw('YEAR(create_date) as year'),
                DB::raw('MONTH(create_date) as month'),
                'store_id',
                'status',
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('year', 'month', 'store_id', 'status')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();
        
        return $monthlyReport;
    }
}
