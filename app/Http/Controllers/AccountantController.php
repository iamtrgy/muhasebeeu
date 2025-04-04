<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AccountantController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'accountant']);
    }
    
    /**
     * Redirect to the accountant dashboard
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dashboard()
    {
        return Redirect::route('accountant.dashboard');
    }
}
