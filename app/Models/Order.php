<?php

namespace App\Models;

use App\Models\Collections\OrderCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // 엘로퀸트에서 컬랙션을 상속한 커스텀 컬랙션 등록
    public function newCollection(array $models = [])
    {
        return new OrderCollection($models);
    }
}
