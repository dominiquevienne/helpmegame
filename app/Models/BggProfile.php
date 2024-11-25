<?php

namespace App\Models;

use Database\Factories\BggProfileFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property int $user_id
 * @property int $bgg_id
 * @property string $name
 * @property string $firstname
 * @property string $lastname
 * @property string|null $avatar_link
 * @property int $year_registered
 * @property string $last_login
 * @property string $state_or_province
 * @property string $country
 * @property string|null $web_address
 * @property string|null $xbox_account
 * @property string|null $wii_account
 * @property string|null $psn_account
 * @property string|null $battlenet_account
 * @property string|null $steam_account
 * @property int $trade_rating
 */
class BggProfile extends Model
{
    /** @use HasFactory<BggProfileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'bgg_id',
        'name',
        'firstname',
        'lastname',
        'avatar_link',
        'year_registered',
        'last_login',
        'state_or_province',
        'country',
        'web_address',
        'xbox_account',
        'wii_account',
        'psn_account',
        'battlenet_account',
        'steam_account',
        'trade_rating',
    ];
}
