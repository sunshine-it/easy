<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    // 注册与登录页面访问限制
    public function __construct()
    {
        // 只让未登录用户访问登录页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 显示登录页面
    public function create()
    {
        return view('sessions.create');
    }

    // 创建新会话（登录）
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email'     => 'required|email|max:255',
            'password'  => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember'))) {
            // 登录成功后的相关操作
            session()->flash('success', '欢迎回来！');
            $fallback = route('users.show', [Auth::user()]);
            // 友好的转向 intended
            return redirect()->intended($fallback);
        }
        else {
            // 登录失败后的相关操作
            // 使用闪存来展示提醒信息
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
        return ;
    }

    // 销毁会话（退出登录）
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}
