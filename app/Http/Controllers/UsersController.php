<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

// 用户类
class UsersController extends Controller
{
    // 构造器方法，当一个类对象被创建之前该方法将会被调用
    public function __construct()
    {
        // 使用中间件 | 用于过滤未登录用户的 edit, update 动作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail'],
        ]);
        // 只让未登录用户访问注册页面
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 用户列表
    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

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
            'name'     => 'required|max:50',
            'email'    => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6' // 保证两次输入的密码一致，可以使用 confirmed 来进行密码匹配验证。
        ]);
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        // 注册后自动登录
        // Auth::login($user);
        // 激活邮箱的发送操作
        $this->sendEmailConfirmationTo($user);
        // 定义回话闪存，作为消息提示
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        // return redirect()->route('users.show', [$user]);
        return redirect('/');
    }

    // 邮件发送方法
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'pazmnew@example.com';
        $name = '一号';
        $to = $user->email;
        $subject = "感谢注册 Easy 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    // 邮件激活方法
    public function confirmEmail($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();
        // 激活状态改为 true
        $user->activated = true;
        // 激活令牌设置为空
        $user->activation_token = null;
        $user->save();
        // 激活后自动登录
        Auth::login($user);
        // 定义回话闪存，作为消息提示
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    // 用户编辑
    public function edit(User $user)
    {
        // 授权策略 app/Policies/UserPolicy 中的 update 方法
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    // 用户更新
    public function update(User $user, Request $request)
    {
        // 授权策略 app/Policies/UserPolicy 中的 update 方法
        $this->authorize('update', $user);
        // 验证规则
        $this->validate($request, [
            'name'     => 'required|max:50',
            'password' => 'nullable|confirmed|min:6',
        ]);
        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = bcrypt($request->password);
        }
        $user->update($data);
        session()->flash('success', '个人资料更新成功！');
        return redirect()->route('users.show', $user->id);
    }

    // 删除用户
    public function destroy(User $user)
    {
        // 授权策略 app/Policies/UserPolicy 中的 destroy 方法
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
