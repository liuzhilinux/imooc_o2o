<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::rule('login','index/user/login');
Route::rule('register','index/user/register');
Route::rule('lists','index/lists/index');
Route::rule('detail','index/detail/index');
Route::rule('order/confirm','index/order/confirm');
Route::rule('order/index','index/order/index');
Route::rule('pay/index','index/pay/index');
Route::rule('admin/login', 'admin/index/login');
Route::rule('admin/register', 'admin/index/register');
Route::rule('bis/waiting', 'bis/index/waiting');
Route::rule('bis/logout', 'bis/index/logout');



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
];
