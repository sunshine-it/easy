<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

// 微博关注操作类
class FollowersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 关注
    public function store(User $user)
    {
        // 授权策略 app/Policies/UserPolicy 中的 follow 方法
        $this->authorize('follow', $user);
        if (! Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }

    // 取消关注
    public function destroy(User $user)
    {
        // 授权策略 app/Policies/UserPolicy 中的 follow 方法
        $this->authorize('follow', $user);
        if (Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }
        return redirect()->route('users.show', $user->id);
    }
}
