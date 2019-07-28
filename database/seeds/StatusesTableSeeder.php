<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Status;

// 发布微博种子类
class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 指定用户发布微博
        $user_ids = ['1', '2', '3', '4'];
        $faker = app(Faker\Generator::class);
        // 生成一百条微博
        $statuses = factory(Status::class)->times(100)->make()->each(function ($status) use ($faker, $user_ids) {
            $status->user_id = $faker->randomElement($user_ids);
        });
        Status::insert($statuses->toArray());
    }
}
