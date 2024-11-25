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
        Schema::create('bgg_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('bgg_id');
            $table->string('name');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('avatar_link')->nullable();
            $table->unsignedSmallInteger('year_registered');
            $table->string('last_login');
            $table->string('state_or_province')->nullable();
            $table->string('country');
            $table->string('web_address')->nullable();
            $table->string('xbox_account')->nullable();
            $table->string('wii_account')->nullable();
            $table->string('psn_account')->nullable();
            $table->string('battlenet_account')->nullable();
            $table->string('steam_account')->nullable();
            $table->unsignedSmallInteger('trade_rating');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bgg_profiles');
    }
};
