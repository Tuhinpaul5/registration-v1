<?php

if (! function_exists('response_handler')) {
    /**
     * Return a standardized JSON response.
     *
     * @param bool $success
     * @param string $message
     * @param int $code
     * @param mixed $data
     * @return \Illuminate\Http\JsonResponse
     */
    function response_handler(string $message, bool $success = true, int $code = 200, ?array $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data,
            'code' => $code
        ], $code);
    }
}