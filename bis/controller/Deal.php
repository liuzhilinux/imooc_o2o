<?php
/**
*商户后台页面
*/
namespace app\bis\controller;
use app\bis\controller\Base;
use app\common\model\BisAuthentication;


class Deal extends Base
{

    public function index()
    {
    	$deals = model('deal')->getDealList($this->user->id);
    	$this->assign('deals', $deals);
        return $this->fetch();
    }

    public function add()
    {
    	// 当为 POST 传值时，表示为提交数据
    	if($this->request->isPost()){
	        $req = $this->request;
	        $bis_id = $this->user->bis_id;
	        $bis_account_id = $this->user->id;
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
	        // 店铺列表
	        $location_ids = array_key_exists('location_ids', $data) ?
	        		implode(',', $data['location_ids']) : '';
	        // 组织数据
	        // 总店信息
	        $dealData = [
	            'name' => $data['name'],
	            'image' => $data['image'],
	            'bis_id' => $bis_id,
	            'location_ids' => $location_ids,
	            'start_time' => strtotime($data['start_time']),
	            'end_time' => strtotime($data['end_time']),
	            'coupons_begin_time' => strtotime($data['coupons_begin_time']),
	            'coupons_end_time' => strtotime($data['coupons_end_time']),
	            'description' => $req->has('description') ? $data['description'] : '',
	            'city_id' => $city_id,
	            'category_id' => $category_id,
	            'origin_price' => $data['origin_price'],
	            'current_price' => $data['current_price'],
	            // 'buy_count' => $data['buy_count'],
	            'total_count' => $data['total_count'],
	            'bis_account_id' => $bis_account_id,
	            // 'balance_price' => $data['balance_price'],
	            'notes' => $req->has('notes') ? $data['notes'] : '',
	        ];
	        // 数据验证
	        if(true !== $result = $this->validate($dealData, 'Bis.deal'))
	        	$this->error($result);
	        else{
	            // 数据入库
	            if(!$deal_id = model('deal')->add($dealData))
	                $this->error('添加团购信息失败！');
	            else $this->success('添加团购信息成功！', url('/bis/deal/index'));
	        }

    	}else{
	        // 获得一级城市列表
	        $citys = model('City')->getCityListByParent();
	        // 获取一级分类列表
	        $categorys = model('Category')->getNormalCategoryList();
	        // 获得该用户的总店和分店
	        $locations = $this->location->getLocationList($this->user->bis_id);
	        // 传递数据给模板
	        $this->assign('citys', $citys);
	        $this->assign('categorys', $categorys);
	        $this->assign('locations', $locations);
        	return $this->fetch();
		}
    }


}
