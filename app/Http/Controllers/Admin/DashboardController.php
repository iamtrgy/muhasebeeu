<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the registered users list.
     */
    public function registered()
    {
        return view('admin.users');
    }
} 