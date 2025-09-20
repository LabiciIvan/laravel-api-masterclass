<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiResponses {

    protected function ok(string $message, mixed $data = []): JsonResponse {
        return $this->success($message, $data, 200);
    }

    protected function success(string $message, mixed $data, int $statusCode = 200): JsonResponse {
        return response()->json([
            'message' => $message,
            'status' => $statusCode,
            'data' => $data
        ], $statusCode);
    }

    protected function error(string $message, int $statusCode): JsonResponse {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}
