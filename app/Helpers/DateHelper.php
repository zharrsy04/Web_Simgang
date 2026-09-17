<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Format date to Indonesian locale with full date format
     * Example: "Senin, 13 April 2025"
     */
    public static function formatIndonesianLong($date)
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->translatedFormat('l, d F Y');
    }

    /**
     * Format date to Indonesian locale with short date format
     * Example: "13 Apr 2025"
     */
    public static function formatIndonesianShort($date)
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->translatedFormat('d M Y');
    }

    /**
     * Format date to Indonesian locale with day format
     * Example: "13/04/2025"
     */
    public static function formatIndonesianSlash($date)
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->format('d/m/Y');
    }

    /**
     * Format date to Indonesian locale with dash format
     * Example: "13-04-2025"
     */
    public static function formatIndonesianDash($date)
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->format('d-m-Y');
    }

    /**
     * Format date to ISO format with Indonesian locale
     * Example: "Senin, 13 April 2025"
     */
    public static function formatIndonesianISO($date)
    {
        if (!$date) {
            return '-';
        }
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        return $carbon->translatedFormat('dddd, D MMMM Y');
    }
}
