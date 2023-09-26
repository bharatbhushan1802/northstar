<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test
    
    protected $_inventory;
    
    public function setUp(): void
    {
        parent::setUp();
        $data = [
                "inventory_name" => "product_1",
                "manufacture_date"=> "10/09/2023",
                "available_quantity" => 4000
        ];
        
        // ------ add new Inventory ----------//
        $inventoryResponse = $this->post(route('inventories.store'), $data);
        $inventoryResponse->assertStatus(201);
        
        $this->_inventory = $inventoryResponse;
       
    }
    
    public function test_index()
    {
         // ------ fetch Inventory list----------//
        $response = $this->get(route('inventories.index'));
        $response->assertStatus(200);

        $this->assertEquals($this->_inventory['result']['inventory_id'], $response['result'][0]['inventory_id']);
    }

    public function test_store()
    {
        $data = [
            "inventory_name" => "product_2",
            "manufacture_date"=> "10/09/2023",
            "available_quantity" => 3000
        ];
        
         // ------ add new Inventory ----------//
        $response = $this->post(route('inventories.store'), $data);
        $response->assertStatus(201);
        
        // ------ Get Inventory info for compare----------//
        $inventoryResponse = $this->get(route('inventories.show', $response['result']['inventory_id']));
        $inventoryResponse->assertStatus(200);

        $this->assertEquals($data['inventory_name'], $inventoryResponse['result']['inventory_name']);
        $this->assertEquals($response['result']['inventory_id'], $response['result']['inventory_id']);
       
         
    }
    
    public function test_update(){
              
        // ------ update the Inventory ----------//
        $updateRequest = ['available_quantity' => 3000];
        $updateInventoryRes = $this->patch(route('inventories.update', $this->_inventory['result']['inventory_id']), $updateRequest);
        $updateInventoryRes->assertStatus(204);

        // ------ Get Inventory info for compare----------//
        $updatedRes = $this->get(route('inventories.show', $this->_inventory['result']['inventory_id']));
        $this->assertEquals($updateRequest['available_quantity'], $updatedRes['result']['available_quantity']);
        
    }
}
