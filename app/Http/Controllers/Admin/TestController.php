<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return view('admin.test');
    }
    
    public function settings()
    {
        return view('profile.edit', [
            'isAdmin' => true
        ]);
    }
}
