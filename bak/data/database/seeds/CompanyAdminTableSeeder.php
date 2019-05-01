<?php

use Illuminate\Database\Seeder;

class CompanyAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_admins')->insert([
        	'company_id' => "1",
            'firstname' => "company",
            'lastname' => "admin",
            'country_code' => "91",
            'mobile_no' => "8347012816",
            'profile' => "",
            'email' => "companyadmin@rideapp.com",
            'email_verified_at' => "2018-11-13 00:00:00",
            'password' => bcrypt('12345678'),
            'created_by' => "1",
        ]);
    }
}
