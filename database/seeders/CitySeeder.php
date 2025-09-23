<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/csv/cities.csv');
        $data = array_map('str_getcsv', file($csvFile));

        $header = array_shift($data);

        foreach ($data as $row) {
            $city = array_combine($header, $row);

            City::create([
                'type' => $city['type'] ?? null,
                'name' => $city['name'] ?? null,
                'province_id' => $city['province_id'] ?? null,
                'latitude' => $city['latitude'] ?? null,
                'longitude' => $city['longitude'] ?? null,
            ]);
        }
    }
}
