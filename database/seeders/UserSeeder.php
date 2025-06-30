<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                "fullname" => "Mayur Gupta",
                "username" => "mayur23",
                "email" => "mayur@gmail.com",
                "phone" => "07486864801",
                "gender" => "M",
                "address" => "Chhapra Road",
                "role_id" => 1,
            ],
            [
                "fullname" => "Tulsi Chauhan",
                "username" => "tls01",
                "email" => "tls01@gmail.com",
                "phone" => "082918391823",
                "gender" => "F",
                "address" => "Station road, Navsari",
                "role_id" => 2,
            ],
            [
                "fullname" => "Sanjana Ladva",
                "username" => "sanju03",
                "email" => "sanju03@gmail.com",
                "phone" => "019292823382",
                "gender" => "F",
                "address" => "Vijalpur, Navsari",
                "role_id" => 2,
            ],
            [
                "fullname" => "Vaibhav Patil",
                "username" => "vpatil04",
                "email" => "vpatil04@gmail.com",
                "phone" => "019292823382",
                "gender" => "M",
                "address" => "Vijalpur, Navsari",
                "role_id" => 2,
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']], // Unique identifier
                [
                    ...$user,
                    "password" => Hash::make("1234"),
                    "image" => env("IMAGE_PROFILE", "default.jpg"),
                    "coupon" => 0,
                    "point" => 0,
                    "remember_token" => Str::random(30),
                ]
            );
        }

        // Generate 5 fake users using factories
        User::factory(5)->create();
    }
}
