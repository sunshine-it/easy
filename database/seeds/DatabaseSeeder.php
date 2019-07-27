<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(UsersTableSeeder::class); // 注册填充用户种子
        $this->call(StatusesTableSeeder::class); // 注册填充用户发微博的种子

        Model::reguard();
    }
}
