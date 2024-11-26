<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\BggProfile;
use App\Models\User;
use App\Services\GameService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update collection from BGG to helpmegame
     */
    public function updateCollection(Request $request) {
        $userId = $request->get('user_id');
        $user = User::find($userId);
        if (!$user) {
            dd('User not found');
        }
        $user->games()->detach();

        $bggProfile = $user->bggProfile;
        if (!$bggProfile) {
            dd('BGG profile not found');
        }

        $url = config('app.bgg_root_url').'collection';
        $response = Http::get($url, [
            'username' => $bggProfile->name,
        ]);
        $xmlContent = simplexml_load_string($response->body());

        foreach ($xmlContent->item as $item) {
            $isOwned = (int) $item->status->attributes()->own;
            $objectType = (string) $item->attributes()['objecttype'];
            $objectSubtype = (string) $item->attributes()['subtype'];
            $objectId = (int) $item->attributes()['objectid'];
            if ($objectId===3076){
//                dd($isOwned);
            }
            if ($isOwned && $objectType === 'thing' && $objectSubtype === 'boardgame') {
                GameService::linkGame($objectId, $user);
            }
        }

        dd($user->games);
    }
}
