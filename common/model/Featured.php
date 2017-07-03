<?php
namespace app\common\model;
use think\Model;
class Featured extends Model
{
	// 设置表名
	protected $name = 'featured';

	public function addFeatured($data)
	{
		return $this->save($data);		
	}

	public function getFeaturedsByType($type = null)
	{
		$where = ['status' => ['neq', -1]];
		if(!is_null($type)){
			$where['type'] = $type;
		}
		return $this->where($where)->order(['id' => 'desc'])->paginate(12);
	}

	public function getFeaturedListByType($type = null)
	{
		$where = ['status' => ['neq', -1]];
		if(!is_null($type)){
			$where['type'] = $type;
		}
		return $this->where($where)->order(['id' => 'desc'])->select();
	}

	public function getFeatured($id)
	{
		$where = [
			'id' => $id, 
			'status' => ['neq', -1]
		];
		return $this->where($where)->order(['id' => 'desc'])->find();
	}

}