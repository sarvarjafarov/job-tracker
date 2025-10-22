<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin user
        User::create([
            'name' => 'Sarvar Jafarov',
            'email' => 'sarvar@example.com',
            'username' => 'sarvar',
            'password' => Hash::make('Nsusife123@'),
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        // Create some sample companies
        \App\Models\Company::create([
            'name' => 'Google',
            'website' => 'https://google.com',
            'email' => 'careers@google.com',
            'industry' => 'Technology',
            'size' => '10000+',
            'description' => 'Search engine and technology company',
        ]);

        \App\Models\Company::create([
            'name' => 'Microsoft',
            'website' => 'https://microsoft.com',
            'email' => 'careers@microsoft.com',
            'industry' => 'Technology',
            'size' => '10000+',
            'description' => 'Software and cloud computing company',
        ]);

        \App\Models\Company::create([
            'name' => 'Apple',
            'website' => 'https://apple.com',
            'email' => 'jobs@apple.com',
            'industry' => 'Technology',
            'size' => '10000+',
            'description' => 'Consumer electronics and software company',
        ]);
    }
}
