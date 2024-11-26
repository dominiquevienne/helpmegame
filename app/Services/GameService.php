<?php

namespace App\Services;

use App\Models\Game;
use App\Models\Taxonomy;
use App\Models\TaxonomyType;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameService
{
    /**
     * Will delete the game in DB if exists and check for reference from source
     *
     * @param $bggId
     * @return Game
     */
    static public function getGame($bggId): ?Game {
        $url = config('app.bgg_root_url').'thing';
        $response = Http::get($url, [
            'id' => $bggId,
        ]);
        $xmlContent = simplexml_load_string($response->body());

        $taxonomyIds = [];
        if (!$xmlContent->item->link) {
            /**
             * @todo Improve logic. No link means no taxonomy for the game
             */
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
        $bggGame->delete();
        $bggGame->save();
        $bggGame->taxonomies()->attach($taxonomyIds);

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
