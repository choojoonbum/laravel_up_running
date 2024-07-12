<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\TaskController;
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
/*Route::namespace('App\Http\Controllers\Dashboard')->group(function () {
    // App\Http\Controllers\Dashboard\PurchaseController
    Route::get('dashboard/purchase', 'PurchaseController@index');
});*/

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

/*Route::get('/', [TaskController::class, 'index']);

Route::get('tasks/create', [TaskController::class, 'create']);
Route::post('tasks', [TaskController::class, 'store']);*/

// 리소스 컨트롤러 연결
Route::resource('tasks', TaskController::class);
//Route::apiResource('tasks', TaskController::class);

// 개별 라우트에서 리소스 확인하기
Route::get('conferences/{id}', function ($id) {
    //$conference = Conference::findOrFail($id);
});

// 묵시적 라우트 모델 바인딩 사용
Route::get('conference/{conference}', function (Conference $conference) {
    return view('conference.show')->with('conference', $conference);
});

// 라우트 모델 바인딩에서 사용되는 엘로퀸트 모델 키 지정
Route::get('posts/{post:slug}', function (Post $post) {
    return $post;
});

// 엘로퀸트 모델을 여러개 바인딩하면서 연관관계의 쿼리 범위를 지정하는 경우(라라벨7 이상)
Route::get('users/{user}/posts{post:slug}', function (User $user, Post $post) {
    // $user는 다음의 쿼리를 수행한 결과
    // {user}는 URL 세그먼트 값이다.
    // user::find({user})->first();

    // $post는 다음의 쿼리를 수정한 결과
    // {slug}는 URL 세그먼트 값이다.
    // User::find({user})->posts()->where('slug', {slug})->first();
    return $post;
});

// 명시적 라우트 모델 바인딩 사용(RouteServiceProvider 설정필요)
Route::get('events/{event}', function (Conference $event) {
    return view('events.show')->with('event', $event);
});

// 라우트 캐싱
// php artisan route:cache
// php artisan route:clear


// 리다이렉트를 수행하는 방법
// 글로벌 핼퍼 함수를 사용하여 리다이렉트 응답 객체를 생성하는 방법
Route::get('redirect-with-helper', function () {
    return redirect()->to('login');
});

Route::get('redirect-with-helper-shortcut', function () {
    return redirect('login');
});

//퍼사드를 사용하여 리다이렉트 응답 객체를 생성하는 방법
Route::get('redirect-with-facade', function () {
    return \Illuminate\Support\Facades\Redirect::to('login');
});

// Route::redirect() 메서드를 사용하는 방법
Route::redirect('redirect-by-route', 'login');

//redirect()->route() 라우트명으로 처리 추천
Route::get('redirect', function () {
    return redirect()->route('conference.index');
    //return redirect()->route('conference.index', ['conference' => 99]);
});

//이전 페이지 이동
//redirect()->back();

// 데이터를 가지고 리다이렉트
Route::get('redirect-with-key-value', function () {
    return redirect('dashboard')->with('error', true);
});
Route::get('redirect-with-array', function () {
    return redirect('dashboard')->with(['error' => true, 'message' => 'Whoops!']);
});

// 사용자 입력 값을 세션에 저장하고 리다이렉트하기
Route::get('form', function () {
    return view('form');
});
Route::post('form', function () {
    return redirect('form')->withInput()->with(['error' => true, 'message' => 'Whoops!']);
});

// 에러를 포함한 리다이렉트
Route::post('form', function (\Illuminate\Http\Request $request) {
    $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $this->validationRules);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }
});

// 403 권한 없음 중단 처리
Route::get('something-you-cant-do', function (\Illuminate\Http\Request $request) {
    abort(403, '접속 권한이 없습니다.');
    abort_unless($request->has('magicToken'), 403);
    abort_if($request->user()->isBanned, 403);

    return response()->make('Hello World!');
});

// 커스텀 응답
Route::get('custom-response', function (\Illuminate\Http\Request $request) {
    //return response()->make('Hello World!');
    //return response()->json([]);
    return response()->streamDownload(function () {
        echo DocumentService::file('myfile')->getContent();
    }, 'myfile.pdf');
});





























