<?php

namespace Chriscreates\Social\Services;

use Chriscreates\Social\Contracts\SocialFindAuthContract;
use Chriscreates\Social\Social;
use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialFindAuthService implements SocialFindAuthContract
{
    public function execute(SocialiteUser $socialiteUser): null|Authenticatable
    {
        $className = Social::getAuthClassName();

        return $className::where('email', $socialiteUser->getEmail())->first();
    }
}
