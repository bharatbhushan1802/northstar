<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

trait HttpResponses {
    protected function success($data, int $code = HttpFoundationResponse::HTTP_OK) : JsonResponse {
        return response()->json([
            'status' => 'success',
            'result' => $data
        ], $code);
    }

    protected function error($data, string $message = null, int $code = HttpFoundationResponse::HTTP_BAD_REQUEST) : JsonResponse {
        return response()->json([
            'status' => 'failed',
            'message' => $message,
            'result' => $data
        ], $code);
    }
    
    protected function success_pagination($data, int $currentPage = 1,  int $perPage = 10, int $code = HttpFoundationResponse::HTTP_OK) : JsonResponse {
        return response()->json([
            'pagination' => ['page' => $currentPage, 'perPage' => $perPage],
            'result' => $data
        ], $code);
    }
}