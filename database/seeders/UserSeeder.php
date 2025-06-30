<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "fullname" => "Mayur Gupta",
            "username" => "mayur23",
            "email" => "mayur@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "07486864801",
            "gender" => "M",
            "address" => "Chhapra Road",
            "role_id" => 1,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::create([
            "fullname" => "Tulsi Chauhan",
            "username" => "tls01",
            "email" => "tls01@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "082918391823",
            "gender" => "F",
            "address" => "Station road, Navsari",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::create([
            "fullname" => "Sanjana Ladva",
            "username" => "sanju03",
            "email" => "sanju03@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "019292823382",
            "gender" => "M",
            "address" => "Vijalpur, Navsari",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        
        User::create([
            "fullname" => "Vaibhav Patil",
            "username" => "vpatil04",
            "email" => "vpatil04@gmail.com",
            "password" => Hash::make("1234"),
            "image" => env("IMAGE_PROFILE"),
            "phone" => "019292823382",
            "gender" => "M",
            "address" => "Vijalpur, Navsari",
            "role_id" => 2,
            "coupon" => 0,
            "point" => 0,
            'remember_token' => Str::random(30),
        ]);

        User::factory(5)->create();
    }
}
