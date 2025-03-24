<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


        // Insert specified users manually
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.lk',
                'role' => 'admin'
            ],
            [
                'name' => 'Shaza',
                'email' => 'cb014090@students.apiit.lk',
                'role' => 'entrepreneur'
            ],
            [
                'name' => 'Leena Hamdhan',
                'email' => 'cb012970@students.apiit.lk',
                'role' => 'entrepreneur'
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->insert([
                'name' => $user['name'],
                'email' => $user['email'],
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => $user['role'], // Fix the typo by adding the missing quote
            ]);
        }
    }
}
