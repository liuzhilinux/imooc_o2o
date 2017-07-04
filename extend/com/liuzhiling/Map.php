<?php
namespace com\liuzhiling;
use com\liuzhiling\Curl;
use think\Config;
/**
* 封装百度地图服务
*/
class Map{
	/**
	* 获取经纬度信息
	* http://api.map.baidu.com/geocoder/v2/?address=北京市海淀区上地十街10号&output=json&ak=E4805d16520de693a3fe707cdc962045&callback=showLocation
	*
	* @param string $address 
	* @return array 
	*
	*/
	public static function getLngLat($address){
		if(empty($address) || $address == '') return [];
		$url = Config::get('map.api_domain')
			.Config::get('map.geocoder').'/'
			.Config::get('map.geocoder_version').'/';
		$data = [
			'address' => $address,
			'output' => 'json',
			'ak' => Config::get('map.ak'),
		];
		// 发送 GET 请求
		return Curl::getData($url, $data);
	}

	/**
	* 根据经纬度坐标获取静态地图。
	* 
	* http://api.map.baidu.com/staticimage/v2?ak=E4805d16520de693a3fe707cdc962045&mcode=666666&center=116.403874,39.914888&width=300&height=200&zoom=11    
	* 
	* <img style="margin:20px" width="280" height="140" src="http://api.map.baidu.com/staticimage/v2?ak=E4805d16520de693a3fe707cdc962045&width=280&height=140&zoom=10" />
	*
	* @param float $lng
	* @param float $lat
	* @return string HTML 
	*/
	public static function getStaticimageHTMLFlag($lng, $lat){
		$url = Config::get('map.api_domain')
			.Config::get('map.staticimage').'/'
			.Config::get('map.staticimage_version');
		$width = Config::get('map.width');
		$height = Config::get('map.height');
		$data = [
			'ak' => Config::get('map.ak'),
			'center' => strval($lng).','.strval($lat),
			'width' => $width,
			'height' => $height,
		];

		return '<img style="margin:20px" width="'. $width . '" height="' . $height
			. '" src="' . $url . '?' . http_build_query($data) . '" />';
	}
}