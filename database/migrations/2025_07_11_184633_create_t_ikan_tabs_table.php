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
        Schema::create('t_ikan_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('m_nelayan_tabs_id');
            $table->unsignedBigInteger('m_ikan_tabs_id');
            $table->tinyInteger('type')->default(0)->comment('0 = IN, 1 = OUT');
            $table->integer('count')->comment('Jumlah Ikan');
            $table->integer('weight')->comment('Berat Ikan (Kg)');
            $table->string('description')->nullable()->comment('Keterangan');
            $table->timestamps();
            $table->foreign('m_ikan_tabs_id')->references('id')->on('m_ikan_tabs')->cascadeOnDelete();
            $table->foreign('m_nelayan_tabs_id')->references('id')->on('m_nelayan_tabs')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_ikan_tabs');
    }
};
