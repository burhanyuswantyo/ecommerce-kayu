<?php

return [
    'store_name' => env('STORE_NAME', 'UD Kayu Nugroho'),
    'store_address' => env('STORE_ADDRESS', 'Jl. Raya No. 123, Yogyakarta'),
    'store_phone' => env('STORE_PHONE', '081234567890'),
    'store_email' => env('STORE_EMAIL', 'udkayunugroho@gmail.com'),
    'store_logo' => env('STORE_LOGO', 'logo.png'),
    'store_coordinates' => [
        'latitude' => env('STORE_LATITUDE', -7.7956),
        'longitude' => env('STORE_LONGITUDE', 110.3695),
    ],
    'store_opening_hours' => [
        'Senin' => '08:00 - 17:00',
        'Selasa' => '08:00 - 17:00',
        'Rabu' => '08:00 - 17:00',
        'Kamis' => '08:00 - 17:00',
        'Jumat' => '08:00 - 17:00',
        'Sabtu' => '08:00 - 12:00',
        'Minggu' => '08:00 - 12:00',
    ],
    'store_shipping_costs' => [
        'self_pickup' => 0,
        'delivery' => [
            'base_rate' => 5000, // Base shipping cost
            'per_kg' => 2000, // Cost per kg
            'per_km' => 1000, // Cost per km
        ],
    ],
    'store_payment_accounts' => [
        [
            'name' => 'Bank BCA',
            'account_number' => '1234567890',
            'account_name' => 'UD Kayu Nugroho',
        ],
        [
            'name' => 'Bank Mandiri',
            'account_number' => '0987654321',
            'account_name' => 'UD Kayu Nugroho',
        ],
        [
            'name' => 'OVO',
            'account_number' => '081234567890',
            'account_name' => 'UD Kayu Nugroho',
        ],
        [
            'name' => 'DANA',
            'account_number' => '081234567890',
            'account_name' => 'UD Kayu Nugroho',
        ],
    ],
];
