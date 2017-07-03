<?php

namespace app\common\model;


class City extends ListBase
{
	// 设置表名
	protected $name = 'city';

	public function add($data)
	{
		$data['status'] = 1;
		return $this->save($data);
	}

	public function edit($data)
	{
		$where=[
			'id' =>$data['id'],
		];
		return $this->where($where)->update($data);
	}


	/**
	* 获取多级列表。
	*/

	public function getCityList($parent_id = 0)
	{
		$where = [ 
			'parent_id' => $parent_id,
			// 排除被删除掉的分类
			'status' => ['in','0,1'],
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'asc',
		];
		return $this->where($where)->order($order)->paginate(12);
	}


	/**
	* 获取单级列表。
	*/

	public function getCityListByParent($parent_id = 0)
	{
		$where = [ 
			'parent_id' => $parent_id,
			// 排除被删除掉的分类
			'status' => ['in','0,1'],
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'asc',
		];
		return $this->where($where)->order($order)->select();
	}


	
	/**
	* 获取城市树结构。
	*/
	public function getCityTree($parent_id = 0)
	{
		$res = [];
		// 查询出同一 parent_id 的城市
		$where = [
			'parent_id' => $parent_id,
			// 排除被删除掉的分类
			'status' => 1,
		];
		$data = $this->where($where)->select();
		// 重新组织数据
		foreach($data as $obj){
			$item = $obj->data;
			$id = $item['id'];
			$name = $item['name'];
			// 递归求取下一级城市树结构
			$sub_citys = $this->getCityTree($id);

			// 数据打包
			$res[$id] = ['name' => $name, 'sub_citys'=>$sub_citys];
		}

		return $res;
	}

	public function getCityTree_2($parent_id = 0)
	{
		$res = [];
		// 查询出同一 parent_id 的城市
		$where = [
			'parent_id' => $parent_id,
			// 排除被删除掉的分类
			'status' => 1,
		];
		$data = $this->where($where)->select();
		// 重新组织数据
		foreach($data as $obj){
			$item = $obj->data;
			$id = $item['id'];
			$name = $item['name'];
			// 数据打包
			$res[$id] = $name;
		}

		return $res;
	}


	
	/**
	* 获取城市信息。
	*/
	public function getCityInfo($id = 0)
	{
		$where = [
			'id' => $id,
		];

		return $this->where($where)->find();
	}


	/**
	* 根据uname获取城市信息。
	*/
	public function getCityByUname($uname = 'Beijing City')
	{
		$where = [
			'uname' => $uname,
		];

		return $this->where($where)->find();
	}

	/**
	* 修改排序序号。
	*/
	public function changeListorder($id, $listorder)
	{
		$where = [
			'id' => $id,
		];
		$data = [
			'listorder' => $listorder,
		];
		return $this->where($where)->update($data);
	}

	/**
	* 删除城市
	*/
	public function deleteCity($id, $status = -1){
		$where = [
			'id' => $id,
		];
		$data = [
			'status' => $status,
		];
		return $this->where($where)->update($data);
	}

}
