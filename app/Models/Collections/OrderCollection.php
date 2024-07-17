<?php

namespace App\Models\Collections;

use Illuminate\Database\Eloquent\Collection;

// 엘로퀸드 컬랙션을 상속한 커스텀 컬렉션 정의
class OrderCollection extends Collection
{
    public function sumBillableAmount()
    {
        return $this->reduce(function ($carry, $order) {
            return $carry + ($order->billable ? $order->amount : 0);
        },0);
    }

}
