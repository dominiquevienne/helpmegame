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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bgg_thing_id')->unique();
            $table->string('thumbnail');
            $table->string('image');
            $table->string('name')->unique();
            $table->text('description');
            $table->unsignedSmallInteger('year_published')->index();
            $table->unsignedTinyInteger('min_players')->index();
            $table->unsignedTinyInteger('max_players')->index();
            $table->unsignedTinyInteger('playing_time')->index();
            $table->unsignedTinyInteger('min_playing_time')->index();
            $table->unsignedTinyInteger('max_playing_time')->index();
            $table->unsignedTinyInteger('min_age')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
