<?php

namespace App\Http\Controllers;

use App\Http\Resources\Dog;
use App\Models\Review;
use App\Models\User;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class DogController extends Controller
{
    // 일반적인 사용자 업로드 처리 작업
    public function updatePicture(Request $request, Dog $dog)
    {
        Storage::put(
            "dogs/{$dog->id}",
            file_get_contents($request->file('picture')->getRealPath())
        );
    }

    // Intervention을 사용하는 조금 더 복잡한 파일 업로드
    public function updatePicture2(Request $request, Dog $dog)
    {
        $original = $request->file('picture');

        $image = Image::make($original)->resize(150, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('jpg', 75);

        Storage::put("dogs/thumb/{$dog->id}", $image->getEncoded());
    }

    // 간단한 파일 다운로드
    public function downloadMyFile()
    {
        return Storage::download('my-file.pdf');
    }

    // 세션 사용하기
    public function sessionUse(Request $request, Store $session)
    {
        // 세션 퍼사드
        Session::get('user_id');

        // Request객체에서 session메서드 사용
        $request->session()->get('user_id');

        // 세션 구현체 사용
        $session->get('user_id');

        // 글로벌 세션 핼퍼사용
        $value = session()->get('key');
        $value = session('key');
        session()->put('key', 'value');
        session(['key', 'value']);

        // 세션 인스턴스 메서드
        session()->get('points');
        session()->get('points', 0);
        session()->get('points', function () {
            return (new PointGetterService)->getPoints();
        });
        session()->put('points', 45);
        session()->put('friends', ['Saul', 'Quaagn', 'Meche']);
        session()->push('friend', 'Javir');
        session()->has('points');
        session()->exists('points'); // null값도 참을 반환
        session()->all();
        session()->only('points');
        session()->forget('points'); // points는 지워지고 나머지는 아직 있음
        session()->flush(); // 이제 세션에 아무것도 없음
        session()->pull($key, $fallbackValue); // pull은 값을 가져온 다음 세션에서 지움
        session()->regenerate(); // 세션 id 재생성
        session()->flash($key, $value); // 다음 페이지 요청에만 사용할 세션 키에 값을 설정
        session()->reflash(); // 플래시 세션데이터를 전체 복원
        session()->keep($key); // 플래시 세션데이터를 해당하는 값만 복원
    }

    // 캐쉬
    public function cacheUse(Repository $cache)
    {
        $users = Cache::get('users');
        $cache->get('users');
        cache('key', 'default value');
        cache()->get('key', 'default value');
        cache(['key' => 'value'], $seconds);
        cache()->put('key', 'value', $seconds);
        cache()->get($key, $fallbackValue); // 키에 해당하는 값을 가져옴
        cache()->pull($key, $fallbackValue); // 값을 가져온다음 제거
        cache()->put('key', 'value', now()->addDay()); // 주어진 시간동안 키에 값을 저장
        cache()->add($key, $value); // 값이 이미 존재하면 재설정하지 않는다. 값이 추가되었는지 여부까지 리턴
        cache()->forever($key, $value); // 값을 저장, 값이 만료되지 않는다.
        cache()->has($key);
        // users에 캐시된 값을 반환하거나 값이 없다면 User::all()을 조회해서 캐싱하고 반환한다
        $users = cache()->remember('users', 7200, function () {
            return User::all();
        });
        cache()->rememberForever($key, $closure);
        cache()->increment($key, $amount);
        cache()->decrement($key, $amount);
        cache()->forget($key); // 키를 전달하면 해당 값을 제거
        cache()->flush(); // 전체 캐시를 제거

    }

    public function cookieUse(Request $request)
    {
        // 응답쿠키
        Cookie::queue('dismissed-popup', 'true', 15);
        // cookie 글로벌 핼퍼 사용
        $cookie = cookie('dismissed-popup', 'true', 15);
        // 요청객체에서 쿠키 읽기
        $request->cookie('dismissed-popup', 'false');
        // 응답 객체에 쿠키 설정하기
        return Response::view('dashboard')->cookie($cookie);
    }

    // 라라벨 스카우터
    public function scoutUse()
    {
        // 색인 검색하기
        Review::search('James')->get(); //James 단어를 포함하는 데이터 찾기

        Review::search('James')->paginate(20);
        Review::search('James')->where('account_id', 2)->get();

        // 색인을 하지 않으면서 모델의 특정 작업 처리하기
        Review::withoutSyncingToSearch(function () {
            // 다수의 리뷰를 생성하는 예
            Review::factory()->count(10)->create();
        });

        Review::all()->searchable(); // 수동으로 색인 실행
        Review::where('sucky', true)->unsearchable(); // 색인 해제

    }

}
