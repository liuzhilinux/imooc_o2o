<?php
namespace app\common\model;
use think\Model;

class Order extends Model
{
	// 设置表名
	protected $name = 'order';

	public function add($data)
	{
		$data['status'] = 0;
		$this->save($data);
		return $this->id;
	}

}
