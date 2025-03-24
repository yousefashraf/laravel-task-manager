<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Enums\RoleName;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('manager'),
        ]);

        $manager->assignRole(RoleName::MANAGER);

        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => Hash::make('user'),
        ]);

        $user->assignRole(RoleName::USER);
    }
}
