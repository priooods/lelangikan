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
        Schema::create('t_lelang_detail_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('users_id');
            $table->unsignedBigInteger('t_lelang_tabs_id');
            $table->unsignedInteger('m_status_tabs_id')->default(1)->comment('ID dari table status');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('t_lelang_tabs_id')->references('id')->on('t_lelang_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_lelang_detail_tabs');
    }
};
