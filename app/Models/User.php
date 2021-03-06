<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Auth;

// 用户模型
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 指明一个用户拥有多条微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    // 用户模型监听器
    public static function boot()
    {
        parent::boot();
        static::creating(function ($user) {
            $user->activation_token = Str::random(10);
        });
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // 用户头像
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->attributes['email'])));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // feed 方法获取当前用户关注的人发布过的所有微博动态 简称 动态流
    public function feed()
    {
        $user_ids = $this->followings->pluck('id')->toArray();
        // 将当前用户的 id 加入到 user_ids 数组中
        array_push($user_ids, $this->id);
        // 使用查询构造器 whereIn 方法取出所有用户的微博动态并进行倒序排序，使用预加载 with 方法，预加载避免了 N+1 查找的问题
        return Status::whereIn('user_id', $user_ids)->with('user')->orderBy('created_at', 'desc');
    }

    // 获取粉丝关系列表
    public function followers()
    {
        // 一个用户能够拥有多个粉丝 多对多关联
        return $this->belongsToMany(User::Class, 'followers', 'user_id', 'follower_id');
    }

    // 获取用户关注人列表
    public function followings()
    {
        // 多对多关联
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    // 关注
    public function follow($user_ids)
    {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids, false);
    }

    // 取消关注
    public function unfollow($user_ids)
    {
        if ( ! is_array($user_ids)) {
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    // 判断关注
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
