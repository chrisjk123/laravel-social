<?php

namespace Chriscreates\Social\Http\Controllers;

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
        SocialAttempt::dispatch($provider);

        $socialiteUser = Socialite::driver($provider)->user();

        $user = $socialFindAuthContract->execute($socialiteUser);

        $socialClass = Social::getAuthClassName();

        if ($user instanceof $socialClass) {
            SocialFound::dispatch($provider, $user);
        } else {
            $user = $socialCreateAuthContract->create([
                'name' => $socialiteUser->getName(),
                'email' => $socialiteUser->getEmail(),
            ]);
        }

        Auth::guard(config('social.guard'))->login($user);

        return redirect(config('social.home'), 303);
    }
}
