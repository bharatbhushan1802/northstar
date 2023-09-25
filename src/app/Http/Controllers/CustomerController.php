<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\HttpResponses;
use App\Http\Requests\ValidateCustomerStoreRequest;
use App\Http\Requests\ValidateCustomerupdateRequest;
use Illuminate\Http\JsonResponse;

use App\Models\Customer;
use Validator;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;


use App\Http\Resources\CustomerListResource;

class CustomerController extends Controller
{
    use HttpResponses;
    
//    public function __construct()
//    {
//       
//    }
    
    public function index(Request $request) : JsonResponse {
        $perPage = 2;
        try {
            $customerMode = new Customer();
            $records = $customerMode->list($perPage);

            return $this->success_pagination(CustomerListResource::collection($records->items()), $records->currentPage(), $perPage, 200);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    

    public function store(ValidateCustomerStoreRequest $request) : JsonResponse {
        try {
            $postData = $request->post();
            if (preg_match('/^[0-9]{10}$/', $request->phone) == 0){
                return $this->error($postData, 'Invalid Phone', 400);
            }
            
            $customerModel = Customer::where('phone', $request->phone)->first();
            if(!empty($customerModel)){
                // --- 409 Conflict - if the server will not process a request, but the reason for that is not the client's fault
                return $this->error($postData, 'Phone Number Already Exists.', 409);
            }

            $customerMode = new Customer();
            $postData['customer_id'] = $customerMode->store($postData);
            
            return $this->success($postData,  HttpFoundationResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
    
    
    public function show(Request $request) : JsonResponse{
        try {
//            dd($request->all());
            $customer = Customer::find($request->id);
            if(empty($customer)){
                return $this->error($request->all(), 'Invalid Customer', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            return $this->success($customer,  HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }
    
    public function update(ValidateCustomerupdateRequest $request) : JsonResponse {
//    public function update(Request $request) : JsonResponse {
        try {        
        $requestData = $request->all();
        if(isset($requestData['phone']) && preg_match('/^[0-9]{10}$/', $requestData['phone']) == 0){
            return $this->error($requestData, 'Invalid Phone', HttpFoundationResponse::HTTP_OK);
        }
        $save = 0;
        
        $customer = Customer::find($request->id);
        if(!empty($requestData['phone']) && $customer->phone != $requestData['phone']){
            // ----- check phone is exists or not ------
            $phoneExists = Customer::where('phone', $requestData['phone'])->first();
            if($phoneExists){
                return $this->error($requestData, 'Phone is already registered with other customer.', 400);
            }
            $save = 1;
            $customer->phone = $requestData['phone'];
        }
        
        if(!empty($requestData['customer_name'])){
            $save = 1;
            $customer->customer_name = $requestData['customer_name'];
        }
        
        if($save){
            $customer->save();
        }else{
            return $this->error($request->all(), 'Request Body is required', HttpFoundationResponse::HTTP_BAD_REQUEST);
        }

        return $this->success($customer,  HttpFoundationResponse::HTTP_NO_CONTENT); //partial update
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }          
    }
}
