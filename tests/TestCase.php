<?php

namespace Chriscreates\Social\Tests;

use Chriscreates\Social\Providers\SocialServiceProvider;
use Chriscreates\Social\Tests\TestClasses\TestAuthModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected $loadEnvironmentVariables = true;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app)
    {
        return [
            SocialiteServiceProvider::class,
            SocialServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('services.google', [
            'client_id' => 'your-client-id',
            'client_secret' => 'your-client-secret',
            'redirect' => 'http://your-callback-url',
        ]);

        $app['config']->set('social.model', TestAuthModel::class);
    }

    protected function setUpDatabase($app)
    {
        Schema::dropAllTables();

        Schema::create('test_auth_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
        });
    }
}
