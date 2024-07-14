<?php

namespace App\Http\ViewComposers;

use App\Models\Task;
use Illuminate\Contracts\View\View;

// 뷰 컴포저 클래스 파일
class RecentPostsComposer
{
    public function compose(View $view)
    {
        $view->with('recentPosts', Task::recent());
    }
}
