<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $id = $request->get('id');
        $url = config('app.bgg_root_url').'thing';
        //echo $url; die();
        $response = Http::get($url, [
            'id' => $id,
        ]);
        $xmlContent = simplexml_load_string($response->body());

        Game::where('bgg_thing_id', $id)->delete();
        $bggGame = new Game();
        $bggGame->bgg_thing_id = $id;
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
         * @todo Implement game mechanics
         */
        /**
         * @todo Implement game family
         */
        /**
         * @todo Implement game expansions
         */
        /**
         * @todo Implement game accessory
         */
        /**
         * @todo Implement game designers
         */
        /**
         * @todo Implement game artists
         */
        /**
         * @todo Implement game publishers
         */
        $bggGame->delete();
        $bggGame->save();

        dump($bggGame);

    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
