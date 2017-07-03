<?php
namespace app\common\validate;

use think\Validate;

class Category extends Validate
{
	protected $rule = [
		'id' => 'number|min:0',
		'name' => 'require|length:1,50',
		'parent_id' => 'number|min:0',
		'listorder' => 'number|min:0',
		'status' => 'in:0,1',
	];

	protected $message = [
		'id.number' => 'ID应为数字！',
		'id.min' => 'ID必须大于0！',
		'name.require' => '分类名称必须填写！',
		'name.length' =>'分类名长度必须在1到50之间！',
		'parent_id' => '分类名非法！',
		'listorder.number' => '排序序号必须为数字！',
		'listorder.min' => '排序序号必须大于0！',
		'status' => '状态数据不合法',
	];

	protected $scene = [
		'add' =>['name','parent_id'],
		'edit' =>['id','parent_id','name'],
		'listorder' =>['listorder','id'],
		'delete' => ['id'],
		'changStatus' =>['id', 'status'],
	];
}