<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

// 用户类
class UsersController extends Controller
{
    // 注册
    public function create()
    {
        return view('users.create');
    }

    // 显示用户的信息
    public function show(User $user)
    {
        // dd($user);
        // dd(compact('user'));
        return view('users.show', compact('user'));
    }
}
