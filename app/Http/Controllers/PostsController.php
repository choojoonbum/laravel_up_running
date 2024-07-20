<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        // 디폴트
        // return view('posts.index', ['posts' => DB::table('posts')->paginate(10)->onEachSide(3)]);

        // 페이지네이터를 직접 처리
        $posts = Post::all();
/*        $perPage = 15;
        $offsetPages = $request->input('page', 1) - 1;


        //$posts = array_slice($posts, $offsetPages * $perPage, $perPage);
        $posts = $posts->slice($offsetPages * $perPage, $perPage);

        return new Paginator($posts, 91,$perPage, $offsetPages);
        //return view('posts.index', ['posts' => DB::table('posts')->paginate(10)->onEachSide(3)]);*/

        $posts = $this->paginate($posts);
        return view('posts.index', compact('posts'));
    }

    // https://www.itsolutionstuff.com/post/laravel-6-paginate-with-collection-or-arrayexample.html
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}
