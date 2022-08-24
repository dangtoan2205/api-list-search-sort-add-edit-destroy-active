<?php

namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Log Helper
 */
class LogHelper extends Exception
{
    /**
     * Log exception with trace info about class, method
     *
     * @param Exception $e     Exception.
     * @param string    $level Level.
     *
     * @return void
     */
    public static function logTrace(Exception $e, string $level = 'error')
    {
        $exceptionInfo = '';
        $traceInfo = isset($e->getTrace()[0]) ? $e->getTrace()[0] : null;
        if (!empty($traceInfo)) {
            $classTrace = $traceInfo['class'] ?? '';
            $functionTrace = $traceInfo['function'] ?? '';
            $fileTrace = $traceInfo['file'] ?? '';
            $lineTrace = $traceInfo['line'] ?? '';
            $line = $e->getLine();
            $message = $e->getMessage();
            $exceptionInfo = "[{$classTrace}->{$functionTrace}:{$line}] {$message} call from {$fileTrace}({$lineTrace})";

            if (!empty($exceptionInfo)) {
                LogHelper::logMessage($exceptionInfo, $level);
            }
        }
    }

    /**
     * Write log to file.
     *
     * @param string $message Message.
     * @param string $level   Level.
     *
     * @return void
     */
    public static function logMessage(string $message, string $level = 'error')
    {
        if (empty($level)) {
            $level = 'error';
        }

        Log::$level($message);
    }

    /**
     * Write log to file.
     *
     * @param string $message Message.
     * @param string $channel Channel.
     * @param string $level   Level.
     * @return void
     */
    public static function logChannel(string $message, string $channel = 'stack', string $level = 'info')
    {
        Log::channel($channel)->$level($message);
    }
}
