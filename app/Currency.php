<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    // Return a collection instead of an array to take advantage of the
    // collections functions.  Call toArray() as needed.
    public static function getCurrencyList()
    {
        return self::where('display', '=', true)->orderBy('name')->get();
    }

    public static function getAllCurrencies()
    {
        return self::orderBy('name')->get();
    }
}
