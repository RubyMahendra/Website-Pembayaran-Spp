<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class MakeBuktiPembayaranNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    DB::statement('ALTER TABLE pembayaran MODIFY bukti_pembayaran VARCHAR(255) NULL');
}

public function down()
{
    DB::statement('ALTER TABLE pembayaran MODIFY bukti_pembayaran VARCHAR(255) NOT NULL');
}


}
