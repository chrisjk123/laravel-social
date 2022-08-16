<?php

namespace Chriscreates\Social;

class Social
{
    public static function getAuthClassName(): string
    {
        return config('social.model');
    }
}
