imooc_o2o
============

本人的一个实战项目，根据[幕课网](http://www.imooc.com/)的付费实战课程[Thinkphp 5.0实战 仿百度糯米开发多商家电商平台](http://coding.imooc.com/learn/list/85.html)课程指导最终完成实战项目并部署到个人空间。其中个人项目[liuzhilinux/imooc_o2o](https://github.com/liuzhilinux/imooc_o2o)中的各个模块及`extend`目录下的扩展均由本人实现。

## 演示

* [http://thinkphpo2o.t.imooc.io](http://thinkphpo2o.t.imooc.io)
  课程演示地址
* [http://o2o.liuzhiling.com/](http://o2o.liuzhiling.com/)
  前台首页，已有注册用户`admin`，密码`toor`。
* [http://o2o.liuzhiling.com/admin](http://o2o.liuzhiling.com/admin)
  总后台首页，没有设计用户登入/注册模块。
* [http://o2o.liuzhiling.com/bis/login.html](http://o2o.liuzhiling.com/bis/login.html)
  商户后台登入页面，已有注册用户`admin`，密码`toor`。

## 第三方 API 、框架和组件的使用

* [百度地图](http://lbsyun.baidu.com/)(`extend/com/liuzhiling/Map.php`)
* [百度翻译](http://api.fanyi.baidu.com/api/trans/product/index)
* [Uploadify](http://www.uploadify.com/)(`public/static/admin/uploadify/Mail.php`)
* [PHPMailer](https://github.com/PHPMailer/PHPMailer)(`extend/com/liuzhiling/`)

##### 声明：
> 其余小功能的扩展均包含在`application/common.php`、`public/static/admin/js/common.js`和`public/static/index/js/common.js`中。微信支付模块待实现。

## 项目目录结构

* `application`: 主项目文件。
* `extend/com/liuzhiling/`: 配合主项目使用的扩展文件。
* `public`: 主入口文件，包含一些静态的 JS 、 CSS 文件及图片上传存放目录。
* `o2o_sql.sql`: 项目部署后建立数据表结构。

## 部署方案

部署环境以树莓派为例，系统为Raspbian。

1. 搭建运行环境

```
# 更新源
$ sudo apt-get update
# 搭建 LAMP 环境
$ sudo apt-get install apache2
$ sudo apt-get install php5
$ sudo apt-get install php5-mysql
$ sudo apt-get install php5-gd
$ sudo apt-get install mysql-server
$ sudo apt-get install mysql-client
$ sudo apt-get install libapache2-mod-php5
# 建立非公共目录
$ sudo mkdir /var/www/private
# 修改目录读写权限
$ sudo chmod 777 /var/www/
 ```
 
 2. 安装 Composer 和 Git
 
 ```
# 安装 Composer
$ sudo curl -sS https://getcomposer.org/installer | sudo php -d detect_unicode=Off
# 切换到全局安装文件夹
$ sudo mv composer.phar /usr/local/bin/composer
# 修改文件权限
$ cd /user/local/bin/
$ sudo chmod a+x composer
# 配置国内镜像
$ composer config -g repo.packagist composer https://packagist.phpcomposer.com
# 安装Git
$ sudo apt-get install git
```

3. 部署ThinkPHP5

> 采用项目目录.框架目录和访问入口目录相隔离的方式.
##### TP5 的环境要求:
> - PHP >= 5.4.0 
> - PDO PHP Extension
> - MBstring PHP Extension
> - CURL PHP Extension

```
$ cd /var/www/private/
$ composer create-project topthink/think tp5  --prefer-dist
# 添加验证码扩展组件
$ composer require topthink/think-captcha
# 添加 PHPMailer 模块
$ composer require phpmailer/phpmailer
```

4. 部署 imooc_o2o

```
$ cd /var/www/private/
$ sudo git clone https://github.com/liuzhilinux/imooc_o2o.git
# 移动 imooc_o2o/public 文件夹中的文件到 /var/www/html 下
$ sudo rm -r /var/www/html/
$ sudo mv /var/www/private/imooc_o2o/public /var/www/html
# 移动 imooc_o2o/extend 文件夹中的文件到 /var/www/private/tp5 下
$ sudo mv /var/www/private/imooc_o2o/extend /var/www/private/tp5
# 移动 imooc_o2o/application 覆盖 /var/www/private/imooc_o2o
$ sudo mv /var/www/private/imooc_o2o/application /var/www/private
  && sudo rm -r /var/www/private/imooc_o2o && sudo rename 
```

5. 根据目录结构修改 `/var/www/html/index.php`

```php
// 定义应用目录
define('APP_PATH', __DIR__ . '/../private/imooc_o2o/');
// 定义扩展目录
defined('EXTEND_PATH') or define('EXTEND_PATH', __DIR__ . '/../private/tp5/extend/');
defined('VENDOR_PATH') or define('VENDOR_PATH', __DIR__ . '/../private/tp5/vendor/');

// 加载框架引导文件
require __DIR__ . '/../private/tp5/thinkphp/start.php';
```

6. 根据自己的情况添加百度 API 和邮件相关配置到`/var/www/private/imooc_o2o/config.php`

```php
// 公共上传图片资源目录
'public_upload_image_url' => '/',

// 视图输出字符串内容替换
'view_replace_str'       => [
    // 静态资源存放目录
   '_STATIC_' => '/static/',
],

// +----------------------------------------------------------------------
// | 邮件发送配置
// +----------------------------------------------------------------------
'mail' =>[
    // 可以使用其他的邮箱服务
    'host' => 'smtp.163.com',
    'username' => 'username@163.com',
    'password' => 'password',
    'port' => 25,
],
'my_email' => 'username@163.com', // 一般情况与上面配置的 username 相同
'my_name' => 'yourname',

// +----------------------------------------------------------------------
// | 百度地图相关配置
// +----------------------------------------------------------------------
'map' =>[
    'api_domain' => 'http://api.map.baidu.com/',
    'ak' => 'E4805d16520de693a3fe707cdc962045',
    'geocoder' => 'geocoder',
    'geocoder_version' => 'v2',
    'staticimage' => 'staticimage',
    'staticimage_version' => 'v2',
    'width' => 400,
    'height' => 300,
],

// +----------------------------------------------------------------------
// | 百度翻译相关配置
// +----------------------------------------------------------------------
'fanyi' => [
    'api' => 'http://api.fanyi.baidu.com/api/trans/vip/translate',
    'appid' => '2015063000000001',
    'key' => 'abDFabDFabDFabDF',
    'salt' => '65001', // 加盐随机值
],

'fanyi_lang' => [
    'zh' => 'zh',
    'en' => 'en',
    'auto' => 'audo',
    'spa' => 'spa',
],

// +----------------------------------------------------------------------
// | 广告位文本相关配置
// +----------------------------------------------------------------------
'featured_type'=>[
    // 广告位类型
    0 => '首页大图推荐位',
    1 => '首页右侧广告位',
],
````

7. 根据自己的数据库配置修改 `/var/www/private/imooc_o2o/database.php`并添加下面一行代码.

```php
    // 关闭自动格式化输出时间戳
    'datetime_format' => false,
```

8. 添加新数据库,根据 `imooc_o2o/o2o_sql.sql`

9. 填充数据

至此,可以访问网站了,大功告成.
