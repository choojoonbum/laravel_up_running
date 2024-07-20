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


