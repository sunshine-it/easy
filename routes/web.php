<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', 'StaticPagesController@home')->name('home');
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

// 注册
Route::get('signup', 'UsersController@create')->name('signup');
// 用户资源路由
Route::resource('users', 'UsersController');
// 访问登录页面，输入账号密码点击登录路由；
Route::get('login', 'SessionsController@create')->name('login');
// 服务器对用户身份进行认证，认证通过后，记录登录状态并进行页面重定向；
Route::post('login', 'SessionsController@store')->name('login');
// 登录成功后的用户，能够使用退出按钮来销毁当前登录状态；
Route::delete('logout', 'SessionsController@destroy')->name('logout');
// 邮箱激活
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');
// 密码重置
// 显示重置密码的邮箱发送页面
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// 邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// 密码更新页面
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// 执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
