<?php

use App\Models\TaxonomyType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taxonomy_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->timestamps();
        });

        $typeNames = [
            'boardgamecategory',
            'boardgamemechanic',
            'boardgamefamily',
            'boardgamedesigner',
            'boardgameartist',
            'boardgamepublisher',
        ];

        foreach ($typeNames as $typeName) {
            $taxonomyType = new TaxonomyType();
            $taxonomyType->name = $typeName;
            $taxonomyType->slug = Str::slug($typeName);
            $taxonomyType->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxonomy_types');
    }
};
