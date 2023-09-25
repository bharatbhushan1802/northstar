<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ValidateStoresStoreRequest;
use App\Models\Store;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\StoreListResource;
use App\Traits\HttpResponses;

class StoreController extends Controller
{
    use HttpResponses;

    public function store(ValidateStoresStoreRequest $request) {
      
        $postData = $request->post();
         try {
             
            $storerModel = new Store();
            $lastInsertedId = $storerModel->store($postData);
            $postData['store_id'] = $lastInsertedId;
            return $this->success($postData,  HttpFoundationResponse::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function index(Request $request) : JsonResponse {
        $perPage = 2;
        try {
            $storeModel = new Store();
            $records = $storeModel->list($perPage);

            return $this->success_pagination(StoreListResource::collection($records->items()), $records->currentPage(), $perPage, HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function show(Request $request) : JsonResponse {
        try {
            
            $store = Store::find($request->id);
            if(empty($store)) {
                return $this->error($request->all(), 'Store not found', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            return $this->success($store,  HttpFoundationResponse::HTTP_OK);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    public function update(Request $request) : JsonResponse {
        $requestData = $request->all();
        try {
        
            $store = Store::find($request->id);
            
            if(empty($store)) {
                return $this->error($request->all(), 'Store not found', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            $save = 0;
            if(!empty($requestData['store_manager_name'])){
                $save = 1;
                $store->store_manager_name = $requestData['store_manager_name'];
            }
            if(!empty($requestData['store_address'])){
                $save = 1;
                $store->store_address = $requestData['store_address'];
            }
            
            if($save){
                $store->save();
            }else{
                return $this->error($request->all(), 'Request Body is required', HttpFoundationResponse::HTTP_BAD_REQUEST);
            }

            return $this->success($store,  HttpFoundationResponse::HTTP_NO_CONTENT); //partial update
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
}
