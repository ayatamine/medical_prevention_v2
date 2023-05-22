<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsDates
{
    /**
     * Format a date using Carbon.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     */
    public function formatDate($date, $format = 'M j, Y')
    {
        return Carbon::parse($date)->format($format);
    }
    /**
     * Format a date using Carbon.
     *
     * @param string $date
     * @param string $format
     *
     * @return string
     */
    public function formatDiffForHumans($date, $format = 'Y-m-d')
    {
        return Carbon::parse($date)->diffForHumans();
    }
}