<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiHariansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_harian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pegawai');
            $table->date('tanggal');
            $table->enum('ket', ['hadir', 'cuti', 'alpha']);
            $table->time('jam_dtg');
            $table->time('jam_plg');
            $table->timestamps();

            $table->index('id_pegawai');
            $table->foreign('id_pegawai')->references('id')->on('pegawai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi_harian');
    }
}