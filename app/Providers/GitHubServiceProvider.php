<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

// 서비스 프로바이더의 등록을 지연시키기
class GitHubServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GitHubClient::class, function ($app) {
            return new GitHubClient();
        });
    }


    public function provides()
    {
        return [
            GitHubClient::class
        ];
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
