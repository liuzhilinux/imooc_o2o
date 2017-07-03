<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use com\liuzhiling\Map;

class Index extends Controller
{
	protected function _initialize()
	{
		// 判断是否为 POST 请求
		if(!$this->request->isPost()) $this->error('请求非法！');

	}

    public function index()
    {
    	return showResult(0, '非法请求！');
    }

    public function getCityList()
    {
    	// 获取数据
    	$id = intval($this->request->param('id'));
    	if(!$id || $id <= 0) $this->error('ID 不合法！');
    	// 取出数据
        $citys = model('City')->getCityListByParent($id);
        // 返回 JSON 
        if(!$citys) return showResult(0, '没有下级城市。');
        else return showResult(1, 'success', $citys);
    }

    public function getCategoryList()
    {
    	// 获取数据 
    	$id = intval($this->request->param('id'));
    	if(!$id || $id <= 0) $this->error('ID 不合法！');
    	// 取出数据
    	$categorys = model('category')->getNormalCategoryList($id);
    	// 返回 JSON
    	if (!$categorys) {
    		return showResult(0, '没有下级分类。');
    	}else{
    		return showResult(1, 'success', $categorys);
    	}
    }

    public function getLngLat(){
        if($this->request->has('address')){
            if('' == $this->request->param('address'))
                return showResult(0,'地址未填写！');
            $res = Map::getLngLat($this->request->param('address'));
            if($res && $res['status'] == 0)
                return showResult(1, 'success', $res['result']);
            else if($res && $res['status'] && array_key_exists('msg', $res))
                return showResult(0, $res['msg']);
        }
        return [];
    }

}
