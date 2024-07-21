<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        //컨트롤러에 미들웨어 적용하기
        $this->middleware('auth');
        $this->middleware('admin-auth')->only('editUsers');
        $this->middleware('team-member')->except('editUsers');
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
