<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('t_lelang_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_ikan_tabs_id')->comment('ID dari table transaksi ikan');
            $table->date('start_date_lelang')->comment('tgl mulai lelang');
            $table->date('end_date_lelang')->comment('tgl selesai lelang');
            $table->string('description')->nullable();
            $table->unsignedInteger('m_status_tabs_id')->default(1)->comment('ID dari table status');
            $table->timestamps();
            $table->foreign('t_ikan_tabs_id')->references('id')->on('t_ikan_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lelang_tabs');
    }
};
