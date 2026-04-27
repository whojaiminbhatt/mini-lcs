<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait Responder
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function success($data = [], string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function error(string $message = 'Error', int $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

    /**
     * Return an internal server error JSON response.
     *
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function internalError(string $message = 'Internal Server Error', int $code = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }
}
