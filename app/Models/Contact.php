<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes; // 소프트 삭제시 트레이드 사용

    // 테이블명을 명시적으로 지정시 (기본 class명의 복수형)
    // protected $table = 'contacts';

    // 기본키의 이름이 다른경우 (기본 id)
    // protected $primaryKey = 'contact_id';

    // 자동 증가 방지
    // public $incrementing = false;

    // created_at, updated_at 컬럼이 없다면 비활성화
    // public $timestamps = false;

    // 타임스탬프 값을 데이터베이스에 저장하는 포맷을 지정
    // protected $dateFormat = 'U';

    // 대량 할당
    protected $fillable = ['name', 'email']; //해당필드 허용
    protected $guarded = ['id', 'created_at', 'update_at', 'owner_id']; // 해당필드 불가

    // 소프트 삭제시 사용
    protected $dates = ['deleted_at']; // 이 컬럼이 date 포맷이라는 것을 말한다
}
