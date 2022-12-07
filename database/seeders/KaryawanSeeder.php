<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use DB;

class KaryawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
    	for($i = 1; $i <= 50; $i++){
    		DB::table('karyawan')->insert([
    			'nama_karyawan' => $faker->name,
    			'level_karyawan' => $faker->numberBetween(1,1),
    			'divisi_karyawan' => $faker->numberBetween(1,1),
    		]);
    	}
    }
}
