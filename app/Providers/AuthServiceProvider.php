<?php

namespace App\Providers;

use App\Models\Contact;
use App\Models\User;
use App\Policies\ContactPolicy;
use http\Env\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Contact::class => ContactPolicy::class, // 정책 클래스 등록하기, 정책클래스는 조건에 맞게 처리시 자동으로 등록가능하다
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 클로저 요청 가이드 정의하기
        \Auth::viaRequest('token-hash', function ($request) {
            return User::where('token-hash', $request->token)->first();
        });

        // 관계형 데이터베이스를 사용하지 않는 커스텀 유저 프로바이더
        \Auth::provider('riak', function ($app, array $config) {
            // Illuminate\Contracts\Auth\UserProvider 인터페이스를 구현한 클래스를 반환
            return new RiakUserProvider($app['riak.connection']);
        });

        // ccontact를 수정하는 어빌리티(인가규칙)
        Gate::define('update-contact', function ($user, $contact) {
            return $user->id === $contact->user_id;
        });

        // 클로저 대신 클래스와 매서드로 정의가능
        // $gate->define('update-contact', [ContactAllChecker::class, 'updateContact']);

        // 여러 파라미터를 갖는 어빌리티
        Gate::define('add-contact-to-group', function ($user, $contact, $group) {
            return $user->id === $contact->user_id && $user->id === $group->user_id;
        });

/*        // 리소스 gate
        Gate::resource('photos', PhotoPolicy::class);
        // 위 리소스 정의와 동일
        Gate::define('photos.viewAny', [PhotoPolicy::class, 'viewAny']);
        Gate::define('photos.view', [PhotoPolicy::class, 'view']);
        Gate::define('photos.create', [PhotoPolicy::class, 'create']);
        Gate::define('photos.update', [PhotoPolicy::class, 'update']);
        Gate::define('photos.delete', [PhotoPolicy::class, 'delete']);*/

        // 권한 확인 가로채기(권한 확인 오버라이드하기)
        Gate::before(function ($user, $ability) {
            if ($user->isOwner()) {
                return true;
            }
        });
    }
}

