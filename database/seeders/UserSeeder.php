<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Core Admin
        User::create([
            'name' => 'TrekAdvisor Admin',
            'email' => 'admin@trekadvisor.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'approval_status' => 'approved',
        ]);

        // 2. Staff Account
        User::create([
            'name' => 'Nepal Support Team',
            'email' => 'staff@trekadvisor.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'approval_status' => 'approved',
        ]);

        // 3. Hotel Owners
        User::create([
            'name' => 'Everest Hospitality',
            'email' => 'owner1@trekadvisor.com',
            'password' => Hash::make('owner123'),
            'role' => 'hotel_owner',
            'approval_status' => 'approved',
        ]);

        User::create([
            'name' => 'Annapurna Lodges',
            'email' => 'owner2@trekadvisor.com',
            'password' => Hash::make('owner123'),
            'role' => 'hotel_owner',
            'approval_status' => 'approved',
        ]);

        // 4. Pool of 20+ Authentic Nepali Customer Names
        $names = [
            'Arjun Thapa', 'Sushma Gurung', 'Ramesh Baniya', 'Pratima Sherpa',
            'Bikash Shrestha', 'Nirmala Rai', 'Prakash Tamang', 'Sita Magar',
            'Sajan Gurung', 'Kiran Lama', 'Anjali Pandey', 'Dipak Bhattarai',
            'Manish Khatri', 'Sunita Dahal', 'Suraj Ghimire', 'Priyanka Karki',
            'Gopal Acharya', 'Saru Shrestha', 'Bibek Baral', 'Ashmita Thapa'
        ];

        foreach ($names as $name) {
            User::create([
                'name' => $name,
                'email' => str_replace(' ', '.', strtolower($name)) . '@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'approval_status' => 'approved',
            ]);
        }
    }
}
