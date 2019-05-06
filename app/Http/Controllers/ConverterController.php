<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Converter;
use Illuminate\Support\Facades\Cache;

class ConverterController extends Controller
{
    /*
     * The initial landing page. Sets initial values.
     */
    public function index(Request $req)
    {
        $conv = $this->getConverter();

        return view('index'
        )->with(
            [
                'ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'keys' => $conv->getKeys(),
                'currency_list' => $conv->getCurrencyList(),
                'amount' => null,
                'current' => null,
                'target' => null,
                'round' => null
            ]);
    }

    /*
     * Method to make a currency conversion based
     * on th user's input.
     */
    public function convert(Request $req)
    {
        $req->validate([
            'amount' => 'required|numeric'
        ]);

        $conv = $this->getConverter();

        $rates = $conv->getRatesArray();

        $converted = $conv->convert($rates[$req->current][$req->target], $req->amount, $req->round);

        return view('index')->with(
            [
                'ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'amount' => $req->amount,
                'currency_list' => $conv->getCurrencyList(),
                'current' => $req->current,
                'target' => $req->target,
                'round' => $req->round,
                'converted' => $converted,
                'keys' => $conv->getKeys()
            ]);
    }

    /*
     * Fetches new values for the conversion rates and resets the page.
     */
    public function refresh()
    {
        $this->refreshRates();

        return redirect('/');
    }

    /*
     * Get the converter object from the cache, or
     * create it and cache it if it doesn't exist yet.
     */
    private function getConverter()
    {
        $conv = null;

        if (Cache::has('converter')) {
            $conv = Cache::get('converter');
        } else {
            $conv = new Converter();
            Cache::put('converter', $conv);
        }

        return $conv;
    }

    /*
     * Private method to fetch the most recent currency rates and
     * store the new converter in the cache overwriting the previous object.
     */
    private function refreshRates()
    {
        $conv = new Converter();

        Cache::put('converter', $conv);
    }
}
