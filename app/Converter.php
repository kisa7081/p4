<?php

namespace App;
use App\Currency;

class Converter
{
    #Properties
    /*
     * The $currency_list is used to get the conversion rates.
     * Also, the keys are used as option values in the
     * base currency dropdown, and the values are used for display.
     */
    private $currency_list;

    private $all_currencies;

    /*
     * This is an array of the keys of the $currency_list.
     * It's used to iterate through the different currencies.
     */
    private $keys;

    /*
     * An array the holds the conversion rates.
     */
    private $ratesArray;

    /*
     * Time stamp signifying when the rates were retrieved.
     */
    private $ratesTimeStamp;

    #Methods
    /*
     * Magic constructor method used to instantiate an instance of Converter.
     */
    public function __construct()
    {
        # Set up the values that will be used.
        $this->currency_list = Currency::getCurrencyList();
        $this->all_currencies = Currency::getAllCurrencies();
        $this->keys = explode(',', $this->currency_list->implode('code', ','));
        $this->ratesArray = $this->createRatesArray();
        $this->ratesTimeStamp = date("F j, Y g:i a T");
    }

    public function getKeys()
    {
        return $this->keys;
    }

    /*
     * Public method that returns the currency list.
     */
    public function getCurrencyList()
    {
        return $this->currency_list;
    }

    public function getAllCurrencies()
    {
        return $this->all_currencies;
    }

    /*
     * Private method called by the constructor to get
     * the latest conversion rates.
     */
    private function createRatesArray()
    {
        $conversions = []; #Initailize the array.
        /*
         * $join is a String used in the URL to
         * list the conversion rates we want
         * for the "base."  The $keys are iterated over
         * to get the "base" value in the URL.
         */
        $join = join(',', $this->keys);
        foreach ($this->keys as $key) {
            $url = 'https://api.exchangeratesapi.io/latest?base=' . $key . '&symbols=' . $join;
            $fp = fopen($url, 'r');
            $data = json_decode(stream_get_contents($fp));
            $d = $data->rates;
            $ar = [];
            foreach ($this->keys as $k) {
                array_push($ar, $d->$k);
            }
            $conversions[$key] = $ar;
        }
        return $conversions;
    }

    /*
     * Returns the time stamp of when the rates were fetched.
     */
    public function getRatesTimeStamp()
    {
        return $this->ratesTimeStamp;
    }

    /*
     * Returns the array containing the rates.
     */
    public function getRatesArray()
    {
        return $this->ratesArray;
    }

    /*
     * Public method to do the actual conversion.
     * If $round is true, the value is rounded
     * to the nearest whole number.  Otherwise,
     * it's rounded to the nearest hundredth.
     */
    public function convert($conversion, float $amount, $round = false)
    {
        $converted = $amount * (float)$conversion;

        $dec_places = $round ? 0 : 2; # ternary operation

        $converted = number_format(round($converted, 2), $dec_places) . ($round ? '.00' : '');

        return $converted;
    }

}