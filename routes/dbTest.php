<?php
use App\Models\Contact;
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

    dd($active);


    //dd($friends);

});
