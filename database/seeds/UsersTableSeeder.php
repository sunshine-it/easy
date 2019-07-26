<?php

use Illuminate\Database\Seeder;
use App\Models\User;

// 用户数据种子类
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 生成假用户
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        $user = User::find(1);
        $user->name = '一号';
        $user->email = 'pazmnew@163.com';
        $user->is_admin = true; // 设置为管理员
        $user->save();
    }
}
