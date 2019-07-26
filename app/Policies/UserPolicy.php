<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

// 用户策略类
class UserPolicy
{
    use HandlesAuthorization;

    // 用户只能编辑自己的资料 第一个参数默认为当前登录用户实例，第二个参数则为要进行授权的用户实例
    public function update(User $currentUser, User $user)
    {
        return $currentUser->id === $user->id;
    }

    // 用户不能自个删除自己
    public function destroy(User $currentUser, User $user)
    {
        // 只有当前用户拥有管理员权限且删除的用户不是自己时才显示链接
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    }

}
