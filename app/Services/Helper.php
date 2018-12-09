<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class Helper
{

    /**
     * Return Success result to API response
     * @param $message
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonOK($message, $data=[])
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data'    => $data,
        ]);
    }

    public static function jsonBool($status)
    {
        return response()->json($status);
    }

    /**
     * Return Not Good result to API response
     * @param $message
     * @param string $logData
     * @return \Illuminate\Http\JsonResponse
     */
    public static function jsonNG($message, $logData='')
    {
        Logger::log('debug', $message, $logData, $back=2);

        return response()->json([
            'status' => false,
            'message' => $message,
        ], 400);
    }
}
