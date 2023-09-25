<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Customer;
use App\Models\Store;
use App\Models\Inventory;
use App\Models\Order;

/**
 * class OrderService
 */

 class OrderService
 {
    public function createOrder(array $data) : array | Exception {

        $customer = $this->isCustomerExists($data['customer_id']);
        if($customer == false) {
            throw new \Exception(USER_NOT_EXISTS);
        }
        
        $store = $this->isStorerExists($data['store_id']);
        if($store == false) {
            throw new \Exception(STORE_NOT_EXISTS);
        }
        
        $inventory = $this->isInventoryExists($data['inventory_id']);
        if(empty($inventory)) {
            throw new \Exception(INVENTORY_NOT_EXISTS);
        }
        
        if($data['quantity'] > $inventory->available_quantity) {
            $msg = "";
            if($inventory->available_quantity > 0) {
                $msg = INSUFFICIENT_QUANTITY . " But can order upto ". $inventory->available_quantity. " quantity.";
            }
            throw new \Exception($msg);
        }
        
        $data['status'] = isset($data['status'])? $data['status'] : 1;
        
        try {
            DB::beginTransaction();
            
            $inventory = new Inventory();   
            $booked = $inventory->bookInventory($data['inventory_id'], $data['quantity']);
            if ($booked == false) {
                 DB::rollBack();
                 throw new \Exception(INTERNAL_SERVER_ERROR);
            }
            
            $order = new Order();
            $orderId = $order->store($data);
            
            DB::commit();
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(INTERNAL_SERVER_ERROR);
        }
        
        $data['order_id'] = $orderId;
        return $data;
    }
    
    protected function isCustomerExists(int $customerId) : bool {
       return !empty(Customer::find($customerId))? true: false;
    }
    
    protected function isStorerExists(int $storeId) : bool {
       return !empty(Store::find($storeId))? true: false;
    }
    
    protected function isInventoryExists(int $inventoryId) : Inventory {
       return Inventory::find($inventoryId);
    }
    

    
    public function updateOrder(int $orderId, array $data) : Order | Exception {
        try {
            
            if (!(isset($data['status'])) || !in_array($data['status'], [0, 1, 2])) {
                 throw new \Exception('Invalid Request. please pass valid status.');
            }
            
            $order = Order::find($orderId);
            if(empty($order)) {
                throw new \Exception(ORDER_NOT_EXISTS);
            }
            
            if($data['status'] == $order->status) {
                throw new \Exception("Order Status already updated.");
            }
            
            if( ($order->status == 2 && $data['status'] == 1) || ($order->status == 2 && $data['status'] == 0) || ($order->status == 1 && $data['status'] == 0)
                    || ($order->status == 1 && $data['status'] == 2) ) {
                throw new \Exception("Can Not change the order status from ".$order->status ." to ". $data['status']."  updated.");
            }
            
            $order->update_date = date('Y-m-d H:i:s');
            // ------ only update the status
            if($order->status == 0 && $data['status'] == 1){
                $order->status = $data['status'];
                $order->save();
                
                return $order;
            }
            
            $inventory = $this->isInventoryExists($order->inventory_id);
            if(empty($inventory)) {
                throw new \Exception(INVENTORY_NOT_EXISTS);
            }
            
            DB::beginTransaction();
            
            // revert quantity in inventory and update order status
            $inventory->available_quantity = ($inventory->available_quantity + $order->quantity);
            $inventory->save();
            
            $order->status = $data['status'];
            $order->save();
            
            DB::commit();
            
           return $order;
        } catch (QueryException $e) {
            DB::rollBack();
            throw new \Exception(INTERNAL_SERVER_ERROR);
        }
        return [];
    }
 }