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
            $table->integer('karyawanid');
            $table->integer('tanggal');
            $table->string('shift');
            $table->string('shift_new')->nullable();
            $table->integer('tanda')->nullable();
            $table->string('periode')->nullable();
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
