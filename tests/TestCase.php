<?php

namespace Chriscreates\Social\Tests;

use Chriscreates\Social\Providers\SocialServiceProvider;
use Chriscreates\Social\Tests\TestClasses\TestAuthModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\SocialiteServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use LazilyRefreshDatabase;

    protected $loadEnvironmentVariables = true;

    public $providers = [
        'bitbucket',
        'facebook',
        'github',
        'gitlab',
        'google',
        'linkedin',
        'twitter',
    ];

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
        $app['config']->set('social.model', TestAuthModel::class);

        foreach ($this->providers as $provider) {
            $app['config']->set("services.{$provider}", [
                'client_id' => 'your-client-id',
                'client_secret' => 'your-client-secret',
                'redirect' => 'http://your-callback-url',
            ]);
        }
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
