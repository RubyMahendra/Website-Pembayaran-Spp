<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReminderColumnsToPembayaranTable extends Migration
{
    /**
     * Jalankan migrasi untuk menambahkan kolom pengingat jatuh tempo.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->boolean('reminder_sent_5_months')->default(false);
            $table->boolean('reminder_sent_start_6_months')->default(false);
            $table->boolean('reminder_sent_middle_6_months')->default(false);
        });
    }

    /**
     * Membalikkan migrasi untuk menghapus kolom pengingat.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn('reminder_sent_5_months');
            $table->dropColumn('reminder_sent_start_6_months');
            $table->dropColumn('reminder_sent_middle_6_months');
        });
    }
}
