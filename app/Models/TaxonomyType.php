<?php

namespace App\Models;

use Database\Factories\TaxonomyTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property array<Taxonomy> $taxonomies
 */
class TaxonomyType extends Model
{
    /** @use HasFactory<TaxonomyTypeFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Taxonomies relationship
     */
    public function taxonomies(): HasMany {
        return $this->hasMany(Taxonomy::class);
    }
}
