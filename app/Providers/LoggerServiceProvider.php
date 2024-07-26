<?php

namespace App\Providers;

use App\container\UserMailer;
use Illuminate\Mail\Mailer;
use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class LoggerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // 기본적인 컨테이너 바인딩
        $this->app->bind(Logger::class, function ($app) {
            return new Logger('/log/path/here', 'error');
        });

        // 컨테이너 클로저 바인딩에서 전달 받은 $app 인자 사용하기
        $this->app->bind(UserMailer::class, function ($app) {
            // 이 바인딩은 기술적으로 특별할게 없다. 컨테이너의 오토와이어링으로 의존성이 다 해결되기 때문
            return new UserMailer($app->make(Mailer::class), $app->make(Logger::class), $app->make(Slack::class));
        });

        // 컨테이너에 싱글턴 바인딩하기
        $this->app->singleton(Logger::class, function () {
            return new Logger('\log\path\here', 'error');
        });

        // 이미 존재하는 인스턴스를 컨테이너에 바인딩하기
        $logger = new Logger('\log\path\here','error');
        $this->app->instance(Logger::class, $logger);

        // 클래스와 문자열에 별칭 붙이기
        $this->app->bind(Logger::class, FirstLogger::class);
        $this->app->bind('log', FirstLogger::class);
        $this->app->alias(FirstLogger::class, 'log');

        // 타입힌팅과 인터페이스에 바인딩하기
        $this->app->bind(\Interfaces\Mailer::class, function () {
            return new MailgunMailer(...);
        });

        // 상황에 따른 바인딩하기
        // FileWrangler 클래스에서 의존성 해결할 경우 syslog 반환
        $this->app->when(FileWrangler::class)->needs(Interfaces\Logger::class)->give(Loggers\Syslog::class);
        // SendWelcomeEmail 클래스에서 의존성 해결할 경우 PaperTrail 반환
        $this->app->when(Jobs\SendWelcomeEmail::class)->needs(Interface\Logger::class)->give(Loggers\PaperTrail::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // 컨테이너의 call() 메서드를 사용해서 클래스 메서드를 직접 호출하기
        app()->call('Foo@bar', ['parameter1', 'value']); // 첫번째 파라미터에 value라는 값을 전달하여서 Foo에 있는 bar메서드 호출하기
    }
}
