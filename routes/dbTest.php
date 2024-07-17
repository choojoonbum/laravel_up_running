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
