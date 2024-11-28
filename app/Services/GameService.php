<?php

namespace App\Services;

use App\Jobs\ProcessGameFetch;
use App\Models\Game;
use App\Models\RankingType;
use App\Models\Taxonomy;
use App\Models\TaxonomyType;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameService
{
    public const GETTER_PAUSE = 5;

    /**
     * Will delete the game in DB if exists and check for reference from source
     *
     * @param $bggId
     * @return Game|null
     */
    static public function getGame($bggId): ?Game {
        $url = config('app.bgg_root_url').'thing';
        $response = Http::get($url, [
            'id' => $bggId,
            'stats' => 1,
        ]);
        $xmlContent = simplexml_load_string($response->body());

        $taxonomyIds = [];
        if (!$xmlContent->item->link) {
            /**
             * @todo Improve logic. No link means no taxonomy for the game
             */
            if ($xmlContent->message) {
                Log::warning('BGG: Rate limit exceeded. Pausing getter for '.self::GETTER_PAUSE.'. Thing ID: '.$bggId);
                sleep(self::GETTER_PAUSE);
                dispatch(new ProcessGameFetch($bggId));
                return null;
            }
            Log::warning('BGG: Thing ID ' . $bggId . ' does not exist');
            return null;
        }

        foreach ($xmlContent->item->link as $link) {
            $taxonomyTypeName = (string) $link->attributes()['type'];
            $taxonomyType = TaxonomyType::where('name', $taxonomyTypeName)->first();
            if (!$taxonomyType) {
                continue;
            }
            $taxonomyName = (string) $link->attributes()['value'];
            $slug = (string) $link->attributes()['type'];
            $taxonomyType = TaxonomyType::where('slug', $slug)->first();
            $taxonomy = Taxonomy::where('name', $taxonomyName)->where('taxonomy_type_id', $taxonomyType->id)->first();
            if (!$taxonomy) {
                $taxonomy = new Taxonomy();
                $taxonomy->bgg_id = (int) $link->attributes()['id'];
                $taxonomy->name = $taxonomyName;
                $taxonomy->taxonomy_type_id = $taxonomyType->id;
                $taxonomy->save();
            }
            $taxonomyIds[] = $taxonomy->id;
        }
        Game::where('bgg_thing_id', $bggId)->delete();

        $rating = (float) $xmlContent->item->statistics->ratings->average->attributes() ?? null;
        $rankingTypeIds = [];
        foreach ($xmlContent->item->statistics->ratings->ranks->rank as $rank) {
            $bggRankId = (int) $rank->attributes()['id'];
            $bggType = (string) $rank->attributes()['type'];
            $bggName = (string) $rank->attributes()['name'];
            $bggFriendlyName = (string) $rank->attributes()['friendlyname'];
            $bggRanking = is_numeric((string) $rank->attributes()['value']) ? (int) $rank->attributes()['value'] : null;

            $rankingType = RankingType::where('bgg_id', $bggRankId)->first();
            if ($rankingType instanceof RankingType) {
                $rankingTypeIds[$rankingType->id] = ['ranking' => $bggRanking];
                continue;
            }
            $rankingType = new RankingType();
            $rankingType->bgg_id = $bggRankId;
            $rankingType->slug = $bggName;
            $rankingType->name = $bggFriendlyName;
            if ($bggType === 'subtype') {
                $rankingType->parent_id = null;
            }
            if ($bggType === 'family') {
                $rankingType->parent_id = count($rankingTypeIds) ? $rankingTypeIds[0] : null;
            }
            $rankingType->save();
            $rankingTypeIds[$rankingType->id] = ['ranking' => $bggRanking];

        }

        $bggGame = new Game();
        $bggGame->bgg_thing_id = $bggId;
        $bggGame->thumbnail = (string) $xmlContent->item->thumbnail;
        $bggGame->image = (string) $xmlContent->item->image;
        /**
         * @todo Improve name retrieving in order to get alternate names instead of only first provided
         */
        $bggGame->name = (string) $xmlContent->item->name->attributes()->value;
        $bggGame->description = (string) $xmlContent->item->description;
        $bggGame->year_published = (int) $xmlContent->item->yearpublished->attributes();
        $bggGame->min_players = (int) $xmlContent->item->minplayers->attributes();
        $bggGame->max_players = (int) $xmlContent->item->maxplayers->attributes();
        /**
         * @todo Implement suggested number of players
         */
        $bggGame->playing_time = (int) $xmlContent->item->playingtime->attributes();
        $bggGame->min_playing_time = (int) $xmlContent->item->minplaytime->attributes();
        $bggGame->max_playing_time = (int) $xmlContent->item->maxplaytime->attributes();
        $bggGame->min_age = (int) $xmlContent->item->minage->attributes();
        $bggGame->rating = $rating;
        /**
         * @todo Implement suggested minimum age
         */
        /**
         * @todo Implement language dependence
         */
        /**
         * @todo Implement game expansions
         */
        /**
         * @todo Implement game accessory
         */
        $bggGame->save();
        $bggGame->taxonomies()->attach($taxonomyIds);
        $bggGame->rankingTypes()->attach($rankingTypeIds);

        return $bggGame;
    }

    /**
     * Will check for game in datasource and create it if not available
     *
     * @param int $bggId
     * @param User $user
     * @return Game
     */
    static public function linkGame(int $bggId, User $user): Game {
        $game = Game::where('bgg_thing_id', $bggId)->first();
        if (!$game) {
            $game = self::getGame($bggId);
        }
        $user->games()->attach($game);

        return $game;
    }

}
