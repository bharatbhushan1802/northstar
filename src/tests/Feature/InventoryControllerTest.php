<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test
    
    public function test_index()
    {
        $data = [
                "inventory_name" => "product_1",
                "manufacture_date"=> "10/09/2023",
                "available_quantity" => 4000
        ];
         // ------ add new Inventory ----------//
        $inventoryResponse = $this->post(route('inventories.store'), $data);
        $inventoryResponse->assertStatus(201);

         // ------ fetch Inventory list----------//
        $response = $this->get(route('inventories.index'));
        $response->assertStatus(200);

        $this->assertEquals($data['inventory_name'], $response['result'][0]['inventory_name']);
        $this->assertEquals($inventoryResponse['result']['inventory_id'], $response['result'][0]['inventory_id']);
        
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
        $data = [
            "inventory_name" => "product_3",
            "manufacture_date"=> "10/12/2022",
            "available_quantity" => 2500
        ];
         // ------ add new Inventory ----------//
        $inventoryResponse = $this->post(route('inventories.store'), $data);
        $inventoryResponse->assertStatus(201);
        $expectedId = $inventoryResponse['result']['inventory_id'];
        
        // ------ update the Inventory ----------//
        $updateRequest = ['available_quantity' => 3000];
        $updateInventoryRes = $this->patch(route('inventories.update', $expectedId), $updateRequest);
        $updateInventoryRes->assertStatus(204);

        // ------ Get Inventory info for compare----------//
        $updatedRes = $this->get(route('inventories.show', $expectedId));
        $this->assertEquals($updateRequest['available_quantity'], $updatedRes['result']['available_quantity']);
        
    }
}
