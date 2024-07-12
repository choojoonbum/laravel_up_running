<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\URL;
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
//특정라우터를 스로틀 미들웨어로 접속 제한하기
Route::middleware(['throttle:uploads'])->group(function () {
    Route::post('/photos',function () {

    });
});

// 라우트 그룹으로 url 접두사 처리
Route::prefix('dashboard')->group(function () {
    Route::get('/', function () {
        // /dashboard
    });
    Route::get('users', function () {
        // /dashboard/users
    });
});

// 모든 라우트 매칭 실패시 대체 라우트 정의
// 라라벨 5.6 이하 지원안함
Route::fallback(function () {
    return 'fallback';
});

//서브 도메인 라우트
Route::domain('api.myapp.com')->group(function (){
    Route::get('/', function () {

    });
});

//{blogName}.tistory.com
Route::domain('{account}.myapp.com')->group(function () {
    Route::get('/', function ($account) {

    });

    Route::get('users/{id}', function ($account, $id) {

    });
});

//공통 네임스페이스 접두사 지정하기
Route::namespace('App\Http\Controllers\Dashboard')->group(function () {
    // App\Http\Controllers\Dashboard\PurchaseController
    Route::get('dashboard/purchase', 'PurchaseController@index');
});

// 라우트 그룹의 이름 접두사 지정하기
Route::name('users.')->prefix('users')->group(function () {
    Route::name('comments.')->prefix('comments')->group(function () {
        Route::get('{id}', function () {
            // /users/comments/{id}의 url 경로
            // users.comments.show 라우트 이름으로 등록
        })->name('show');
    });
});

// 라우트에 서명 추가하기
Route::get('invitations/{invitation}/{group}', InvitationController::class)->name('invitations')->middleware('signed');
Route::get('/invitationsLink', function () {
    // 일반링크
    //$url = URL::route('invitations', ['invitation' => 5816, 'group' => 678]);
    // 서명된 링크 생성하기
    //$url = URL::signedRoute('invitations', ['invitation' => 5816, 'group' => 678]);
    // 유효기간이 있는 서명된 링크 생성하기
    $url = URL::temporarySignedRoute('invitations', now()->addHour(4), ['invitation' => 5816, 'group' => 678]);

    return $url;
});

// 뷰에 변수 전달하기
Route::get('tasks', function () {
    return view('tasks.index')->with('tasks', ['task' => 'all']);
});

// Route::view()
Route::view('/', 'welcome');
Route::view('/', 'welcome', ['User' => 'Michael']);

// 특정변수를 모든 템플릿에 공유
// view()->share('variableName', 'variableValue');

