<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class ContactsController extends Controller
{
    public function save(Request $request)
    {
        // 데이터 추가
        $contacts = new Contact();
        $contacts->firtst_name = $request->input('first_name');
        $contacts->last_name = $request->input('last_name');
        $contacts->email = $request->input('email');
        $contacts->save();

        $contacts = new Contact([
            'name' => 'ken hirue',
            'email' => 'ken@hirata.com'
        ]);
        $contacts->save();

        $contacts = Contact::make([
            'name' => 'ken hirue',
            'email' => 'ken@hirata.com'
        ]);
        $contacts->save();

        // create() 인스턴스 생성후 디비 저장
        $contacts = Contact::create([
            'name' => 'ken hirue',
            'email' => 'ken@hirata.com'
        ]);

        // 엘로퀸트 인스턴스 조회후 save 메서드로 레코드 수정
        $contact = Contact::find(1);
        $contact->email = 'natalie@parkfamily.com';
        $contact->save();


        return redirect('contacts');
    }

    public function show($contactId)
    {
        // URL 세그먼트를 기반으로 하나의 연락처 정보를 조회하고 JSON으로 반환
        // 만약 ID에 해당하는 데이터가 없으면 예외가 발생한다.
        return view('contacts.show')->with('contact', Contact::findOrFail($contactId));
    }

    public function vips()
    {
        // 조금 더 복잡한 예제이지만 기본적인 엘로퀸트를 할용한 기능이다.
        // VIP로 지정된 연락처 목록을 확인하여 formalName 속성을 지정한다.
        return Contact::where('vip', true)->get()->map(function ($contact) {
            $contact->formalName = "The exalted {$contact->first_name} of the {$contact->last_name}s";
        });
    }

    public function test()
    {
        // 전체 데이터 조회
        $allContats = Contact::all();

        // 조건절
        Contact::where('vip', true)->get();

        Contact::orderBy('created_at', 'desc')->take(10)->get();

        // 더 적은 메모리 자원 사용
        Contact::chunk(100, function ($contacts) {
            foreach ($contacts as $contact) {
                //
            }
        });

        // 집계 쿼리
        $countVips = Contact::where('vip', true)->count();
        $sumVotes = Contact::sum('votes');
        $averageSkill = User::avg('skill_level');

        // 인스턴스 조회후 수정
        Contact::where('created_at', '<', now()->subYear())->update(['longevity' => 'ancient']);

        $contact = Contact::find(1);
        $contact->update(['longevity' => 'ancient']);

        // 전달받은 값의 인스턴스를 찾아온다. 데이터가 없다면 전달해준 데이터를 저장 후 반환
        $contact = Contact::firstOrCreate(['email' => 'luis.ramos@myacme.com']);
        // 전달받은 값의 인스턴스를 찾아온다. 데이터가 없다면 전달해준 데이터를 반환
        $contact = Contact::firstOrNew(['email' => 'luis.ramos@myacme.com']);

    }

    public function test2()
    {

        // 기본적인 삭제
        $contact = Contact::find(5);
        $contact->delete();
        Contact::destroy(1);
        Contact::destroy([1,2]);
        Contact::where('updated_at', '<', now()->subYear())->delete();

        // 소프트 삭제된 레코드 조회
        $allHistoricContacts = Contact::withTrashed()->get(); // 소프트 삭제된 데이터도 함께 조회
        Contact::onlyTrashed()->get(); // 소프트삭제만 조회

        $contact->restore(); // 삭제된 데이터 복원
        Contact::onlyTrashed()->where('vip', true)->restore();

        // 완전 삭제
        $contact->forceDelete();
        Contact::onlyTrashed()->forceDelete();
    }


}
