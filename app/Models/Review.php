<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Review extends Model
{
    use HasFactory;
    use Searchable; // 스카우터 적용

    // 조건에 따라 모델 색인처리
    public function shouldBeSearchable()
    {
        return $this->isApprovied();
    }
}
