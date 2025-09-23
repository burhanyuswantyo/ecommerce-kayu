<?php

namespace Database\Seeders;

use App\Models\Postal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = base_path('database/csv/zip_codes.csv');
        $data = array_map('str_getcsv', file($csvFile));

        $header = array_shift($data);

        foreach ($data as $row) {
            $postal = array_combine($header, $row);

            Postal::create([
                'name' => $postal['name'] ?? null,
                'village_id' => $postal['id'] ?? null,
            ]);
        }
    }
}
