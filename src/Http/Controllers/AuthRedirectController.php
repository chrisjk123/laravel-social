<?php

namespace Chriscreates\Social\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class AuthRedirectController extends Controller
{
    public function __invoke(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }
}
