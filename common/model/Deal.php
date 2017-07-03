<?php
namespace app\common\model;

class Deal extends BisBase
{
	// 设置表名
	protected $name = 'deal';

	public function getDealList($id)
	{
		$where = [
			'bis_account_id' => $id,
			'status' => ['in', [0,1]],
		];
		$order = [
			'id' => 'desc',
		];

		return $this->where($where)->order($order)->paginate(12);
	}

	public function getDeals($where = [])
	{
		$where['status'] = ['in', [0,1]];
		$order = [
			'id' => 'desc',
		];
		return $this->where($where)->order($order)->paginate(12);
	}

	public function changeStatus($id, $status){
		$where = [
			'id' => $id,
		];
		$data = [
			'status' => $status,
		];
		return $this->where($where)->update($data);
	}

	public function getOrderDealsByIds($ids = [], $order_column = ''){
		$order = [
			'id' => 'desc',
		];
		$where = [
			'status' => 1,
		];
		if($ids){
			$where['category_id'] = ['in', implode(',', $ids)];
		}
		if($order_column){
			$order[$order_column] = 'desc';
		}

		return $this->order($order)->where($where)->paginate(15);
	}

}