<?php

namespace Chriscreates\Social\Http\Controllers;

use App\Providers\RouteServiceProvider;
use Chriscreates\Social\Contracts\SocialCreateAuthContract;
use Chriscreates\Social\Contracts\SocialFindAuthContract;
use Chriscreates\Social\Events\SocialAttempt;
use Chriscreates\Social\Events\SocialFound;
use Chriscreates\Social\Social;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthCallbackController extends Controller
{
    public function __invoke(
        string $provider,
        SocialFindAuthContract $socialFindAuthContract,
        SocialCreateAuthContract $socialCreateAuthContract
    ) {
        event(new SocialAttempt($provider));

        $socialiteUser = Socialite::driver($provider)->user();

        $user = $socialFindAuthContract->execute($socialiteUser);

        $socialClass = Social::getAuthClassName();

        if ($user instanceof $socialClass) {
            event(new SocialFound($provider, $user));
        } else {
            $user = $socialCreateAuthContract->create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
            ]);
        }

        Auth::guard(config('social.guard'))->login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
