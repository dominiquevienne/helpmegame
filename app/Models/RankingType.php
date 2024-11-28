<?php

namespace App\Models;

use Database\Factories\RankingTypeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property int $bgg_id
 * @property string $slug
 * @property string $name
 * @property int $parent_id
 * @property array<Game> $games
 */
class RankingType extends Model
{
    /** @use HasFactory<RankingTypeFactory> */
    use HasFactory;

    protected $table = 'ranking_type';


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bgg_id',
        'slug',
        'name',
        'parent_id',
    ];

    /**
     * Games relationship
     */
    public function games(): BelongsToMany {
        return $this->belongsToMany(Game::class)
            ->withPivot(['ranking', ]);
    }
}
