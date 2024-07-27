<?php
Route::namespace('App\Http\Controllers\Api')->group(function () {
    //Route::apiResource('dogs', 'DogsController');

    // 라라벨에서 응답 해더 추가하기
    Route::get('dogs', function () {
        return response(Dog::all())->header('X-Greatness-Index', 12);
    });

    // 라라벨에서 요청 해더 읽기
    Route::get('dogs', function (Request $request) {
        var_dump($request->header('Accept'));
    });

    // 페이지 처리된 API 라우트
    Route::get('dogs', function () {
        return Dog::paginate(20);
        // GET /dogs
        // GET /dogs?page=1
        // GET /dogs?page=2
    });

    // 쿼리 빌더 paginate 호출
    Route::get('dogs', function () {
        return DB::table('dogs')->paginate(20);
    });

    // 가장 간단한 API 정렬
    Route::get('dogs', function (Request $request) {
        $sortColumn = $request->input('sort', 'name');
        return Dog::orderBy($sortColumn)->paginate(20);
    });

    // 방향을 조절할 수 있는 단일 칼럼 api 정렬
    Route::get('dogs', function (Request $request) {
        $sortColumn = $request->input('sort', 'name');
        $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc';
        $sortColumn = ltrim($sortColumn, '-');
        return Dog::orderBy($sortColumn, $sortDirection)->paginate(20);
    });

    // json-api 방식 정렬
    Route::get('dogs', function (Request $request) {
        $sorts = explode(',', $request->input('sort',''));
        $query = Dog::query();
        foreach ($sorts as $sortColumn) {
            $sortDirection = Str::startsWith($sortColumn, '-') ? 'desc' : 'asc';
            $sortColumn = ltrim($sortColumn, '-');
            $query->orderBy($sortColumn, $sortDirection);
        }
        return $query->paginate(20);
    });

    // 필터
    Route::get('dogs', function (Request $request) {
        $query = Dog::query();
        $query->when(request()->filled('filter'), function ($query) {
            [$criteria, $value] = explode(':', request('filter'));
            return $query->where($criteria, $value);
        });
        return $query->paginate(20);
    });

    // 다중 필터
    Route::get('dogs', function (Request $request) {
        $query = Dog::query();
        $query->when(request()->filled('filter'), function ($query) {
            $filters = explode(',', request('filter'));
            foreach ($filters as $filter) {
                [$criteria, $value] = explode(':', $filter);
                $query->where($criteria, $value);
            }
            return $query;
        });
        return $query->paginate(20);
    });

    // 단순 Dog 리소스 활용
    Route::get('dogs/{dogId}', function ($dogId) {
        return new DogResource(Dog::find($dogId));
    });

    // 리소스 컬렉션 활용
    Route::get('dogs', function () {
        return DogResource::collection(Dog::all());
    });

    // 리소스 컬렉션에 페이지네이터 객체 전달하기
    Route::get('dogs', function () {
        return new DogCollection(Dog::paginate(20));
    });

    // 조건에 따라 속성 적용하기
    return [
        'name' => $this->name,
        'breed' => $this->breed,
        'rating' => $this->when(Auth::user()->canSeeRatings(), 12)
    ];
});

