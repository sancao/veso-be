<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use function GuzzleHttp\Psr7\uri_for;

class Logger
{

    /**
     * Log info to system
     * @param $logType
     * @param $message
     * @param string $logData
     * @param int $back
     */
    public static function log($logType, $message, $logData='', $back=1)
    {
        $trackBack      = debug_backtrace()[$back];
        $data['uri']    = uri_for(url()->current())->getPath();
        $data['method'] = $trackBack['class'] . ':' . $trackBack['function'];
        $data['data']   = $logData;

        switch ($logType) {
            case 'debug':
                Log::debug($message, ['data' => $data]);
                break;
            case 'info':
                Log::info($message, ['data' => $data]);
                break;
        }
    }
}
