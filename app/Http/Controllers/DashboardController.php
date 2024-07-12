<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin-auth')->only('editUsers');
        $this->middleware('team-member')->except('editUsers');
    }
}
