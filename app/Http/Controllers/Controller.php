<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class Controller
{
    protected $allowedStatus = [
        Response::HTTP_UNAUTHORIZED,
        Response::HTTP_UNPROCESSABLE_ENTITY,
        Response::HTTP_FORBIDDEN
    ];
    protected function response(array $data = [], string $message = '', int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'success' => true
        ], $status);
    }

    protected function error(Exception $e): JsonResponse
    {
        $code = in_array($e->getCode(), $this->allowedStatus) ? $e->getCode() : Response::HTTP_BAD_REQUEST;
        
        return response()->json([
            'message' => $e->getMessage(),
            'success' => false
        ], $code);
    }
}
