<?php

namespace App\Http\Controllers\UserAccounts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAccountsController extends Controller
{
    public function index()
    {
        return view('content.UserAccounts.index');
    }

    public function create()
    {
        return view('auth.register');
    }
}
