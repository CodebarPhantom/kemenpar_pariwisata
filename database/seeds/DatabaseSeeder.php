<?php

use Illuminate\Database\Seeder;
use App\Models\Ticket\PrimaryTests;
use App\Models\Ticket\SecondaryTests;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PrimaryTestsSeeder::class);
    }
}


class PrimaryTestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PrimaryTests::class, 5000)->create()->each(function ($primary) {
            $primary->secondary_tests()->save(factory(SecondaryTests::class)->make());
        });
    }
}

