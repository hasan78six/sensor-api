<?php

namespace App\Helpers;

use Illuminate\Http\Response;

/**
 * Class ApiResponse
 * 
 * Helper class for standardizing API responses in JSON format
 */
class ApiResponse
{
    /**
     * Generate a success response
     *
     * @param mixed|null $data The data to be returned in the response
     * @param string $message The success message
     * @param int $code The HTTP status code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($data = null, $message = 'Success', $code = Response::HTTP_OK)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
        ], $code);
    }

    /**
     * Generate an error response
     *
     * @param string $message The error message
     * @param int $code The HTTP status code
     * @param mixed|null $errors Additional error details
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error($message = 'Error', $code = Response::HTTP_BAD_REQUEST, $errors = null)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}