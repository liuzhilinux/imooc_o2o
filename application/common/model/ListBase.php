<?php

namespace app\common\model;

use think\Model;

class ListBase extends Model
{
	// 自动写入时间戳
	protected $autoWriteTimestamp = true;
	// 设置表名
	protected $name = 'category';

	/**
	* 获得 id->name 的对照列表
	*/
	public function getNameMap(){
		$all = $this->where(['status' => 1])->select();
		$res = [];

		foreach ($all as $item) {
			if($item)
				$res[$item->data['id']] = $item->data['name'];
		}
		return $res;
	}

}
