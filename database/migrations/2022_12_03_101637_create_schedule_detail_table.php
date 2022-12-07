<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_detail', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_id');
            $table->string('karyawanid');
            $table->integer('tanggal');
            $table->integer('shift');
            $table->integer('tanda');
            $table->string('periode');
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
        Schema::dropIfExists('schedule_detail');
    }
}
