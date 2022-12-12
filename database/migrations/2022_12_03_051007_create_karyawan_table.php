<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKaryawanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->string('nama_karyawan');
            $table->string('pendidikan');
            $table->string('level_karyawan');
            $table->string('divisi_karyawan');
            $table->string('jabatan');
            $table->text('alamat');
            $table->string('jk');
            $table->date('tanggallahir');
            $table->string('tmptlahir');
            $table->date('tglmasuk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('karyawan');
    }
}
