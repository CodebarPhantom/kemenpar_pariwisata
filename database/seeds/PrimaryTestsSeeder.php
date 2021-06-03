<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Ticket\PrimaryTests;
use App\Models\Ticket\SecondaryTests;

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
