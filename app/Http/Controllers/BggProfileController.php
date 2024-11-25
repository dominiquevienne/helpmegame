<?php

namespace App\Http\Controllers;

use App\Models\BggProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BggProfileController extends Controller
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
        $name = 'bloulapinou';
        $url = config('app.bgg_root_url').'user';
        //echo $url; die();
        $response = Http::get($url, [
            'name' => $name,
        ]);
        $xmlContent = simplexml_load_string($response->body());

        $bggProfile = new BggProfile();
        $bggProfile->user_id = 1;
        $bggProfile->name = $name;
        $bggProfile->bgg_id = (int) $xmlContent->attributes()->id;
        $bggProfile->firstname = (string) $xmlContent->firstname->attributes();
        $bggProfile->lastname = (string) $xmlContent->lastname->attributes();
        $bggProfile->avatar_link = empty((string) $xmlContent->avatarlink->attributes()) ? null : (string) $xmlContent->avatarlink->attributes();
        if ($bggProfile->avatar_link === 'N/A') {
            $bggProfile->avatar_link = null;
        }
        $bggProfile->battlenet_account = empty((string) $xmlContent->battlenetaccount->attributes()) ? null : (string) $xmlContent->battlenetaccount->attributes();
        $bggProfile->country = (string) $xmlContent->country->attributes();
        $bggProfile->last_login = (string) $xmlContent->lastlogin->attributes();
        $bggProfile->psn_account = empty($xmlContent->psnaccount) ? null : (string) $xmlContent->psnaccount;
        $bggProfile->state_or_province = empty((string) $xmlContent->stateorprovince->attributes()) ? null : (string) $xmlContent->stateorprovince->attributes();
        $bggProfile->steam_account = empty((string) $xmlContent->steamaccount->attributes()) ? null : (string) $xmlContent->steamaccount->attributes();
        $bggProfile->web_address = empty($xmlContent->webaddress) ? null : (string) $xmlContent->webaddress;
        $bggProfile->trade_rating = (int) $xmlContent->traderating->attributes();
        $bggProfile->wii_account = empty((string) $xmlContent->wiiaccount->attributes()) ? null : (string) $xmlContent->wiiaccount->attributes();
        $bggProfile->xbox_account = empty($xmlContent->xbox_account->attributes()) ? null : (string) $xmlContent->xbox_account->attributes();
        $bggProfile->year_registered = (int) $xmlContent->yearregistered->attributes();
//        dump($bggProfile);die();
        $bggProfile->save();

        dump($bggProfile);
    }

    /**
     * Display the specified resource.
     */
    public function show(BggProfile $bggProfile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BggProfile $bggProfile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BggProfile $bggProfile)
    {
        //
    }
}
