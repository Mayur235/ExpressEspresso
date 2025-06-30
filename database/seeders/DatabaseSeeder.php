<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RoleSeeder::class,
            BankSeeder::class,
            CategorySeeder::class,
            StatusSeeder::class,
            ProductSeeder::class,
            NoteSeeder::class,
            PaymentSeeder::class,
            UserSeeder::class,
        ]);
    }
}
