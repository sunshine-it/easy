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
        return view('users.show', compact('user'));
    }

    // 处理用户注册信息
    public function store(Request $request)
    {
        // 验证规则
        $this->validate($request, [
            'name'      => 'required|max:50',
            'email'     => 'required|email|unique:users|max:255',
            'password'  => 'required|confirmed|min:6' // 保证两次输入的密码一致，可以使用 confirmed 来进行密码匹配验证。
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // 定义回话闪存，作为消息提示
        session()->flash('success', '欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show', [$user]);
    }
}
