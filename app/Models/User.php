<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 일대다 연관관계
    /*public function contacts()
    {
        return $this->hasMany(Contact::class);
    }*/

    // 다대다 연관관계 정의
    public function contacts()
    {
        // return $this->belongsToMany(Contact::class);

        // 피벗 테이블에 필드 추가
        return $this->belongsToMany(Contact::class)
            ->withTimestamps() // 피벗테이블에서 created_at, updated_at 사용
            ->withPivot('status', 'preferred_greeting') // 피벗테이블에 사용할 필드
            ->as('member_ship'); // pivot 속성명을 member_ship 으로 변경
    }

    // 연결을 통한 다수 연관관계 정의
    public function phoneNumbers()
    {
        // 연관관계의 연관관계에서 데이터를 가져옴
        return $this->hasManyThrough(PhoneNumber::class, Contact::class);
    }

    // 연결을 통한 단일 연관관계 정의
    public function phoneNumber()
    {
        // 연관관계의 연관관계에서 데이터를 가져옴
        return $this->hasOneThrough(PhoneNumber::class, Contact::class);
    }

    // 다형성 연관관계를 사용자에게 연결하도록 확장
    public function stars()
    {
        return $this->hasMany(Star::class);
    }

}
