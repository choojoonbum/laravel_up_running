<?php

namespace App\Http\Controllers;

use App\Http\Responses\GroupDonationDashboard;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return new GroupDonationDashboard('group1');
    }
}
