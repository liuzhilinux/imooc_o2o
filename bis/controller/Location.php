<?php
/**
*商户分店列表以及添加页面
*/
namespace app\bis\controller;
use app\bis\controller\Base;


class Location extends Base
{

    public function index()
    {
    	// 获得分店列表
    	$list = $this->location->getLocationList($this->user->bis_id);
    	$this->assign('list', $list);
        return $this->fetch();

    }


    public function add()
    {
    	// 当为 POST 传值时，表示为提交数据
    	if($this->request->isPost()){
	        $req = $this->request;
	        $bis_id = $this->user->bis_id;
	        // 获取数据
	        $data = $req->param();
	        // city_id
	        if($req->has('thr_city_id') && $req->param('thr_city_id') != 0)
	            $city_id = $req->param('thr_city_id');
	        else if($req->has('sec_city_id') && $req->param('sec_city_id') != 0)
	            $city_id = $req->param('sec_city_id');
	        else $city_id = $req->param('city_id');
	        // category_id
	        if($req->has('sec_category_id'))
	            $category_id = $req->param('sec_category_id');
	        else $category_id = $req->param('category_id');
	        // 经纬度
	        if($req->has('lng') && $req->param('lng')){
	            $lng = floatval($req->param('lng'));
	            $lat = floatval($req->param('lat'));
	        }else{
	            $this->error('请填写地址并标记！');
	        }
	        // 组织数据
	        // 总店信息
	        $locationData = [
	            'name' => $data['name'],
	            'logo' => $data['logo'],
	            'address' => $data['address'],
	            'tel' => $data['tel'],
	            'contact' => $data['contact'],
	            'xpoint' => $lng,
	            'ypoint' => $lat,
	            'open_time' => $data['open_time'],
	            'content' => $req->has('content') ? $data['content'] : '',
	            'is_main' => 0,
	            'city_id' => $city_id,
	            'category_id' => $category_id,
	        ];
	        // 数据验证
	        if(true !== $result = $this->validate($locationData, 'Bis.location'))
	        	$this->error($result);
	        else{
	            // 数据入库
	            if(!$location_id = $this->location->add($locationData))
	                $this->error('添加分店信息失败！');
	            else $this->success('添加分店信息成功！', url('/bis/location/index'));
	        }

    	}else{
	        // 获得一级城市列表
	        $citys = model('City')->getCityListByParent();
	        // 获取一级分类列表
	        $categorys = model('Category')->getNormalCategoryList();
	        // 传递数据给模板
	        $this->assign('citys', $citys);
	        $this->assign('categorys', $categorys);
        	return $this->fetch();
		}
    }

    public function detial(){
    	if(!$this->request->has('id')) $this->error('非法请求！');
        $id = $this->request->param('id');
        // 获得总店信息
        $location = $this->location->get(['bis_id' => $id]);
        // 获得城市信息
        $city = model('city')->get(['id' => $location->city_id]);
        // 获得分类信息
        $category = model('category')->get(['id' => $location->category_id]);

        $this->assign('location', $location);
        $this->assign('city', $city);
        $this->assign('category', $category);

        return $this->fetch();
    }

    public function delete(){
    	if(!$this->request->has('id')) $this->error('请求非法！');
    	$id = $this->request->param('id');
    	if($this->location->changeStatus($id, -1)) $this->success('删除成功！');
    	else $this->error('删除失败！');
    }



}
