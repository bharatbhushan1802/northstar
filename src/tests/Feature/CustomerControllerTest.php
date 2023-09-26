<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use App\Models\Customer; // Adjust the namespace as per your project

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase; // Use this trait to reset the database after each test
    
    protected $_customer;
    
    public function setUp(): void
    {
        parent::setUp();
        // creating user
        $data = [
            'name' => 'John Doe',
            'phone' => '1234067890',
        ];
         // ------ Add new customer ----------//
        $storeResponse = $this->post(route('customers.store'), $data);
        $storeResponse->assertStatus(201);
        
        $this->_customer = $storeResponse;
       
    }
    
    public function test_index()
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
        
        $this->assertEquals('John Doe', $response['result'][0]['customer_name']);
        $this->assertEquals($this->_customer['result']['customer_id'], $response['result'][0]['customer_id']);
        
    }

    public function test_store()
    {
        $data = [
            'name' => 'John Doe',
            'phone' => '1234567890',
        ];

         // ------ Add new customer ----------//
        $response = $this->post(route('customers.store'), $data);
        $response->assertStatus(201);
        // ------ Get customer info for compare----------//
        $customerResponse = $this->get(route('customers.show', $response['result']['customer_id']));
        $customerResponse->assertStatus(200);

        $this->assertEquals($customerResponse['result']['phone'], $response['result']['phone']);
        $this->assertEquals($response['result']['customer_id'], $response['result']['customer_id']);
         
    }
    
    public function test_update(){
        
        $customerId = $this->_customer['result']['customer_id'];
        
        // ------ update the customer ----------//
        $updateRequest = ['customer_name' => 'Neha Bhushan'];
        $updateResponse = $this->patch(route('customers.update', $customerId), $updateRequest);
        $updateResponse->assertStatus(204);

        // ------ Get customer info for compare----------//
        $customerResponse = $this->get(route('customers.show', $customerId));
        $customerResponse->assertStatus(200);
        $this->assertEquals($updateRequest['customer_name'], $customerResponse['result']['customer_name']);
        
    }

}