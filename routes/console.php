<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 클로저 기반의 아티즌 명령어 정의 예제
Artisan::command('password:reset {userId} {--sendEmail}',function ($userId, $sendEmail) {
    $user = \App\Models\User::find($userId);
    //dump($sendEmail);
    dump($user);
});
