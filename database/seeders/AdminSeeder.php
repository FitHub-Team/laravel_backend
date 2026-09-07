<?php

namespace Database\Seeders;

use App\Models\Admin;
//use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash as FacadesHash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@superfit.com',
            'password' => FacadesHash::make('password123'),
        ]);
    }
}
