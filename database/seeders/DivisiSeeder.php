<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;

class DivisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
    	for($i = 1; $i <= 15; $i++){
    		DB::table('divisi')->insert([
    			'kode_divisi' => $faker->languageCode,
    			'nama_divisi' => $faker->jobTitle,
    		]);
    	}
    }
}
