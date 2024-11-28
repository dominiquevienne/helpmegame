<?php

namespace App\Models;

use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property-read int $id
 * @property int $bgg_thing_id
 * @property string $thumbnail
 * @property string $image
 * @property string $name
 * @property string $description
 * @property int $year_published
 * @property int $min_players
 * @property int $max_players
 * @property int $playing_time
 * @property int $min_playing_time
 * @property int $max_playing_time
 * @property int $min_age
 * @property float $rating
 * @property array<Taxonomy> $taxonomies
 * @property array<User> $users
 * @property array<RankingType> $rankingTypes
 */
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'bgg_thing_id',
        'thumbnail',
        'image',
        'name',
        'description',
        'year_published',
        'min_players',
        'max_players',
        'playing_time',
        'min_playing_time',
        'max_playing_time',
        'min_age',
        'rating',
    ];

    /**
     * Taxonomies relationship
     */
    public function taxonomies(): BelongsToMany {
        return $this->belongsToMany(Taxonomy::class);
    }

    /**
     * Users relationship
     */
    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class);
    }

    /**
     * Ranking relationship
     */
    public function rankingTypes(): BelongsToMany {
        return $this->belongsToMany(RankingType::class)
            ->withPivot(['ranking', ]);
    }
}
