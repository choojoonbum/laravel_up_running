<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

//  뷰로 전달되는 데이터를 분리해서 처리하므로써 코드의 가독성이 개선되는 효과가 있다.
class GroupDonationDashboard implements Responsable
{

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function budgetThisYear()
    {
        //
    }

    public function giftsThisYear()
    {
        //
    }

    public function toResponse($request)
    {
        return view('group.dashboard')
            ->with('annual_budget', $this->budgetThisYear())
            ->with('annual_gifts_received', $this->giftsThisYear());
    }
}
