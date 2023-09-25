<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\OrderService;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test
    
    public function test_index()
    {
         // ------ Add new customer ----------//
        $customerData = [ 'name' => 'Mohan Doe', 'phone' => '1230567890'];
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertStatus(201);
        $customerId = $response['result']['customer_id'];
        
         // ------ add new Inventory ----------//
        $inventoryData = [ "inventory_name" => "product_1", "manufacture_date"=> "10/12/2022", "available_quantity" => 2500 ];
        $inventoryResponse = $this->post(route('inventories.store'), $inventoryData);
        $inventoryResponse->assertStatus(201);
        $inventoryId = $inventoryResponse['result']['inventory_id'];
       
        // ------ Add new Store ----------//
        $storeData = [ "store_manager_name" => "nicky", "store_address" => "gurgaon" ];
        $storeResponse = $this->post(route('stores.store'), $storeData);
        $storeResponse->assertStatus(201);
        $storeId = $storeResponse['result']['store_id'];
        
        $orderData = [
            "customer_id" => $customerId,
            "inventory_id" => $inventoryId,
            "store_id" => $storeId,
            "quantity" => 10
        ];
        
        // ----- insert order -----//
        $orderService = new OrderService();
        $result = $orderService->createOrder($orderData);
        $response->assertStatus(201);
        $this->assertIsArray($result);        
        $this->assertEquals(1, $result['status']); 
        
        
        // ------ fetch order list----------//
        $orderList = $this->get(route('orders.index'));
        $orderList->assertStatus(200);

        $this->assertEquals($result['order_id'], $orderList['result'][0]['order_id']);
    }

    public function test_store()
    {
        
        // ------ Add new customer ----------//
        $customerData = [ 'name' => 'rocky', 'phone' => '9002567890'];
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertStatus(201);
        $customerId = $response['result']['customer_id'];
        
         // ------ add new Inventory ----------//
        $inventoryData = [ "inventory_name" => "product_2", "manufacture_date"=> "10/12/2022", "available_quantity" => 1500 ];
        $inventoryResponse = $this->post(route('inventories.store'), $inventoryData);
        $inventoryResponse->assertStatus(201);
        $inventoryId = $inventoryResponse['result']['inventory_id'];
       
        // ------ Add new Store ----------//
        $storeData = [ "store_manager_name" => "Rocky", "store_address" => "Noida" ];
        $storeResponse = $this->post(route('stores.store'), $storeData);
        $storeResponse->assertStatus(201);
        $storeId = $storeResponse['result']['store_id'];
        
        $orderData = [
            "customer_id" => $customerId,
            "inventory_id" => $inventoryId,
            "store_id" => $storeId,
            "quantity" => 100
        ];
        
        // ----- insert order -----//
        $orderService = new OrderService();
        $result = $orderService->createOrder($orderData);
        $response->assertStatus(201);
        $this->assertIsArray($result);        
        $this->assertEquals(1, $result['status']); 
        
        $orderId =  $result['order_id'];
        
        // ------ Get order info for compare----------//
        $orderResponse = $this->get(route('orders.show', $orderId));
        $orderResponse->assertStatus(200);

        $this->assertEquals($orderId, $orderResponse['result']['order_id']);
    }
    
    
    public function test_update(){
        
        // ------ Add new customer ----------//
        $customerData = [ 'name' => 'rocky mohan', 'phone' => '9000567890'];
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertStatus(201);
        $customerId = $response['result']['customer_id'];
        
         // ------ add new Inventory ----------//
        $inventoryData = [ "inventory_name" => "product_3", "manufacture_date"=> "10/02/2023", "available_quantity" => 1900 ];
        $inventoryResponse = $this->post(route('inventories.store'), $inventoryData);
        $inventoryResponse->assertStatus(201);
        $inventoryId = $inventoryResponse['result']['inventory_id'];
       
        // ------ Add new Store ----------//
        $storeData = [ "store_manager_name" => "Kia", "store_address" => "Delhi" ];
        $storeResponse = $this->post(route('stores.store'), $storeData);
        $storeResponse->assertStatus(201);
        $storeId = $storeResponse['result']['store_id'];
        
        $orderData = [
            "customer_id" => $customerId,
            "inventory_id" => $inventoryId,
            "store_id" => $storeId,
            "quantity" => 50,
            "status" => 0
        ];
        
        // ----- insert order -----//
        $orderService = new OrderService();
        $result = $orderService->createOrder($orderData);
        $response->assertStatus(201);
        $this->assertIsArray($result);        
        
        $orderId =  $result['order_id'];
        
        // ---- now update the status of order ----// 
        $orderupdateData['status'] = 1;
        $updateInventoryRes = $this->patch(route('orders.update', $orderId), $orderupdateData);
        $updateInventoryRes->assertStatus(201);
        
    }
    
    public function test_report(){
        
               // ------ Add new customer ----------//
        $customerData = [ 'name' => 'rocky', 'phone' => '9002567890'];
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertStatus(201);
        $customerId = $response['result']['customer_id'];
        
         // ------ add new Inventory ----------//
        $inventoryData = [ "inventory_name" => "product_2", "manufacture_date"=> "10/12/2022", "available_quantity" => 1500 ];
        $inventoryResponse = $this->post(route('inventories.store'), $inventoryData);
        $inventoryResponse->assertStatus(201);
        $inventoryId = $inventoryResponse['result']['inventory_id'];
       
        // ------ Add new Store ----------//
        $storeData = [ "store_manager_name" => "Rocky", "store_address" => "Noida" ];
        $storeResponse = $this->post(route('stores.store'), $storeData);
        $storeResponse->assertStatus(201);
        $storeId = $storeResponse['result']['store_id'];
        
        $orderData = [
            "customer_id" => $customerId,
            "inventory_id" => $inventoryId,
            "store_id" => $storeId,
            "quantity" => 100
        ];
        
        // ----- insert order -----//
        $orderService = new OrderService();
        $result = $orderService->createOrder($orderData);
        $response->assertStatus(201);
        
        
        // ------ Get order report ----------//
        $orderResponse = $this->get(route('orders.report'));
        $orderResponse->assertStatus(200);
        $this->assertEquals(1, $orderResponse['result'][0]['order_count']);
        $this->assertEquals($storeId, $orderResponse['result'][0]['store_id']);
        
    }
}
