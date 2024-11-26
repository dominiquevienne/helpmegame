<?php

namespace App\Models;

use Database\Factories\TaxonomyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property int $bgg_id
 * @property string $name
 * @property int $taxonomy_type_id
 * @property array<Game> $games
 * @property TaxonomyType $taxonomyType
*/
class Taxonomy extends Model
{
    /** @use HasFactory<TaxonomyFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bgg_id',
        'name',
        'taxonomy_type_id',
    ];

    /**
     * Games relationship
     */
    public function games(): BelongsToMany {
        return $this->belongsToMany(Game::class);
    }

    /**
     * Taxonomies relationship
     */
    public function taxonomyType(): BelongsTo {
        return $this->belongsTo(TaxonomyType::class);
    }
}
