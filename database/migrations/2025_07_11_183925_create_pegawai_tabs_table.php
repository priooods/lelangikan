<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawai_tabs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('m_user_role_tabs_id')->comment('Role');
            $table->string('name')->comment('Nama Pegawai');
            $table->string('email')->unique()->comment('Email Pegawai');
            $table->string('alamat')->nullable()->comment('Alamat Pegawai');
            $table->tinyInteger('gender')->nullable()->default(0)->comment('Gender Pegawai, 0 = P, 1 = L');
            $table->string('avatar')->nullable()->comment('Photo profile');
            $table->string('password');
            $table->timestamps();
            $table->foreign('m_user_role_tabs_id')->references('id')->on('m_user_role_tabs')->cascadeOnDelete();
        });

        DB::table('pegawai_tabs')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('12345678'),
            'm_user_role_tabs_id' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai_tabs');
    }
};
