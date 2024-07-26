<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Log\Logger;

class MyController extends Controller
{
    protected $logger;

    // 컨틀롤러의 생성자 메서드에 의존성 주입하기
    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function index()
    {
        $this->logger->error('Something happend');
    }

    // 메서드 의존성은 라우트 파라미터 앞이나 뒤에 적어올수 있다.
    public function show(Logger $logger, $id)
    {
        $logger->error('somethig happend');
    }
}
