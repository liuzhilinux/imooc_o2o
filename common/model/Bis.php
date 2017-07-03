<?php
namespace app\common\model;
use think\Db;

class Bis extends BisBase
{
	// 设置表名
	protected $name = 'bis';


	/**
	* 获取商户列表
	*/

	public function getBisList($status = [0,1]){
		$where = [
			'status' => is_array($status) ? ['in', $status] : $status,
		];
		$order = [
			'id' => 'desc',
		];
		return $this->where($where)->order($order)->paginate(12);
	}

	/**
	* 改变状态
	*/

	public function changeStatus($id, $status){
		$where = [
			'id' => $id,
		];
		$data = [
			'status' => $status,
		];
		return $this->where($where)->update($data);
	}
}