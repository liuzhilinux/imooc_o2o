<?php
namespace app\common\model;
use think\Db;

class BisAccount extends BisBase
{
	// 设置表名
	protected $name = 'bis_account';

	public function getAccount($username){
		$where = [
			'username' => $username,
		];
		return $this->where($where)->find();
	}

	public function getAccountById($id){
		$where = [
			'id' => $id,
		];
		return $this->where($where)->find();

	}
}