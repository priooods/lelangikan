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
        Schema::create('t_penjualan_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_ikan_tabs_id')->comment('ID dari table transaksi ikan');
            $table->unsignedInteger('m_status_tabs_id')->default(1)->comment('ID dari table status');
            $table->string('price')->comment('Harga Ikan / (Kg)');
            $table->string('description')->comment('Detail / Informasi Rekening');
            $table->timestamps();
            $table->foreign('t_ikan_tabs_id')->references('id')->on('t_ikan_tabs')->cascadeOnDelete();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_tabs');
    }
};
