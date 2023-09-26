<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\OrderService;
use App\Http\Controllers\OrderController;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test
    
    protected $_customerId;
    protected $_inventoryId;
    protected $_storeId;
    
    public function setUp(): void
    {
        parent::setUp();
        
         // ------ Add new customer ----------//
        $customerData = [ 'name' => 'Mohan Doe', 'phone' => '1230567890'];
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertStatus(201);
        $this->_customerId = $response['result']['customer_id'];
          
         // ------ add new Inventory ----------//
        $inventoryData = [ "inventory_name" => "product_1", "manufacture_date"=> "10/12/2022", "available_quantity" => 2500 ];
        $inventoryResponse = $this->post(route('inventories.store'), $inventoryData);
        $inventoryResponse->assertStatus(201);
        $this->_inventoryId = $inventoryResponse['result']['inventory_id'];
        
        // ------ Add new Store ----------//
        $storeData = [ "store_manager_name" => "nicky", "store_address" => "gurgaon" ];
        $storeResponse = $this->post(route('stores.store'), $storeData);
        $storeResponse->assertStatus(201);
        $this->_storeId = $storeResponse['result']['store_id'];
    }
    
    public function test_index()
    {
        $orderData = [
            "customer_id" => $this->_customerId,
            "inventory_id" => $this->_inventoryId,
            "store_id" => $this->_storeId,
            "quantity" => 10
        ];
        
        // ----- insert order -----//
        $orderService = new OrderService();
        $result = $orderService->createOrder($orderData);
        $this->assertIsArray($result);      
        $this->assertEquals(1, $result['status']); 
        
        // ------ fetch order list----------//
        $orderList = $this->get(route('orders.index'));
        $orderList->assertStatus(200);

        $this->assertEquals($result['order_id'], $orderList['result'][0]['order_id']);
    }

    public function test_store()
    {

        $orderData = [
            "customer_id" => $this->_customerId,
            "inventory_id" => $this->_inventoryId,
            "store_id" => $this->_storeId,
            "quantity" => 100
        ];
          
        // ----- insert order -----//
        $response = $this->post(route('orders.store'), $orderData);
        $result = $response->assertStatus(201);
        $orderId =  $result['result']['order_id'];
        $this->assertEquals(1, $result['result']['status']); 
        
        
        // ------ Get order info for compare----------//
        $orderResponse = $this->get(route('orders.show', $orderId));
        $orderResponse->assertStatus(200);

        $this->assertEquals($orderId, $orderResponse['result']['order_id']);
    }
    
    
    public function test_update(){
        
        $orderData = [
            "customer_id" => $this->_customerId,
            "inventory_id" => $this->_inventoryId,
            "store_id" => $this->_storeId,
            "quantity" => 50,
            "status" => 0
        ];
        
        // ----- insert order -----//
        $response = $this->post(route('orders.store'), $orderData);
        $result = $response->assertStatus(201);
        $orderId =  $result['result']['order_id'];
        
        // ---- now update the status of order ----// 
        $orderupdateData['status'] = 1;
        $updateInventoryRes = $this->patch(route('orders.update', $orderId), $orderupdateData);
        $updateInventoryRes->assertStatus(201);
        
    }
    
    public function test_report(){
        
        $orderData = [
            "customer_id" => $this->_customerId,
            "inventory_id" => $this->_inventoryId,
            "store_id" => $this->_storeId,
            "quantity" => 100
        ];
        
        // ----- insert order -----//
        $response = $this->post(route('orders.store'), $orderData);
        $orderResponse = $response->assertStatus(201);
        
        
        // ------ Get order report ----------//
        $orderResponse = $this->get(route('orders.report'));
        $orderResponse->assertStatus(200);

        $this->assertEquals(1, $orderResponse['result'][0]['order_count']);
        $this->assertEquals($this->_storeId, $orderResponse['result'][0]['store_id']);
        
    }
}
