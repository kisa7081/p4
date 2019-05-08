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

        return view('index')->with(
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

        /*
         *  Since the currency list is created from a web service, it isn't exactly
         *  model friendly.  Fortunately, it's a square matrix with the column value
         *  for a currency equaling the row value, so retrieving the target currency
         *  based on the index is easy.  The source currency must be found via its 'code' value.
         */
        $source_currency_id = $conv->getCurrencyList()->firstWhere('code', $req->current)->toArray()['id'];
        $target_currency_id = $conv->getCurrencyList()->toArray()[$req->target]['id'];

        $rate = $rates[$req->current][$req->target];
        $amount = $req->amount;
        // Now use the values gathered above to make a conversion.
        $converted = $conv->convert($rate, $amount, $req->round);

        // Now we create a new conversion model to be stored in the database.
        $conversion = new Conversion();
        $conversion->source_amount = $amount;
        $conversion->rate = $rate;
        $conversion->converted_amount = (float)str_replace(',', '', $converted);
        $conversion->time_stamp = now();
        $conversion->source_currency_id = $source_currency_id;
        $conversion->target_currency_id = $target_currency_id;
        //  This should create a new database record in the conversions table.
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

    /*
     * Get the list of currencies for the user to choose from.
     */
    public function choose()
    {
        $conv = $this->getConverter();

        return view('choose')->with(
            [
                'ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'currency_list' => $conv->getAllCurrencies()->toArray()
            ]);
    }

    /*
     *  Update the list of currency choices.  A true display value means
     *  that it will be a choice in the dropdown.  For simplicity, only two
     *  queries are needed to achieve this.  The first sets the display value to true
     *  for all of the selections made, and false for everything not selected.
     */
    public function saveChoices(Request $req)
    {
        $req->validate([
            'currencies' => 'required'
        ]);

        // These will be displayed.
        Currency::whereIn('id', array_values($req->currencies))->update(['display' => true]);
        // These will not be displayed.
        Currency::whereNotIn('id', array_values($req->currencies))->update(['display' => false]);
        // Now get  the rates for the selected currencies.
        $this->refreshRates();

        return redirect('/');
    }

    public function viewHistory(Request $req)
    {
        // Get the filter criteria if it exists.
        $sourceCurrencyId = $req->sourceCurrencyId;
        $targetCurrencyId = $req->targetCurrencyId;

        // Returns a collection that will have array functions applied to it.
        // Only one query needed.
        $tempCollection = Conversion::with('sourceCurrency')->with('targetCurrency')->orderByDesc('time_stamp')->get();

        if ($sourceCurrencyId != null) {
            // Get only conversions for the given source currency.
            $tempCollection = $tempCollection->where('source_currency_id', $sourceCurrencyId);
        }
        if ($targetCurrencyId != null) {
            //  Get only conversions for the given target currency.
            $tempCollection = $tempCollection->where('source_currency_id', $targetCurrencyId);
        }

        $conversions = $tempCollection;

        $conv = $this->getConverter();

        return view('history')->with(
            [
                'ratesTimeStamp' => $conv->getRatesTimeStamp(),
                'conversions' => $conversions,
                'currency_list' => $conv->getAllCurrencies()->sortBy('code')->toArray(),
                'source' => $sourceCurrencyId,
                'target' => $targetCurrencyId
            ]);
    }

    /*
     *  Make a re-calculation and save the results.
     */
    public function updateHistory(Request $req)
    {
        // Must first validate the user input.
        $req->validate([
            'rate' => 'required|numeric|min:0|max:100000000000'
        ]);

        $id = $req->id;
        $rate = $req->rate;
        $amount = $req->amount;
        $conv = $this->getConverter();
        // Create the converted value based on the rate the user input.
        $converted = $conv->convert($rate, (float)$amount);

        Conversion::where('id', $id)->update(['rate' => $rate, 'converted_amount' => (float)str_replace(',', '', $converted)]);

        return redirect('/history');
    }

    /*
     *  Deletes a conversion from the history.
     */
    public function deleteHistory(Request $req)
    {
        $id = $req->id;
        Conversion::where('id', $id)->delete();

        return redirect('/history');
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
