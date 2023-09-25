<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventory;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Http\JsonResponse;
use App\Traits\HttpResponses;
use App\Http\Requests\ValidateInventoryStoreRequest;
use App\Http\Resources\InventoryListResource;


class InventoryController extends Controller
{
    use HttpResponses;
    
    public function store(ValidateInventoryStoreRequest $request) {
      
        $postData = $request->post();
        $postData['manufacture_date'] = date('Y-m-d', strtotime($postData['manufacture_date']));
         try {
            $storerModel = new Inventory();
            $lastInsertedId = $storerModel->store($postData);
            $postData['inventory_id'] = $lastInsertedId;
            return $this->success($postData,  HttpFoundationResponse::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function index(Request $request) : JsonResponse {
        $perPage = 2;
        try {
            $storeModel = new Inventory();
            $records = $storeModel->list($perPage);

            return $this->success_pagination(InventoryListResource::collection($records->items()), $records->currentPage(), $perPage, HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function show(Request $request) : JsonResponse {
        try {
            
            $inventory = Inventory::find($request->id);
            if(empty($inventory)) {
                return $this->error($request->all(), 'Inventory not found', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            return $this->success($inventory,  HttpFoundationResponse::HTTP_OK);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    public function update(Request $request) : JsonResponse {
        $requestData = $request->all();
        try {
        
            $inventory = Inventory::find($request->id);
            
            if(empty($inventory)) {
                return $this->error($request->all(), 'Inventory not found', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            $save = 0;
            if(!empty($requestData['inventory_name'])){
                $save = 1;
                $inventory->inventory_name = $requestData['inventory_name'];
            }
            if(!empty($requestData['available_quantity'])){
                $save = 1;
                $inventory->available_quantity = $requestData['available_quantity'];
            }
            
            if(!empty($requestData['manufacture_date'])){
                $save = 1;
                $inventory->manufacture_date = date('Y-m-d', strtotime($requestData['manufacture_date']));
            }
            
            if($save){
                $inventory->save();
            }else{
                return $this->error($request->all(), 'Request Body is required', HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            return $this->success($inventory,  HttpFoundationResponse::HTTP_NO_CONTENT); //partial update
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
