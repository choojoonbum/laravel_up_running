<?php
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
// 직접 페이지네이터 생성하기
Route::get('people', function (Request $request) {
    $posts = Post::all();
    $perPage = 15;
    $offsetPages = $request->input('page', 1) - 1;

    // 페이지네이터를 직접 처리
    //$posts = array_slice($posts, $offsetPages * $perPage, $perPage);
    $posts = $posts->slice($offsetPages * $perPage, $perPage);

    dd(new Paginator($posts, $perPage));

});

Route::get('messagebag', function () {
    // 직접 메세지 클래스를 생성 사용
    $message = [
        'errors' => [
            '수정하는데 오류가 발생했습니다.'
        ],
        'message' => [
            '수정 작업이 완료 되었습니다.'
        ]
    ];
    $messagebag = new \Illuminate\Support\MessageBag($message);

    if ($messagebag->has('error')) {
        echo '<ul id="errors">';
        foreach ($messagebag->get('errors', '<li><b>:message</b></li>') as $error) {
            echo $error;
        }
        echo '</ul>';
    }
});

// 다국어 기능 사용방법
Route::get('/es/contacts/show/{id}', function () {
    // 수동으로 로케일 설정 원래는 서비스 공급자에 적용해야 함
    App::setLocale('es');
    return view('contacts.show');
});

// 파일 처리 예제
Route::post('file', function (Request $request) {
    dump($request->all());
    if ($request->hasFile('profile_picture')) { // 사용자가 업로드한 파일이 존재하는지 확인
        if ($request->file('profile_picture')->isValid()) { // 파일이 성공적으로 업로드 되었는지 확인
            dump($request->file('profile_picture'));
        }
    }

    // Illuminate\Http\UploadedFile 클래스를 사용
    // 일반적인 파일 업로드 처리 로직
    if ($request->hasFile('profile_picture')) {
        $path = $request->profile_picture->store('profiles', 's3');
        auth()->user()->profile_picture = $path;
        auth()->user()->save();
        dump('성공');
    }
});

Route::get('recipes/create', 'RecipeController@create');
Route::post('recipes', 'RecipeController@store');

// 수동으로 유효성 검증하기
Route::post('recipes', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'title' => 'require|unique:recipes|max:125',
        'body' => 'require',
        'email' => new \App\Http\Rules\WhitelistedEmailDomain, // 커스텀 Rule 객체
    ]);

    if ($validator->fails()) {
        return redirect('recipes/create')->withErrors($validator)->withInput();
    }
});

// 생성한 폼 요청 객체 사용
Route::post('comments', function (\App\Http\Requests\CreateCommentRequest $request) {
   // 댓글 저장
});

// 일반적인 코드에서 아티즌 명령어를 호출하는 예제
Route::get('test-artisan', function () {
/*    $exitCode = Artisan::call('password:reset', [
        'userId' => 10,
        '--sendEmail' => true,
    ]);*/
    Artisan::call('password:reset 10 --sendEmail'); // 라라벨 5.8 이상
});





Route::get('request-test', function (Request $request) {
    dump($request->method());
    dump($request->path());
    dump($request->url());
    dump($request->is('pe*'));
    dump($request->ip());
    dump($request->header());
    dump($request->server());
    dump($request->secure());
    dump($request->pjax());
    dump($request->wantsJson());
    dump($request->isJson());
    //dump($request->accepts());

    dump($request->file());
    dump($request->allFiles());
    //dump($request->hasFile());

    dump($request->flash());
    //dump($request->flashOnly());
    //dump($request->flashExcept());
    dump($request->old());
    dump($request->flush());
    dump($request->cookie());
    //dump($request->hasCookie());
});

Route::get('route', function () {
    //return new \Illuminate\Http\Response('Hello');
    //return response('hello');

    // Http 상태코드와 헤더를 변경한 간단한 http 응답
    return response('Error!', 400)
        ->header('X-Header-Name','header-value')
        ->cookie('cookie-name','cookie-value');
});

// view 응답 타입 사용하기
Route::get('xml-structure', function () {
    return response()->view('xml-structure', 'xmlGetterService')
    ->header('Content-Type', 'text/xml');
});

// 응답 타입 사용하기
Route::get('download', function () {
    return response()->download('file.csv', 'export.csv', ['header' => 'value']);
});
Route::get('order-export', function () {
    return response()->download('file.csv');
});
Route::get('delete-export', function () {
    return response()->download('file.csv', 'export.csv')->deleteFileAfterSend(); // 원본파일 삭제
});
Route::get('response-type/{id}', function ($id) {
    return response()->file("{$id}.jpg", ['header', 'value']);
});
Route::get('json', function () {
    return response()->json(\App\Models\Contact::all());
});
Route::get('non-eloq-json', function () {
    return response()->json(['tom', 'jerry']);
});
Route::get('jsonp', function (Request $request) {
    return response()->json(\App\Models\Contact::all())->setCallback($request->input('callback'));
});


Route::get('redirect', function () {

    // 단순한  Responseable 객체 만들기
    return new \App\Http\Responses\MyJson(['name' => 'Sangeetha']);

    // 커스텀 응답 메크로 사용
    return response()->myJson(['name' => 'Sangeetha']);
/*    return redirect('accout/payment');
    return redirect()->to('accout/payment');
    return redirect()->route('accout.payment');
    return redirect()->action('AccountController@showPayment');*/

    // 외부 도메인으로 리다이렉트
    return redirect()->away('http://naver.com');
    // 이름이 있는 라우터나 컨틀롤러가 라우트 파라미터를 필요로 한다면
    return redirect()->route('contacts.edit', ['id'=>15]);
    return redirect()->action('Controller@edit', ['id' => 15]);

    // 유효성 검증에 실패하면...
    return back()->withInput();

    // 플래시 데이터와 함께 리다이렉트하기
    return redirect('dashboard')->with('message', 'contact created!');
    // 세션에서 플래시된 데이터를 조회.. 주로 블레이드 템플릿에서 처리된다.
    echo session('message');
});

//  미들웨어을 라우트 정의해서 사용하기
Route::get('contacts','ContactController@index')->middleware('ban-delete'); // route get 메서드에서는 미들웨어가 동작하지 않는다.

// 그룹기능에 적용하기 좋다(
Route::prefix('api')->middleware('ban-delete')->group(function () {
    // api 관련 모든 라우트
});

Route::get('company', function () {
    return view('company.admin');
})->middleware('auth:owner,view'); // 파라미터 2개이상 넘기는 경우
