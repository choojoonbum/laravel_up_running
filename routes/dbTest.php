<?php
use App\Models\Contact;
use App\Models\PhoneNumber;
use App\Models\User;

Route::get('/test', function () {

    // 로컬 스코프 사용
    $active = Contact::activeVips()->get();

    // 인자를 전달하는 스코프
    $friends = Contact::status('no')->get();

    // 글로벌 스코프 적용됨
    $active = Contact::all();

    // 글로벌 스코프 적용하지 않기
    $allContacts = Contact::withoutGlobalScope(\App\Models\Scopes\ActiveScope::class)->get();
    Contact::withoutGlobalScope('statusGlobal')->get(); // 클로저 사용 스코프
    // Contact::withoutGlobalScopes([\App\Models\Scopes\ActiveScope::class, VipScore::class])->get(); // 여러개의 스코프 적용하지 않기
    Contact::withoutGlobalScopes()->get(); // 모든 스코프 사용하지 않기

    $contact = Contact::find(2);
    $name = $contact->name;
    $fullName = $contact->full_name;
    $contact->amount = 11;
    //$contact->workgroup_name = 'jstott';

    $contact->options = [
        ['aa' => '11'], ['bb' => '22']
        ];
    // $contact->met_at = time();
    // $contact->save();
    dump($contact->vip);

    // 쿼리 결과에 속성값 형변환을 지정
    $contacts = Contact::select(['id', 'name', 'vip'])->withCasts([
        'vip' => 'boolean'
    ])->get();
    $contacts->each(function ($contact) {
        dump($contact->vip);
    });

});

Route::get('/test2', function () {

    // 컬렉션 인스턴스 생성
    $collection = collect([1,2,3]);

    // 컬렉션에서 짝수를 걸러낸다
    $odds = $collection->reject(function ($item) {
        return $item % 2 == 0;
    });

    // 새로운 컬렉션 생성
    $multiplied = $collection->map(function ($item) {
        return $item * 10;
    });

    // 짝수만 필터후 10을 곱한 컬랙션 생성후 전체합 출력
    $sum = $collection->filter(function ($item) {
        return $item % 2 == 0;
    })->map(function ($item) {
        return $item * 10;
    })->sum();

    $orders = \App\Models\Order::all();
    $billableAmount = $orders->sumBillableAmount(); // OrderCollection 클래스 타입을 반환

    dump($orders);

});

// 라우터에서 컬렉션 반환시 문자열(json)로 출력
Route::get('/test3', function () {

    // makeVisible 메서드로 필요할때 출력가능
    return Contact::findOrFail(12)->makeVisible('created_at');

});


Route::get('test4', function () {
    //$contact = Contact::first();

    // 연관모델 데이터를 추가하기
    //$phoneNumber = new PhoneNumber();
    //$phoneNumber->phone_number = '01026405799';
    //$contact->phoneNumber()->save($phoneNumber);
/*
    $contact->phoneNumber()->saveMany([
        PhoneNumber::find(1),
        PhoneNumber::find(2)
    ]);

    $contact->phoneNumber()->create([
        'phone_number' => '+123213123123'
    ]);

    $contact->phoneNumber()->createMany([
        ['phone_number' => '+010264057111'],
        ['phone_number' => '213123231']
    ]);*/

    $user = User::first();
    $userContacts = $user->contacts;

    // 엘로퀜트는 컬렉션을 반환
    $donos = $userContacts->filter(function ($contact) {
        return $contact->status == 'donor';
    });

    //$lifetimeValue = $contact->orders->reduce(function ($carry, $order) {
    //    return $carry + $order->amount;
    //}, 0);

    //$contact = Contact::find(28);
    //$userName = $contact->user->name;

    $contact = Contact::find(1);

    // 하위 모델에서 연관된 아이템을 추가하거나 연관관계 해제하기
    //$contact->user()->disassociate();
    //$contact->save();

    $contact->user()->associate(User::find(1));
    $contact->save();

});

Route::get('test5', function () {
    // 연관관계 쿼리 빌더 사용하기
    //$donors = User::first()->contacts()->where('status', 'donor')->get();
    //dd($donors);

    // 연관관계가 존재하는 레코드만 찾기
    //$postsWithComments = \App\Models\Post::has('comments')->get();
    //$postsWithComments = \App\Models\Post::has('comments', '>=', 5)->get();

    //$usersWithContacts = User::has('contacts')->get();
    //$usersWithContacts = User::has('contacts.phoneNumber')->dd();

    $jennyIGoYourNumber = Contact::whereHas('phoneNumber', function ($query) {
        $query->where('phone_number', 'like', '%5799%');
    })->get();
});

Route::get('test6', function () {

    dd(User::find(1)->phoneNumbers);
    //dd(User::find(1)->phoneNumber);

});
