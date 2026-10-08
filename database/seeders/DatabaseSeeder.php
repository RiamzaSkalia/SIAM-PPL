<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name'     => 'Administrator SIAM',
                'email'    => 'admin@siam.ac.id',
                'password' => Hash::make('admin'), // Password : admin
                'role'     => 'admin',
            ]
        );
    }
}