<?php
namespace app\common\model;

class BisLocation extends BisBase
{
	// 设置表名
	protected $name = 'bis_location';

	public function getLocationList($bis_id, $status = [0,1]){
		$where = [
			'bis_id' => $bis_id,
			'status' => is_array($status) ? ['in', $status] : $status,
		];
		$order = [
			'id' => 'desc',
		];
		return $this->where($where)->order($order)->paginate(12);
	}

}