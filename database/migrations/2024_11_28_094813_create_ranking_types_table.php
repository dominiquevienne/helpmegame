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
        Schema::create('ranking_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bgg_id')->index();
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->unsignedBigInteger('parent_id');
            $table->timestamps();
        });
        Schema::table('ranking_types', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('ranking_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_types');
    }
};
