<?php
namespace app\common\model;
use think\Model;

class User extends Model
{
	// 自动写入时间戳
	protected $autoWriteTimestamp = true;

	protected $name = 'user';

	public function add($data){
		// 如果传入的数据不是数组或空数组，抛出异常
		if(!is_array($data) && count($data) == 0){
			exception("ERROR: 传入的数据必须是不为空的数组！");
		}
		$data['status'] = 1;

		return $this->allowField(true)->save($data);
	}

	public function updateById($data, $id){
		if(!$data && is_array($data) && !$id){
			exception('ERROR: 非法请求！n!n');
		}
		$where = [
			'id' => $id,
		];

		return $this->allowField(true)->save($data, $where);

	}

	public function getUserByName($username){
		if(!$username && is_string($username)){
			exception('用户名非法！');
		}
		$where = [
			'username' => $username,
			'status' => 1
		];
		return $this->where($where)->find();
	}
}