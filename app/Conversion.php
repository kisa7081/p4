<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{

    public function sourceCurrency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function targetCurrency()
    {
        return $this->belongsTo('App\Currency');
    }
}
