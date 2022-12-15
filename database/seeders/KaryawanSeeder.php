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
    	for($i = 1; $i <= 4; $i++){
    		DB::table('karyawan')->insert([
    			'nama_karyawan' => $faker->name,
    			'level_karyawan' => '5',
    			'divisi_karyawan' => '2',
    		]);
    	}
    }
}
