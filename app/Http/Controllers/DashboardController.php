<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        //컨트롤러에 미들웨어 적용하기
        $this->middleware('auth');
        $this->middleware('admin-auth')->only('editUsers');
        $this->middleware('team-member')->except('editUsers');

        // 어빌리티 사용
        if (\Gate::allows('update-contact', $contact)) {
            abort(403);
        }
        /*
        if (\Gate::denies('update-contact', $contact)) {
            abort(403);
        }
         */

        // 여러 파라미터를 전달
        if (\Gate::denies('add-contact-to-group', [$contact, $group])) {
            abort(403);
        }

        // 현재 사용자가 아닌 다른 사용자의 인가 여부를 확인하고 싶을때
        if (\Gate::forUser($user)->denies('create-contact')) {
            abort(403);
        }
    }

    // auth() 글로벌 헬퍼를 사용한 예
    public function dashboard()
    {
        if (auth()->guest()) {
            return redirect('sign-up');
        }

        return view('dashboard')->with('user', auth()->user());
    }
}
