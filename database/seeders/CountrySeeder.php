<?php

namespace Database\Seeders;

use App\Models\Admin\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Country::create([
            'name' => 'Pakistan',
            'iso3' => 'PAK',
            'numeric_code' => '586',
            'iso2' => 'PK',
            'phonecode' => '92',
            'capital' => 'Islamabad',
            'currency' => 'PKR',
            'currency_name' => 'Pakistani rupee',
            'currency_symbol' => '₨',
            'tld' => '.pk',
            'native' => 'Pakistan',
            'region' => 'Asia',
            'subregion' => 'Southern Asia',
            'timezones' => '[{"zoneName":"Asia/Karachi","gmtOffset":18000,"gmtOffsetName":"UTC+05:00","abbreviation":"PKT","tzName":"Pakistan Standard Time"}]',
            'latitude' => '30.00000000',
            'longitude' => '70.00000000',
            'emoji' => '🇵🇰',
            'emojiU' => 'U+1F1F5 U+1F1F0',
        ]);
    }
}
