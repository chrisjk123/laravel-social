<?php

namespace Chriscreates\Social\Tests\TestClasses;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TestAuthModel extends Authenticatable
{
    public $table = 'test_auth_models';

    protected $guarded = [];

    public $timestamps = false;
}
