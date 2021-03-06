<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    /*
     * Create the relationships with the currency table.
     *
     */

    public function sourceCurrency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function targetCurrency()
    {
        return $this->belongsTo('App\Currency');
    }
}
