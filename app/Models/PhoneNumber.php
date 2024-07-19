<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    use HasFactory;

    protected $fillable = ['phone_number'];

    // 하위모델이 변경될 때 상위 모델 레코드의 타임스템프 값을 갱신하도록 정의하기
    protected $touches = ['contact'];

    // 일대일 역방향 연관관계
    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
