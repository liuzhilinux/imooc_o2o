<?php
namespace app\common\validate;
use think\Validate;

class Featured extends Validate
{
	protected $rule = [
		'title' => 'require',
		'image' => 'require',
		'type' => 'require|number',
		'url' => 'require',
		'description' => 'require',
	];

	protected $message = [
		'title.require' => '标题必须填写！',
		'image.require' => '推荐图必须上传！',
		'type.require' => '所属分类必须选择！',
		'type.number' => '请求非法！',
		'url.require' => 'URL必须填写！',
		'description.require' => '描述必须填写！',
	];

	protected $scene = [
		'add' => ['title', 'image', 'type', 'url', 'description'],
	];
}