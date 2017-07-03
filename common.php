<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


/**
* 状态 HTML 代码动态拼接
*/
function getStatusStr($status){
	switch ($status) {
		case 1:
			$str = '<span class="label label-success radius">正常</span>';
			break;
		case 0:
			$str = '<span class="label label-danger radius">待审</span>';
			break;
		default:
			$str = '<span class="label label-danger radius">删除</span>';
			break;
	}

	return $str;
}



/**
* 显示城市的树状结构
* <option value="{$vo.id}">--{$vo.name}</option>
*/

function showCityTree($tree, $uid = 0, $res = '', &$i = 0){
	$i++;
	$j = count($tree);
	$str = '';
	for(; $i >1; $i--){
		$str .= '│  ';
	}
	foreach($tree as $id => $item){
		$str2 = $j != 1 ? '├─' : '└─';
		$res .= '<option value="' . $id . '"';
		if($id == $uid) $res .= 'selected="selected"';
		$res .= '>' . $str . $str2 . $item['name'] . '</option>';
		if(count($item['sub_citys']) > 0){
			$res = showCityTree($item['sub_citys'], $uid, $res, $i);
		}
		$j--;
	}
	$i--;
	return $res;
}
function showCityTree_2($tree, $uid = 0){
	$res = '';
	foreach ($tree as $id => $name) {
		$res .= '<option value="' . $id . '"';
		if($id == $uid) $res .= 'selected="selected"';
		$res .= '>'.$name.'</option>';
	}
	return $res;
}

/**
* 以统一格式返回数据，以方便前端调用 API 。
*/
function showResult($status, $message = '', $data = []){
	return json([
		'status' => $status,
		'message' => $message,
		'data' => $data,
	]);
}

/**
* 根据状态切换提示信息
*/
function getStatusSwitch($status){
	switch ($status) {
		case 1:
			return '你已申请通过！';
		case 0:
			return '你提交的信息已保存成功，请等待工作人员审核。';
		case -1:
			return '你提交的信息不合法，已被删除！！！';		
	}
}

/**
* 展示分页效果
*/
function showRender($obj){
	// 获取页面参数
	$params = request()->param();
	$html = '<div class="tp5-pagination cl pd-5 bg-1 bk-gray mt-20">';
	$html .= is_null($obj) ? '' : $obj->appends($params)->render();
	$html .= '</div>';
	return $html;
}


/**
* 生成订单号
*/
function getOrderSn(){
	list($t1, $t2) = explode(' ', microtime());
	$t1 *= 1e4;
	$t3 = mt_rand(1e6, 1e7);
	return str_replace('.', '', $t3.$t2.$t1);
}