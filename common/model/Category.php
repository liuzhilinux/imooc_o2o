<?php

namespace app\common\model;

use think\Db;

class Category extends ListBase
{
	// 设置表名
	protected $name = 'category';

	public function add($data)
	{
		$data['status'] = 0;
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

	public function getCategoryList($parent_id = 0)
	{
		$where = [ 
			'parent_id' => $parent_id,
			// 排除被删除掉的分类
			'status' => ['in','0,1'],
		];

		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];
		return $this->where($where)->order($order)->paginate(12);
	}


	/**
	* 获取一级列表。
	*/
	public function getNormalCategoryList($parent_id = 0)
	{
		$where = [
			'status' => 1,
			'parent_id' => $parent_id,
		];

		$order = [
			'id' => 'desc',
		];
		return $this->where($where)->order($order)->select();
	}
	/**
	* 获取列表信息。
	*/
	public function getCategoryInfo($id = 0)
	{
		$where = [
			'id' => $id,
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
	* 修改状态
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


	/**
	* 根据 Parent Id 获取分类信息。
	*/
	public function getCategoryByParent($parent_id, $limit = 0){
		$where = [
			'parent_id' => $parent_id,
			'status' => 1,
		];
		$order = [
			'listorder' => 'desc',
			'id' => 'desc',
		];
		if($limit){
			$this->limit($limit);
		}
		return $this->where($where)->order($order)->select();
	}

	public function getSubCategoryIdsByParent($parent = 0){
		$ids = [];
		$where = [
			'status' => 1,
			'parent_id' => $parent,
		];
		$subs = $this->where($where)->select();

		foreach ($subs as $cat) {
			$ids[] = $cat->id;
		}
		return $ids;
	}
}
