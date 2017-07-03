<?php
namespace app\common\validate;

use think\Validate;

class City extends Validate
{
	protected $rule = [
		'id' => 'number|min:0',
		'name' => 'require|length:1,50',
		'uname' => 'require|length:1,50',
		'parent_id' => 'number|min:0',
		'listorder' => 'number|min:0',
		'status' => 'in:0,1',
	];

	protected $message = [
		'id.number' => 'ID应为数字！',
		'id.min' => 'ID必须大于0！',
		'name.require' => '城市名称必须填写！',
		'name.length' =>'城市名称长度必须在1到50之间！',
		'uname.require' => '城市英文名必须填写！',
		'uname.length' =>'城市英文名长度必须在1到50之间！',
		'parent_id' => '父级城市非法！',
		'listorder.number' => '排序序号必须为数字！',
		'listorder.min' => '排序序号必须大于0！',
		'status' => '状态数据不合法',
	];

	protected $scene = [
		'save' =>['name', 'uname', 'parent_id'],
		'delete' => ['id'],
		'edit' =>['id'],
		'update' =>['id','parent_id','name', 'uname'],
		'listorder' =>['listorder','id'],
	];
}