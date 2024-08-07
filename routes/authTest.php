<?php

use Illuminate\Support\Facades\Gate;

Route::post('login-test',function () {
    // 사용자 인증 시도,
    if (auth()->attempt([
        'email' => request()->input('email'),
        'password' => request()->input('password')
    ])) {
        dump('로그인성공');
    } else {
        dump('실패');
    }

    // remember token 사용여부
    //dump(auth()->viaRemember());
});

Route::get('logout-test',function () {
    auth()->logout();
    dump('성공');
});

// auth  미들웨어로 라우트를 보호하는 예
Route::middleware('auth')->group(function () {
    Route::get('account', function () {
        dump('라우터 보호');
    });
});

// 비회원만 접근 가능
Route::get('login-test',function () {
    return view('auth.login_test');
})->middleware('guest');

// auth 라우터 미들웨어는 가드명을 파라미터로 받을 수 있다.
Route::middleware('auth:trainees')->group(function () {
    // trainee 가드의 인증이 필요한 전용 라우트 그룹
});


// 인증 미들웨어 사용하기
Route::get('people/create', function () {
    // 인물을 생성한다.
})->middleware('can:create-person');

Route::get('people/{person}/edit', function () {
    // 인물을 수정한다
})->middleware('can:edit,person');

Route::post('people', function () {
    // 인물을 생성한다.
})->middleware('can:create,App\models\person');


// user 인스턴스를 사용하여 인가 확인하기
Route::get('test', function () {
    $user = \App\Models\User::find(1);

    if ($user->can('create-contact')) {
        //
    }
    // 위 소스와 동일한 실행 코드이다.
    // Gate::forUser($user)->check('create-contact');
});

Route::get('test', function () {

    // 정책에 대응하여 권한 확인하기
    // 첫번째 파라미터를 정책 클래스의 어떤 메서드를 실행할 것인지 파악
    // Gate::allows('update',$contact) 실행시 ContactPolicy@update 사용
    // 게이트
    if (Gate::denies('update', $contact)) {
        abort(403);
    }
    // 명시적인 인스턴스가 없을때의 게이트
    if (! Gate::check('create', Contact::class)) {
        abort(403);
    }
    // user
    if ($user->can('update', $contact)) {

    }


    // 정책 클래스를 찾아 관련 메서들 실행하는 핼퍼
    if (policy($contact)->update($user, $contact)) {

    }


});
