<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Jobs\ProcessGameFetch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $i = 10501;
        while($i <= 12000) {
            dispatch(new ProcessGameFetch($i));
            $i++;
        }
    }
}
