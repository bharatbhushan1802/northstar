<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use App\Http\Requests\ValidateOrderStoreRequest;
use App\Services\OrderService;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderReportResource;

class OrderController extends Controller
{
    use HttpResponses;
    protected $orderService;
    
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }
    
    public function store(ValidateOrderStoreRequest $request) : JsonResponse {
      
        $postData = $request->post();
       
        try {
            $data = $this->orderService->createOrder($postData);
            return $this->success($data,  HttpFoundationResponse::HTTP_CREATED);
            
        } catch (\Exception $e) {
            dd($e);
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function index() : JsonResponse {
        $perPage = 2;
        try {
            $orderModel = new Order();
            $records = $orderModel->list($perPage);

            return $this->success_pagination(OrderListResource::collection($records->items()), $records->currentPage(), $perPage, HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function show(Request $request) : JsonResponse {
        try {
            
            $order = Order::find($request->id);
            if(empty($order)) {
                return $this->error($request->all(), 'Order not found', HttpFoundationResponse::HTTP_NOT_FOUND);
            }
            return $this->success($order,  HttpFoundationResponse::HTTP_OK);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    
    public function update(Request $request) : JsonResponse {
        $requesteddata = $request->all();
        
        try {
            $data = $this->orderService->updateOrder($request->id, $requesteddata);
            return $this->success($data,  HttpFoundationResponse::HTTP_CREATED);
            
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    public function report() : JsonResponse {
        try {
            $orderObj = new Order();
            $report = $orderObj->report();
            return $this->success(OrderReportResource::collection($report),  HttpFoundationResponse::HTTP_OK);
        } catch (\Exception $e) {
            return $this->error([], $e->getMessage(), HttpFoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
        }        
    }
}
