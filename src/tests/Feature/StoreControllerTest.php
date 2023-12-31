<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StoreControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test

    protected $_store;
    
    public function setUp(): void
    {
        parent::setUp();
        
        $data = [
            "store_manager_name" => "john",
            "store_address" => "gurgaon"
        ];
         // ------ Add new Store ----------//
        $storeResponse = $this->post(route('stores.store'), $data);
        $storeResponse->assertStatus(201);
        
        $this->_store = $storeResponse;
       
    }
    
    public function test_index()
    {
         // ------ fetch Store list----------//
        $response = $this->get(route('stores.index'));
        $response->assertStatus(200);

        $this->assertEquals($this->_store['result']['store_id'], $response['result'][0]['store_id']);
        
    }

    public function test_store()
    {
        $data = [
            "store_manager_name" => "mick",
            "store_address" => "noida"
        ];
        
         // ------ Add new store ----------//
        $response = $this->post(route('stores.store'), $data);
        $response->assertStatus(201);
        
        // ------ Get store info for compare----------//
        $customerResponse = $this->get(route('stores.show', $response['result']['store_id']));
        $customerResponse->assertStatus(200);

        $this->assertEquals($data['store_address'], $response['result']['store_address']);
        $this->assertEquals($response['result']['store_id'], $response['result']['store_id']);
       
         
    }
    
    public function test_update(){
        
        // ------ update the store ----------//
        $updateRequest = ['store_manager_name' => 'Carter'];
        $updateStoreRes = $this->patch(route('stores.update', $this->_store['result']['store_id']), $updateRequest);
        $updateStoreRes->assertStatus(204);

        // ------ Get store info for compare----------//
        $customerResponse = $this->get(route('stores.show', $this->_store['result']['store_id']));
        $this->assertEquals($updateRequest['store_manager_name'], $customerResponse['result']['store_manager_name']);
        
    }
}
