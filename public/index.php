<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../../private/imooc_o2o/');
// 定义扩展目录
defined('EXTEND_PATH') or define('EXTEND_PATH', __DIR__ . '/../../private/tp5/extend/');
defined('VENDOR_PATH') or define('VENDOR_PATH', __DIR__ . '/../../private/tp5/vendor/');


// 加载框架引导文件
require __DIR__ . '/../../private/tp5/thinkphp/start.php';
