<?php

namespace Chriscreates\Social\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Laravel\Socialite\Two\User as SocialiteUser;

interface SocialFindAuthContract
{
    public function execute(SocialiteUser $socialiteUser): null|Authenticatable;
}
