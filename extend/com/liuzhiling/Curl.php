<?php
/**
* Curl支持类。
*/

namespace com\liuzhiling;


class Curl {
	/**
	* 获取特定API返回数据。
	* @param $url 请求地址
	* @param $data 要发送的数据
	* @param $isPost 是否为POST请求
	* @return array/FALSE 返回结果。
	*/
	public static function getData($url, $data = [], $isPost = false){
		// 初始化 curl
		$ch = curl_init();
		// 配置属性
		//获取的信息以字符串返回
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		//禁用将头文件的信息作为数据流输出
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		if($isPost){
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}elseif(count($data) > 0){
			$url .= '?'.http_build_query($data);
		}
		//绑定 url
		curl_setopt($ch, CURLOPT_URL, $url);
		// 执行
		$result = curl_exec($ch);
		curl_close($ch);

		return json_decode($result, true);
	}


}