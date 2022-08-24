<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Common Helper
 *
 * @author
 */
class CommonHelper
{
    // Flag for datetime/timestamp
    const DATETIME_TYPE = 0;
    const TIMESTAMP_TYPE = 1;
    const DEFAULT_TIMEZONE = 'Asia/Tokyo';
    const DEFAULT_DATETIME_FORMAT = 'Y-m-d H:i';

    /**
     * Convert date string to timestamp.
     *
     * @param mixed $dateTimeString DateTimeString.
     * @param mixed $format         Format.
     * @param mixed $timezone       TimeZone.
     *
     * @return integer
     */
    public static function convertDateToTimestamp($dateTimeString, $format = self::DEFAULT_DATETIME_FORMAT, $timezone = self::DEFAULT_TIMEZONE)
    {
        try {
            return Carbon::createFromFormat($format, $dateTimeString)->setTimezone($timezone)->timestamp;
        } catch (\Exception $e) {
            Log::error('[CommonHelper->convertDateToTimestamp:' . __LINE__ . '] ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Convert timestamp to datetime.
     *
     * @param mixed $timestamp Timestamp.
     * @param mixed $format    Format.
     * @param mixed $timezone  TimeZone.
     *
     * @return string
     */
    public static function convertTimestampToDate($timestamp, $format = self::DEFAULT_DATETIME_FORMAT, $timezone = self::DEFAULT_TIMEZONE)
    {
        try {
            return Carbon::createFromTimestamp($timestamp, $timezone)->format($format);
        } catch (\Exception $e) {
            Log::error('[CommonHelper->convertTimestampToDate:' . __LINE__ . '] ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get current date time with timezone and format.
     *
     * @param mixed $type     Datetime/Timestamp for get result.
     * @param mixed $format   Format of datetime.
     * @param mixed $timezone Timezone.
     *
     * @return integer|string|object
     */
    public static function getNow($type = self::DATETIME_TYPE, $format = self::DEFAULT_DATETIME_FORMAT, $timezone = self::DEFAULT_TIMEZONE)
    {
        switch ($type) {
            case self::TIMESTAMP_TYPE:
                return Carbon::now()->timezone($timezone)->timestamp;
                break;
            case self::DATETIME_TYPE:
                return Carbon::now()->timezone($timezone)->format($format);
                break;
            default:
                return Carbon::now()->timezone($timezone);
                break;
        }
    }
}
