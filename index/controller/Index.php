<?php
namespace app\index\controller;
use app\index\controller\Base;

class Index extends Base
{
    public function index()
    {
    	$title = '首页';

    	// 获取首页推荐位图片
    	$centerReatureds = model('featured')->getFeaturedListByType(0);

    	// 获取右边推荐位图片
    	$rightReatureds = model('featured')->getFeaturedListByType(1);

    	// 获取团购列表
    	$dealList = $this->getDealList();

    	$this->assign('title', $title);
    	$this->assign('centerReatureds', $centerReatureds);
    	$this->assign('rightReatureds', $rightReatureds);
    	$this->assign('dealList', $dealList);
    	return $this->fetch();
    }

    private function getDealList()
    {
    	$list = model('deal')->getListByLimit();
    	$res = [];
    	foreach ($list as $item) {
    		$cate_id = $item->category_id;
    		$cate = model('category')->getCategoryInfo($cate_id);
    		if($cate->parent_id != 0)
    			$cate = model('category')->getCategoryInfo($cate->parent_id);
    		$res[intval($cate->id)][] = $item;
    	}

    	return $res;
    }


}
