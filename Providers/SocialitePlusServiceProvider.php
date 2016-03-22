<?php

namespace App\Providers;

use Laravel\Socialite\SocialiteManager;
use Laravel\Socialite\SocialiteServiceProvider;

class SocialitePlusServiceProvider extends SocialiteServiceProvider {
    public function register()
    {
        $this->app->bindShared('Laravel\Socialite\Contracts\Factory', function ($app) {
            $socialiteManager = new SocialiteManager($app);

            $socialiteManager->extend('coinidtest', function() use ($socialiteManager) {
                $config = $this->app['config']['services.coinidtest'];

                return $socialiteManager->buildProvider(
                    'App\Auth\Social\Two\CoinidtestProvider', $config
                );
            });
            return $socialiteManager;
        });
    }
}