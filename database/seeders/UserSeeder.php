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
        $users = [
            [
                'name' => 'Administrator',
                'phone' => '082237188923',
                'photo' => 'assets/uploads/users/default.png',
                'email' => 'admin@gmail.com',
                'password' => bcrypt(12345678),
                'role' => 'admin',
            ],
            [
                'name' => 'Operator',
                'phone' => '082237188923',
                'photo' => 'assets/uploads/users/default.png',
                'email' => 'operator@gmail.com',
                'password' => bcrypt(12345678),
                'role' => 'operator',
            ],
        ];

        foreach($users as $user) {
            User::create($user);
        }
    }
}
