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
        Schema::create('m_nelayan_tabs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nama Nelayan');
            $table->unsignedInteger('m_status_tabs_id');
            $table->timestamps();
            $table->foreign('m_status_tabs_id')->references('id')->on('m_status_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_nelayan_tabs');
    }
};
