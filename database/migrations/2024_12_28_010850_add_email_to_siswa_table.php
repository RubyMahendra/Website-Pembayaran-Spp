<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmailToSiswaTable extends Migration
{
    /**
     * Menambahkan kolom email ke tabel siswa.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Menambahkan kolom email dengan tipe string dan unique
            $table->string('email')->unique()->nullable()->after('nama_siswa');
        });
    }

    /**
     * Rollback perubahan pada tabel siswa.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Menghapus kolom email jika rollback
            $table->dropColumn('email');
        });
    }
}
