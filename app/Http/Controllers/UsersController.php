<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// 用户类
class UsersController extends Controller
{
    // 注册
    public function create()
    {
        return view('users.create');
    }
}
