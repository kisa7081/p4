<?php

use Illuminate\Database\Seeder;
use App\Conversion;
use App\Currency;

class ConversionsTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $conversions =
        [
            [45, 0.0153659739, 0.69, "2019-05-08 00:18:06", 4, 6],
            [5, 0.0402673362, 0.2, "2019-05-08 00:18:18", 23, 22],
            [50, 110.6213679034, 5531.07, "2019-05-08 00:18:30", 6, 11],
            [12.56, 0.7657130085, 9.62, "2019-05-08 00:18:50", 6, 22]
        ];

        $count = count($conversions);

        foreach ($conversions as $conv) {
            $conversion = new Conversion();
            $conversion->created_at = Carbon\Carbon::now()->subDays($count)->toDateTimeString();
            $conversion->updated_at = Carbon\Carbon::now()->subDays($count)->toDateTimeString();
            $conversion->source_amount = (float)$conv[0];
            $conversion->rate = (float)$conv[1];
            $conversion->converted_amount = (float)$conv[2];
            $conversion->time_stamp = $conv[3];
            $conversion->source_currency_id = Currency::where('id', $conv[4])->pluck('id')->first();
            $conversion->target_currency_id = Currency::where('id', $conv[5])->pluck('id')->first();
            $conversion->save();

            $count--;
        }
    }
}
