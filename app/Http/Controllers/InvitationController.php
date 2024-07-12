<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvitationController extends Controller
{
    // 단일 액션 컨트롤러 사용시 __invoke 매직 메서드 사용
    public function __invoke($invitation, $group, Request $request)
    {
        //미들웨어 대신 hasValidSignature 메서드로 유효성 검사 가능
        if (! $request->hasValidSignature()) {
            abort(403);
        } else {
            dd($request->all());
        }
    }
}
