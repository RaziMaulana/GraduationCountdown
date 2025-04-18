<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         // Hanya membuat 1 user admin
         $admin = User::create([
            'name' => 'Administrator',
            'nisn' => '0983625134142', // NISN admin
            'password' => Hash::make('admin123'), // Password di-hash
            'password_plain' => 'admin123', // Password plaintext (hanya untuk development)
            'rata_rata' => null,
            'jurusan' => null,
            'status' => null, // Status khusus untuk admin
            'foto_diri' => null,
        ]);
    }
}
