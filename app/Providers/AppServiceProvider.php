<?php

namespace App\Providers;

use App\Http\ViewComposers\RecentPostsComposer;
use App\Models\Contact;
use App\Models\Task;
use Illuminate\Pagination\Paginator;
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

        // 서비스 공급자에서 커스텀 블레이드 지시어 등록하기
        Blade::directive('ifGuest', function () {
            return "<?php if (auth()->guest()): ?>";
        });

        // 파라미터를 전달받을 수 있는 커스텀 블레이드 지시어 등록하기
        Blade::directive('newlinesToBar', function ($expression) {
            return "<?php echo nl2br({$expression}); ?>";
        });

        /*// Blade::if 메서드를 사용해서 간단히 처리
        Blade::if('ifGuest', function () {
            return auth()->guest();
        });*/

        // 웰로퀸트 이벤트를 수신하는 코드 등록하기
        /*
        $thirdPartyService = new SomeThirdPartyService();
        Contact::creating(function ($content) use ($thirdPartyService) {
            try {
                $thirdPartyService->addContact($content);
            } catch (\Exception $e) {
                \Log::error('ThirdPartyService에 새로운 contact 추가 실패');
                return false; // false 반환시 엘로퀸트 모델 생성이 취소된다.
            }
        });
        // saving - creating, updating  // saved - created, updated
        */
        Contact::creating(function ($content) {
            try {
                dump($content);
                dump('이벤트 처리');
            } catch (\Exception $e) {
                \Log::error('ThirdPartyService에 새로운 contact 추가 실패');
                return false; // false 반환시 엘로퀸트 모델 생성이 취소된다.
            }
        });

        // 페이징 부트스트랩 사용
        Paginator::useBootstrap();

    }
}
