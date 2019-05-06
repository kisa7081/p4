<?php

use Illuminate\Database\Seeder;
use App\Currency;
class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $currencyData =
        [
            ["BGN","Bulgarian Lev", false],
            ["NZD","New Zealand Dollar", false],
            ["ILS","New Israeli Sheqel", false],
            ["RUB","Russian Ruble", true],
            ["CAD","Canadian Dollar", false],
            ["USD","US Dollar", true],
            ["PHP","Philippine Peso", false],
            ["CHF","Swiss Franc", false],
            ["ZAR","Rand", false],
            ["AUD","Australian Dollar", false],
            ["JPY","Yen", false],
            ["TRY","Turkish Lira", false],
            ["HKD","Hong Kong Dollar", false],
            ["MYR","Malaysian Ringgit", false],
            ["THB","Baht", false],
            ["HRK","Kuna", false],
            ["NOK","Norwegian Krone", false],
            ["IDR","Rupiah", false],
            ["DKK","Danish Krone", false],
            ["CZK","Czech Koruna", false],
            ["HUF","Forint", false],
            ["GBP","Pound Sterling", true],
            ["MXN","Mexican Peso", true],
            ["KRW","Won", false],
            ["ISK","Iceland Krona", false],
            ["SGD","Singapore Dollar", false],
            ["BRL","Brazilian Real", false],
            ["PLN","Zloty", false],
            ["INR","Indian Rupee", false],
            ["RON","Romanian Leu", false],
            ["CNY","Yuan Renminbi", false],
            ["SEK","Swedish Krona", false]
        ];

        $count = count($currencyData);

        foreach ($currencyData as $data) {
            $currency = new Currency();
            $currency->created_at = Carbon\Carbon::now()->subDays($count)->toDateTimeString();
            $currency->updated_at = Carbon\Carbon::now()->subDays($count)->toDateTimeString();
            $currency->code = $data[0];
            $currency->name = $data[1];
            $currency->display = $data[2];

            $currency->save();

            $count--;
        }
    }
}
