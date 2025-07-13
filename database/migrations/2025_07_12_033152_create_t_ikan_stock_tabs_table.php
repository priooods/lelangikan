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
        Schema::create('t_ikan_stock_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_ikan_tabs_id')->comment('ID dari table Ikan');
            $table->integer('stock')->default(0)->comment('Stock Ikan');
            $table->timestamps();
            $table->foreign('m_ikan_tabs_id')->references('id')->on('m_ikan_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_ikan_stock_tabs');
    }
};
