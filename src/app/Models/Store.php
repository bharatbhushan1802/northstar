<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'store_manager_name', 'store_address'
    ];
    protected $primaryKey = 'store_id';
    public $timestamps = false;
    
    public function store($data){

        $inserted =  static::create([
            'store_manager_name' => $data['store_manager_name'],
            'store_address' => $data['store_address']
        ]);
        return $inserted->store_id;
    }
    
    public function list($perPage = 10){
        return static::orderby('store_id','desc')->paginate($perPage);
    }
}
