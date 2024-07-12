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
}
