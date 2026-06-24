<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
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
                "name" => "Admin",
                "email" => "Admin@gmail.com",
                "password" => bcrypt("123456"),
                "level" => "Admin",
                "balance" => "0",
            ],
            [
                "name" => "Fisiphoria",
                "email" => "Fishiporia@gmail.com",
                "password" => bcrypt("Likinpark123"),
                "level" => "Organizer",
                "balance" => "0",
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
