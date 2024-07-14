<?php

namespace App\Providers;

use App\Http\ViewComposers\RecentPostsComposer;
use App\Models\Task;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 컴포넌트의 별칭을 추가하여 사용
        Blade::component('partials.modal', 'modal');

        // 변수를 전역으로 공유하기
        //view()->share('recentPosts', Task::recent());

/*        // 클로저를 사용해 특정 뷰에 변수를 공유
        view()->composer('sidebar', function ($view) {
            $view->with('recentPosts', Task::recent());
        });*/

        // 생성한 뷰 컴포저 클래스 등록하기
        view()->composer('sidebar', RecentPostsComposer::class);
    }
}
