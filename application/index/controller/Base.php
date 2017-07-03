<?php
namespace app\index\controller;

use think\Controller;

class Base extends Controller
{
    protected $city;
    protected $user;

    protected function _initialize()
    {
    	// 判断是否已经登入
    	if(session('?user', '', 'o2o')){
    		$user = session('user', '', 'o2o');
    	}else{
    		$user = '';
    	}
        $this->user = $user;
        // 取出城市列表数据
        $citys = model('city')->getCityListByParent();
        // 获得当前城市
        $this->getCity();
        // 获取分类数据
        $cats = $this->getCats();
        // halt($cats);
        // 获得城市 ID 与城市名称的对照表 
        $cityMap = model('city')->getNameMap();

        $this->assign('citys', $citys);
    	$this->assign('city', $this->city);
        $this->assign('cats', $cats);
        $this->assign('user', $user);
        $this->assign('css', strtolower($this->request->controller()));
        $this->assign('cityMap', $cityMap);
    }

    private function getCity()
    {
        // 如果获得 GET 请求则修改当前城市信息
        if($this->request->get('city')){
            // 根据 uname 从数据库中获取城市信息
            $uname = $this->request->get('city');
            $this->city = model('city')->getCityByUname($uname);
        }else{
            // 判断是否已选择当前城市
            if(session('?current_city', '', 'o2o')){
                $this->city = session('current_city', '', 'o2o');
            }else{
                $this->city = model('city')->getCityByUname();
            }
        }
        // 更新 SESSION
        session('current_city', $this->city, 'o2o');
    }

    private function getCats(){
        // 从数据库中获取一级分类数据

        $topCats = model('category')->getCategoryByParent(0, 5);
        $tops = [];

        // 整理数据
        foreach ($topCats as $cat) {
            $tid = $cat->id;
            $subs = [];
            // 获得子分类
            $subCats = model('category')->getCategoryByParent($tid);

            // 组织数据
            foreach ($subCats as $c) {
                $subs[$c->id] = $c->name;
            }
            $top = [
                'name' => $cat->name,
                'subs' => $subs,
            ];

            $tops[$tid] = $top;

        }

        return $tops;
    }

}
