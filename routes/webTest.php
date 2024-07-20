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




