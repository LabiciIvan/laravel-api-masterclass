<?php

namespace App\Traits;

trait ApiResponses {

    protected function ok($message) {
        return $this->success($message);
    }

    protected function success(string $message, int $statusCode = 200) {
        return response()->json([
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }
}