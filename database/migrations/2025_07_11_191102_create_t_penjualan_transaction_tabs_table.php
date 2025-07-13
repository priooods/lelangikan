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
        Schema::create('t_penjualan_transaction_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('t_penjualan_tabs_id')->comment('ID dari table penjualan');
            $table->unsignedBigInteger('users_id')->comment('ID dari table user/pelanggan');
            $table->unsignedInteger('m_status_tabs_id')->default(1)->comment('ID dari table status');
            $table->integer('count')->comment('Jumlah ikan yang di beli /(Kg)');
            $table->string('notes')->nullable()->comment('Catatan pembelian');
            $table->string('amount_paid')->nullable()->comment('Jumlah dibayar');
            $table->string('amount_change')->nullable()->comment('Jumlah kembalian');
            $table->string('payment_path')->nullable()->comment('Bukti Bayar');
            $table->timestamps();
            $table->foreign('t_penjualan_tabs_id')->references('id')->on('t_penjualan_tabs')->cascadeOnDelete();
            $table->foreign('users_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_penjualan_transaction_tabs');
    }
};
