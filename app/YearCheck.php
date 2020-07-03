<?php

namespace App; 
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class YearCheck extends Model
{
    public static function getYear()
    {
        try { 
            $year = SmGeneralSettings::first()->academic_Year->year; 
            return $year;
        } catch (\Exception $e) {
            return date('Y');
        }
    }
}
