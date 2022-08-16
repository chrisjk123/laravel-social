<?php

namespace Chriscreates\Social\Providers;

use Chriscreates\Social\Actions\SocialCreateAuthAction;
use Chriscreates\Social\Contracts\SocialCreateAuthContract;
use Chriscreates\Social\Contracts\SocialFindAuthContract;
use Chriscreates\Social\Services\SocialFindAuthService;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class SocialServiceProvider extends LaravelServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        $this->bootRoutes();
        $this->bootPublishesConfig();

        app()->singleton(SocialFindAuthContract::class, SocialFindAuthService::class);
        app()->singleton(SocialCreateAuthContract::class, SocialCreateAuthAction::class);
    }

    public function register()
    {
        $this->registerConfiguration();
    }

    protected function registerConfiguration()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/social.php',
            'social'
        );
    }

    private function bootRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    private function bootPublishesConfig()
    {
        $this->publishes([
            __DIR__.'/../../config/social.php' => config_path('social.php'),
        ], 'social-config');
    }
}
