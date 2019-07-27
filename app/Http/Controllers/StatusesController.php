<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
use Auth;

class StatusesController extends Controller
{
    // 构造函数
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 处理创建微博的请求
    public function store(Request $request)
    {
        // 校验规则
        $this->validate($request, [
            'content' => 'required|max:140',
        ]);
        // 创建微博
        Auth::user()->statuses()->create([
            'content' => $request['content'],
        ]);
        session()->flash('success', '发布成功！');
        return redirect()->back();
    }

    // 处理删除微博的请求
    public function destroy()
    {}
}
