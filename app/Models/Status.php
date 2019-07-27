<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// 微博模型
class Status extends Model
{
    protected $fillable = ['content'];

    // 一条微博属于一个用户
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
