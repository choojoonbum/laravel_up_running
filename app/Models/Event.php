<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name'
    ];

    // 다형성 연관관계 모델 정의
    public function stars()
    {
        return $this->morphMany(Star::class, 'starrable');
    }

    // 다대다 다형성
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
