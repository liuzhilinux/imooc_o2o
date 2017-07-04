imooc_o2o
============

本人的一个实战项目，根据[幕课网](http://www.imooc.com/)的付费实战课程[Thinkphp 5.0实战 仿百度糯米开发多商家电商平台](http://coding.imooc.com/learn/list/85.html),根据课程指导最终完成实战项目并部署到个人空间。其中个人项目[liuzhilinux/imooc_o2o](https://github.com/liuzhilinux/imooc_o2o)中的各个模块及`extend`目录下的扩展均由本人实现，因本人系第一个项目，故代码质量有很大地方需要改进，请多包涵。

## 演示：

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
> 其余小功能的扩展均包含在`application/common.php`、`public/static/admin/js/common.js`和`public/static/index/js/common.js`中。课程要求的微信支付模块，因本人未能申请成功企业号，故没能实现微信支付模块。

## 项目目录结构

* `application`: 主项目文件。
* `extend/com/liuzhiling/`: 配合主项目使用的扩展文件。
* `public`: 主入口文件，包含一些静态的 JS 、 CSS 文件及图片上传存放目录。
* `o2o_sql.sql`: 项目部署后建立数据表结构。




