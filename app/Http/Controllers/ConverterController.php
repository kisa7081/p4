<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Converter;
use Illuminate\Support\Facades\Cache;
use App\Currency;
use App\Conversion;

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
                'currency_list' => $conv->getCurrencyList()->toArray(),
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
            'amount' => 'required|numeric|min:0|max:100000000000'
        ]);

        $conv = $this->getConverter();

        $rates = $conv->getRatesArray();

        $source_currency_id = $conv->getCurrencyList()->firstWhere('code', $req->current)->toArray()['id'];
        $target_currency_id = $conv->getCurrencyList()->toArray()[$req->target]['id'];

        $rate = $rates[$req->current][$req->target];
        $amount = $req->amount;

        $converted = $conv->convert($rate, $amount, $req->round);

        $conversion = new Conversion();
        $conversion->sourceAmount = $amount;
        $conversion->rate = $rate;
        $conversion->convertedAmount = (float)str_replace(',', '', $converted);
        $conversion->timeStamp = now();
        $conversion->source_currency_id = $source_currency_id;
        $conversion->target_currency_id = $target_currency_id;
        $conversion->save();

        return view('index')->with(
            [
                'ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'amount' => $req->amount,
                'currency_list' => $conv->getCurrencyList()->toArray(),
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

    public function choose()
    {
        $conv = $this->getConverter();
        return view('choose'
        )->with(
            ['ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'currency_list' => $conv->getAllCurrencies()->toArray()
            ]);
    }

    public function saveChoices(Request $req)
    {
        $req->validate([
            'currencies' => 'required'
        ]);

        Currency::whereIn('id', array_values($req->currencies))->update(['display'=> true]);
        Currency::whereNotIn('id', array_values($req->currencies))->update(['display'=>false]);
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
