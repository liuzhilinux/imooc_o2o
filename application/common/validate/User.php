<?php
namespace app\common\validate;
use think\Validate;

class User extends Validate
{
	protected $rule = [
		'username' => 'require|length:4,50',
		'password' => 'require|min:4',
		'repassword' => 'require',
		'email' => 'require|email',

	];

	protected $message = [
		'username.require' => '用户名必须填写！',
		'username.length' => '用户名长度非法！',
		'email.require' => '邮箱号必须填写！',
		'email.email' => '邮箱不合法！',
		'password.require' => '密码必须填写！',
		'password.min' => '为了安全性考虑，密码长度必须大于6位！',
		'repassword' => '请确认密码！',
	];

	protected $scene = [
		'add' => ['username', 'password', 'repassword', 'email'],
		'check' => ['username' => 'require', 'password' => 'require'],
	];
}