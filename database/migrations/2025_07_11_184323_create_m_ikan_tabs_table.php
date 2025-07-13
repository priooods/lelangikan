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
        Schema::create('m_ikan_tabs', function (Blueprint $table) {
            $table->id();
            $table->string('fish_name')->comment('Nama Ikan');
            $table->string('fish_picture')->comment('Foto Ikan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_ikan_tabs');
    }
};
