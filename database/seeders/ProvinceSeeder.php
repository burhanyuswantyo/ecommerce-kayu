<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/csv/provinces.csv');
        $data = array_map('str_getcsv', file($csvFile));

        $header = array_shift($data);

        foreach ($data as $row) {
            $province = array_combine($header, $row);

            Province::create([
                'name' => $province['name'],
                'latitude' => $province['latitude'] ?? null,
                'longitude' => $province['longitude'] ?? null,
            ]);
        }
    }
}
