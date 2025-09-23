<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/csv/districts.csv');
        $data = array_map('str_getcsv', file($csvFile));

        $header = array_shift($data);

        foreach ($data as $row) {
            $district = array_combine($header, $row);

            District::create([
                'name' => $district['name'] ?? null,
                'province_id' => $district['province_id'] ?? null,
                'city_id' => $district['city_id'] ?? null,
                'latitude' => $district['latitude'] ?? null,
                'longitude' => $district['longitude'] ?? null,
            ]);
        }
    }
}
