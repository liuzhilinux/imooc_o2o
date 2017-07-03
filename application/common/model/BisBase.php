<?php
namespace app\common\model;
use think\Model;
use think\Db;

class BisBase extends Model
{
	// 自动写入时间戳
	protected $autoWriteTimestamp = true;
	// 设置表名

	public function add($data){
		return $this->save($data) ? intval($this->id) : false;
	}

	/**
	* 改变状态
	*/
	public function changeStatus($id, $status){
		$where = [
			'bis_id' => $id,
		];
		$data = [
			'status' => $status,
		];
		return $this->where($where)->update($data);
	}

	public function getListByLimit($limit = 10, $where = [])
	{
		$where['status'] = ['in', [0,1]];
		$order = [
			'id' => 'desc',
		];
		if($limit){
			$this->limit($limit);
		}
		return $this->where($where)->order($order)->select();
	}

}