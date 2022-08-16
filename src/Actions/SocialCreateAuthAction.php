<?php

namespace Chriscreates\Social\Actions;

use Chriscreates\Social\Contracts\SocialCreateAuthContract;
use Chriscreates\Social\Social;
use Illuminate\Contracts\Auth\Authenticatable;

class SocialCreateAuthAction implements SocialCreateAuthContract
{
    public function create(array $input): Authenticatable
    {
        $className = Social::getAuthClassName();

        return $className::create([
            'name' => $input['name'],
            'email' => $input['email'],
        ]);
    }
}
