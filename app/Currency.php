<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public static function getCurrencyList()
    {
        return self::where('display', '=', true)->orderBy('name')->get();
    }

    public static function getAllCurrencies()
    {
        return self::orderBy('name')->get();
    }
}
