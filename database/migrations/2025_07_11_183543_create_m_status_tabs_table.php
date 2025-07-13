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
        Schema::create('m_status_tabs', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('title')->comment('Nama Status');
        });

        DB::table('m_status_tabs')->insert(
            array(
                ['title' => 'Draft'], //1
                ['title' => 'Available'], //2
                ['title' => 'Not Available'], //3
                ['title' => 'Sending Payment'], //4
                ['title' => 'Waiting Payment'], //5
                ['title' => 'Paid Payment'], //6
                ['title' => 'Refund Payment'], //7
                ['title' => 'Active'], //8
                ['title' => 'Not Active'], //9
                ['title' => 'Terjual'], //9
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_status_tabs');
    }
};
