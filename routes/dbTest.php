<?php
use App\Models\Contact;
use App\Models\PhoneNumber;
use App\Models\User;
use App\Models\Event;
use App\Models\Star;

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

Route::get('test7', function () {

    // 다대다 연관관계인 각각의 모델에서 연관관계 컬렉션을 조회하는 방법
    $user = User::first();
    $user->contacts->each(function ($contact) {
        dump($contact->name);
    });

    Contact::first()->users()->each(function ($user) {
        dump($user->name);
    });

    // 연관관계 모델의 피벗 속성을 통해서 피벗 테이블 레코드 속성 조회
    User::first()->contacts()->each(function ($content) {
        dump($content->member_ship->created_at);
        dump($content->member_ship->status);
        dump($content->name);
    });
});

Route::get('test8', function () {

    // 다대다 연관관계의 모델을 추가하고 해제할때 피벗 테이블 고려하기
/*    $user = User::find(2);
    $contact = Contact::first();
    // 두번째 인자에 피벗테이블 값 전달, 인스턴스로 저장
    $user->contacts()->save($contact, ['status' => 'donor']);*/

    $user = User::find(4);
    // 아이디값을 직접 저장
    //$user->contacts()->attach(1);
    //$user->contacts()->attach(2, ['status' => 'donor']);
    //$user->contacts()->attach([1,2,3]);
/*    $user->contacts()->attach([1 => ['status' => 'inactive'], 2, 3]);*/

    //연결된 연락처 모델 전체 해제
    //$user->contacts()->detach();
    //$user->contacts()->detach(1);
    //$user->contacts()->detach([1,2]);

    // 연관관계 저장 삭제 토클처리
    //$user->contacts()->toggle([1,2,3]);

    // 피벗 테이블 레코드 변경처리
    //$user->contacts()->updateExistingPivot(1, ['status' => 'inactive']);

    // 관련된 데이터(유저4)를 전체 삭제후 새로운 연관 관계 추가
    //$user->contacts()->sync([2]);
    $user->contacts()->sync([1 => ['status' => 'inactive'], 2, 3]);

});

Route::get('test9', function () {
    // 다형성 연관관계 데이터 생성
    //$contact = Contact::find(2);
    //$contact->stars()->create();

    //Event::first()->stars()->create();

    // 다형성 연관관계의 모델 인스턴스 조회하기
    Contact::find(2)->stars()->each(function ($star) {
        //dump($star);
    });

    // 다형성 연관관계 모델에서 대상 모델 인스턴스 조회 및 저장
    $stars = Star::all();

    $stars->each(function ($star) {
        //dump($star->starrable);
    });


    //$user = User::first();
    //$event = Event::first();
    //$event->stars()->create(['user_id' => $user->id]);

    //dd(Star::first()->user);
    //Contact::find(20)->stars()->create(['user_id' => User::find(7)]);


    Contact::find(20)->stars()->create(['user_id' => User::find(7)->id]);

});

use App\Models\Tag;
Route::get('test10', function () {

    // 다대다 다형성 연관관계 연결
    $tag = Tag::firstOrCreate(['tag_name' => 'likes-cheese']);
    $contact = Contact::first();
    //$contact->tags()->attach($tag->id);


    $contact = Contact::first();
    $contact->tags->each(function ($tag) {
        //dump($tag);
    });

    $tag = Tag::first();
    $tag->contacts->each(function ($contact) {
        dump($contact);
    });

});

Route::get('test11', function () {
    // 하위모델 수정시 상위모델에도 타입스템프값 변경
    $phoneNumber = PhoneNumber::first();
    $phoneNumber->phone_number = '01026405799';
    //Contact::first()->phoneNumbers()->save($phoneNumber);

    // 목록 출력시 N+1 쿼리 처리
    //$contacts = Contact::all();
    //$contacts = Contact::with('phoneNumbers')->get(); // eager 로딩 N+1문제 해결
    $contacts = Contact::with('phoneNumbers','users')->get(); // 여러개도 가능
    foreach ($contacts as $contact) {
        foreach ($contact->phoneNumbers as $phoneNumber) {
            //dump($phoneNumber->phone_number." xxxx");
        }
        foreach ($contact->users as $user) {
            //dump($user->name." yyyy");
        }
    }

    $contacts = Contact::with(['phoneNumbers' => function ($query){
        $query->where('phone_number', '01026405799');
    }])->get();

    $contacts->each(function ($contact) {
        foreach ($contact->phoneNumbers as $phoneNumber) {
            //dump($phoneNumber->phone_number);
        }
    });

    // 지연 eager 로딩
/*    $contacs = Contact::all();
    if($showPhoneNumbers = false) {
        $contacts->load('phoneNumbers'); // 연관 모델을 불러오기 전까지 한번의 쿼리를 실행해 n+1 문제 해결
        $contacts->loadMissing('phoneNumbers'); //연관관계를 조회하지 않았을때만 eager 로딩을 하고자 할때
    }*/


    // 연관 관계된 모델의 개수 조회
    $contacts = User::withCount('phoneNumbers')->get();
    foreach ($contacts as $contact) {
        dump($contact->phone_numbers_count);
    }
});




















