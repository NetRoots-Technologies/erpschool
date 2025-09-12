<?php

namespace Database\Seeders;

use App\Models\Admin\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $states = [
            [
                'name' => 'Punjab',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '04',
                'iso2' => 'PB',
                'type' => 'province',
                'latitude' => '31.1471305',
                'longitude' => '75.3412179',
            ],
            [
                'name' => 'Sindh',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '05',
                'iso2' => 'SD',
                'type' => 'province',
                'latitude' => '25.8943014',
                'longitude' => '68.5247149',
            ],
            [
                'name' => 'Khyber Pakhtunkhwa',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '03',
                'iso2' => 'KP',
                'type' => 'province',
                'latitude' => '34.9526205',
                'longitude' => '72.331113',
            ],
            [
                'name' => 'Balochistan',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '02',
                'iso2' => 'BA',
                'type' => 'province',
                'latitude' => '28.4907332',
                'longitude' => '65.0957792',
            ],
            [
                'name' => 'Islamabad Capital Territory',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '08',
                'iso2' => 'IS',
                'type' => 'territory',
                'latitude' => '33.6844202',
                'longitude' => '73.0478848',
            ],
            [
                'name' => 'Gilgit-Baltistan',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '06',
                'iso2' => 'GB',
                'type' => 'territory',
                'latitude' => '35.8025667',
                'longitude' => '74.9833275',
            ],
            [
                'name' => 'Azad Jammu and Kashmir',
                'country_id' => '1',
                'country_code' => 'PK',
                'fips_code' => '01',
                'iso2' => 'JK',
                'type' => 'territory',
                'latitude' => '33.9259055',
                'longitude' => '73.7810136',
            ],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
