<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//라우트 파라미터
/*Route::get('users/{id}/friends', function ($id) {

});
//라우트 파라미터 옵션
Route::get('users/{id?}', function ($id = 'fallbackId') {
    return $id;
});*/

//라우터 파라미터의 정규표현식 추가
Route::get('users/{id}', function ($id) {
    dd(route('members.show', ['id' => $id])) ;
    return 'a';
})->where('id', '[0-9]+')->name('members.show');

Route::get('users/{username}', function ($username) {
    return 'b';
})->where('username', '[A-Za-z]+');

Route::get('posts/{id}/{slug}', function ($id, $slug) {
    return 'c';
})->where(['id' => '[0-9]+', 'slug' => '[A-Za-z]+' ]);

// 라우트에 이름 지정하기
Route::get('members/{id}', [\App\Http\Controllers\MemberController::class, 'show'])->name('members.show');
//echo route('members.show', ['id' => 1, 'commentId' => 2, 'opt' => 'a']); route핼퍼 함수에 라우트 파라미터 전달하기 localhost/members/1/comment/2?opt=a

//로그인한 사용자만 접근하게 지정한 라우트 그룹
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return 'dashboard';
    });
    Route::get('account', function () {
        return 'account';
    });
});

//시간당 접속 제한하기
Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('/photos',function () {

    });
});
