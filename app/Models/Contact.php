<?php

namespace App\Models;

use App\Models\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;
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

    protected static function boot()
    {
        parent::boot();

        // 클로저를 사용한 글로벌 스코프
        /*static::addGlobalScope('statusGlobal', function (Builder $builder) {
            $builder->where('status', 'yes');
        });*/
        static::addGlobalScope(new ActiveScope);

    }

    // 모델에 로컬 스코프 정의하기
    public function scopeActiveVips($query)
    {
        return $query->where('vip', true)->where('trial', false);
    }

    // 인자를 요하는 스코프 메서드 정의
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

}
