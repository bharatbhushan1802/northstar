<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer  extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name', 'phone'
    ];
    protected $primaryKey = 'customer_id';
    public $timestamps = false;
        
    public function store($data){
      
        $inserted =  static::create([
            'customer_name' => $data['name'],
            'phone' => $data['phone']
        ]);
        return $inserted->customer_id;
    }
    
    public function list($perPage = 10){
        return static::orderby('customer_id','desc')->paginate($perPage);
    }

}
