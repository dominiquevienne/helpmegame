<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $url = 'https://boardgamegeek.com/xmlapi2/collection?username=bloulapinou';

        dump(simplexml_load_file($url));
//        echo 'toto';
    }
}
