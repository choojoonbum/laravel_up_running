<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
/*
        Schema::create('test', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->nullable()->after('last_name');
        });

        // composer require doctrine/dbal 필드 수정시 의존성 추가
        Schema::create('test', function (Blueprint $table) {
            $table->string('name', 100)->change();
            $table->string('delete_at')->nullable()->change();

            // 컬럼 변경
            $table->renameColumn('promoted', 'is_promoted');
            // 컬럼을 동시에 여러개 변경시 Schema::create() 메서드를 여러번 호출
            $table->dropColumn('votes');
        });

        // 컬럼에 인덱스 추가
        Schema::create('test', function (Blueprint $table) {
            // 컬럼이 생성된 뒤에 수행한다.
            $table->primary('primary_id'); // 기본키, increment()를 사용시 불필요
            $table->primary(['First_name', 'last_name']); // 복합키를 사용하는 경우
            $table->unique('email'); // 유니크 인덱스 지정
            $table->unique('email', 'optional_custom_index_name'); // 이름을 지정한 유니크 인덱스
            $table->index('amount'); // 기본적인 인덱스
            $table->index('amount', 'optional_custom_index_name'); // 이름을 지정한 기본적인 인덱스
        });

        // 인덱스 삭제하기
        Schema::create('test', function (Blueprint $table) {
            $table->dropPrimary('custom_primary_id');
            $table->dropUnique('optional_custom_index_name');
            $table->dropIndex('optional_custom_index_name');

            // dropIndex 메서드에 컬럼명을 배열로 전달시 생성 규칙을 기준으로 인덱스 이름을 추정
            $table->dropIndex(['email', 'amount']);
        });

        // 외래키 추가 및 삭제
        Schema::create('test', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            // 라라벨7 이상버전 사용 가능
            $table->foreignId('user_id')->constrained();

            // 외래키 제약 지정
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 외래키 삭제
            $table->dropForeign('contacts_user_id_foreign');
            $table->dropForeign(['user_id']);
        });
*/

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
