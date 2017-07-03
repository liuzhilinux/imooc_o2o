<?php
return [
    // 定义test模块的自动生成
    'admin' => [
        '__dir__'    => ['controller', 'model', 'view'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => ['index/index'],
    ],
    'api' => [
        '__dir__'    => ['controller', 'model', 'validate','view'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => ['index/index'],
    ],
    'bis' => [
        '__dir__'    => ['controller', 'model', 'validate', 'view'],
        'controller' => ['Index'],
        'model'      => [],
        'view'       => ['index/index'],
    ],

    'common'     => [
        '__file__'   => ['common.php'],
        '__dir__'    => ['validate','model',],
        'controller' => [],
        'model'      => [],
        'view'       => [],
    ],


];
