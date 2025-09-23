<?php

namespace Database\Seeders;

use App\Models\Village;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VillageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/csv/villages.csv');
        $data = array_map('str_getcsv', file($csvFile));

        $header = array_shift($data);

        foreach ($data as $row) {
            $village = array_combine($header, $row);

            Village::create([
                'name' => $village['name'] ?? null,
                'postal_code' => $village['postal_code'] ?? null,
                'province_id' => $village['province_id'] ?? null,
                'city_id' => $village['city_id'] ?? null,
                'district_id' => $village['district_id'] ?? null,
                'latitude' => $village['latitude'] ?? null,
                'longitude' => $village['longitude'] ?? null,
            ]);
        }
    }
}
