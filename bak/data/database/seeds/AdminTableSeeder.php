<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            'firstname' => "super",
            'lastname' => "admin",
            'country_code' => "91",
            'mobile_no' => "8347012816",
            'profile' => "",
            'email' => "admin@rideapp.com",
            'email_verified_at' => "2018-11-13 00:00:00",
            'password' => bcrypt('12345678'),
        ]);
    }
}
