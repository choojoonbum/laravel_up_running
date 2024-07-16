<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use function MongoDB\BSON\toRelaxedExtendedJSON;

class DatabaseTest
{
    public function test()
    {
        // 쿼리를 직접 전달하는 방식
        DB::statement('drop table users');

        // SELECT  쿼리를 직접 전달하고 파라미터를 바인딩하는 호출 방식
        DB::select('select * from contacts where validated = ?', [true]);

        // 체이닝 방법을 사용해 데이터를 조회 하는 방법
        $user = DB::table('users')->get();

        // 다른 테이블과 JOIN  구문을 체이닝으로 호출하는 방법
        DB::table('users')->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')
                ->where('contacts.type', 'donor');
        });

        $user = DB::select('select * from users');

        // 파라미터 바인딩, 이름 바인딩
        $type = 1;
        $userOfType = DB::select('select * from users where type = ?', [$type]);
        $userOfType = DB::select('select * from users where type = :type', ['type' => $type]);

        // 인서트
        DB::insert('insert into contacts (name, email) values (?,?)', ['sally', 'sally@me.com']);

        // 업데이트
        $countUpdate = DB::update('update contacts set status = ? where id = ?', ['donor', $id]);

        // 삭제
        $countDelete = DB::delete('delete from contacts where archived = ?', [true]);

    }

    function test2()
    {
        // 쿼리 빌더 체이닝
        $usersOfType = DB::table('users')->where('type', $type)->get();

        //쿼리 결과 제약 메서드
        $email = DB::table('contacts')->select('email', 'email2 as second_email')->get();
        $email = DB::table('contacts')->select('email')->addSelect('email2 as second_email')->get();

        // where()
        $newContacts = DB::table('contacts')->where('created_at', '>', now()->subDay())->get();
        $vipContacts = DB::table('contacts')->where('vip',true)->get();
        $newVips = DB::table('contacts')->where('vip', true)->where('created_at', '>', now()->subDay());
        $newVips = DB::table('contacts')->where([
            ['vip', true],
            ['created_at', '>', now()->subDay()]
        ]);

        // orWhere()
        $priorityContacts = DB::table('contacts')->where('vip', true)->orWhere('created_at', '>', now()->subDay())->get();
        $contacts = DB::table('contacts')->where('vip',true)->orWhere(function ($query) {
            $query->where('created_at', '>', now()->subDay())->where('trial', false);
        })->get();

        // whereBetween
        $mediumDrinks = DB::table('contacts')->whereBetween('size', [6,12])->get();

        // whereIn, whereNotIn, whereNull, whereNotNull
        DB::table('contacts')->whereIn('state', ['FL','GA','EL'])->get();

        // whereRaw() 이스케이프 처리하지 않음
        DB::table('contacts')->whereRaw('id = 12345')->get();

        // whereExist
        DB::table('users')->whereExists(function ($query) {
            $query->select('id')->from('comments')->whereRaw('comments.user_id = users.id');
        })->get();

        // distinct
        DB::table('contacts')->select('city')->distinct()->get();

        DB::table('contacts')->orderBy('last_name', 'asc');

        // group by, having ,havingRaw
        DB::table('contacts')->groupBy('city')->having('count(contact_id) > 30')->get();

        $page4 = DB::table('contacts')->skip(30)->take(10)->get();

        //latest, oldset, inRandomOrder

        // 조건에 따라 쿼리를 추가하는 메서드 when
        // 조건이 거짓일때 세번째 파라미터 실행
        $status = request('status');
        $post = DB::table('posts')->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        $post = DB::table('posts')->when($ignoreDrafts, function ($query) use ($status) {
            return $query->where('draft', false);
        })->get();

        // get() 생성한 쿼리를 실행하고 결과를 반환한다
        // first, firstOrFail(결과가 없을때 예외를 발생)
        DB::table('contacts')->orderBy('created_at', 'desc')->first(); // limit 1

        // find, findOrFail
        DB::table('contacts')->find(5);

        // value 하나의 컬럼값만 출력
        DB::table('contacts')->orderBy('created_at', 'desc')->value('email');

        // count
        DB::table('contacts')->where('vip',true)->count();
        // max, min
        DB::table('orders')->where('vip',true)->max('amount');
        // sum, avg
        DB::table('orders')->where('vip',true)->avg('amount');

        // dd(종료), dump
        DB::table('users')->where('name', 'Willbur Powery')->dd();

        // 원시 쿼리 작성
        DB::table('contacts')->select(DB::raw('*, (score * 100) AS integer_score'))->get();

    }

    function test3()
    {
        // 조인 쿼리
        DB::table('users')->join('contacts', 'user.id', '=', 'contacts.user_id')
            ->select('users.*', 'contacts.name', 'contacts.status')->get();

        DB::table('users')->join('contacts', function ($join) {
            $join->on('users.id', '=', 'contacts.user_id')
                ->orOn('users.id', '=', 'contacts.proxy_user_id');
        })->get();

        // 유니온 쿼리
        $first = DB::table('contacts')->whereNull('first_name');
        DB::table('contacts')->whereNull('last_name')
            ->union($first)->get();

        // 인서트 쿼리
        // 자동생성된 기본키 반환
        $id = DB::table('contacts')->insertGetId([
            'name' => 'Abe Thomas',
            'email' => 'athomas1987@gmail.com'
        ]);

        DB::table('contacts')->insert([
            ['name' => 'Tamiak Jonhson', 'email' => 'tamiak@gmail.com'],
            ['name' => 'jin Parttjer', 'email' => 'james.pateer@gmail.com']
        ]);

        // 업데이트 쿼리
        DB::table('contacts')->where('points', '>', 100)
            ->update(['status' => 'vip']);

        // 칼럼값을 증가, 감소
        DB::table('contacts')->increment('token', 5);
        DB::table('contacts')->decrement('tokens');

        // 삭제 쿼리
        DB::table('users')->where('last_login', '<', now()->subYear())->delete();

        // JSON 연산
        // options JSON 컬럼의 isAdmin 속성이 true인 모든 레코드 조회
        DB::table('users')->where('options->isAdmin', true)->get();
        // options JSON 컬럼의 verified 속성을 true로 변경
        DB::table('users')->update(['options->isVerified', true]);

        // 트랜잭션
        DB::transaction(function () use ($userId, $numVotes) {
            // 실패할 가능성이 있는 DB 쿼리
            DB::table('users')->where('id', $userId)->update(['votes' => $numVotes]);

            // 위의 쿼리가 실패하면 실행되지 않는 쿼리
            DB::table('votes')->where('user_id', $userId)->delete();
        });

        DB::beginTransaction();
        // 데이터베이스 작업 수행
        if ($badThingsHappend) {
            DB::rollBack();
        }
        // 다른 데이터베이스 작업 수행
        DB::commit();





    }
}
