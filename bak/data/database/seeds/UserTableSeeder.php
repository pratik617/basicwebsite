<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => "Ravirajsinh Zala",
            'country_code' => "+91",
            'contact_no' => "8347012816",
            'profile' => "",
            'email' => "user@rideapp.com",
            'email_verified_at' => "2018-11-13 00:00:00",
            'password' => bcrypt('12345678'),
            'gmail_id' => "",
            'facebook_id' => "",
            'linkedin_id' => "",
            'profile' => "",
            'invite_code' => "",
            'invited_code' => "",
            'device_type' => "Android",
            'is_login' => "no",
            'created_by' => "1",
            'updated_by' => "1",
        ]);
    }
}
