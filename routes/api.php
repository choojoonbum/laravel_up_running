<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Dog;
use App\Http\Resources\Dog as DogResource;
use App\Http\Resources\DogCollection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// 패스포트 인증 미들웨어로 api 라우터 보호하기
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

// Bearer 토큰을 사용하면서 api 요청하기
Route::get('/token', function (Request $request) {
    $http = new GuzzleHttp\Client();
    $response = $http->request('GET', 'http://tweeter.test/api/user', [
        'headers' => [
            'Accept' => 'application/json',
            'Authorization' => ' Bearer ' . $accessToken
        ]
    ]);
});

// 토큰 스코프에 기반해서 접근을 제한하기 위해 미들웨어 사용하기
Route::get('clips', function () {
    // 액세스 토큰이 list-clips,add-delete-clips 둘다 가지고 있다
})->middleware('scopes:list-clips,add-delete-clips');

Route::get('clips', function () {
    // 액세스 토큰이 나열된 스코프중 하나를 가지고 있다.
})->middleware('scope:list-clips,add-delete-clips');


