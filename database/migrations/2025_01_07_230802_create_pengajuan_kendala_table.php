<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengajuanKendalaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengajuan_kendala', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email_pengguna');
            $table->text('kendala');
            $table->text('deskripsi');
            $table->enum('status', ['Tahap Pengecekan', 'Selesai'])->default('Tahap Pengecekan'); // Kolom status dengan nilai enum
            $table->timestamps(); // Menambahkan kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengajuan_kendala');
    }
}
