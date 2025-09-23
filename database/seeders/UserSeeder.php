<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@udkayunugroho.com',
            'role' => 'super_admin',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@udkayunugroho.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Manager',
            'username' => 'manager',
            'email' => 'manager@udkayunugroho.com',
            'role' => 'manager',
        ]);
    }
}
