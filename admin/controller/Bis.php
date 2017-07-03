<?php
namespace app\admin\controller;

use think\Controller;
use think\Loader;
use app\common\model\Bis as BisModel;
use app\common\model\BisAccount;
use app\common\model\BisLocation;
use app\common\model\City;
use app\common\model\Category;




class Bis extends Controller
{

    private $validate;
    private $bis;
    private $account;
    private $location;
    private $city;
    private $category;

    protected function _initialize()
    {
        $this->bis = new BisModel;
        $this->account = new BisAccount;
        $this->location = new BisLocation;
        $this->validate = Loader::validate('bis');
        $this->city = new City;
        $this->category = new Category;

    }


    public function index(){
        $list = $this->bis->getBisList(1);
        $this->assign('list', $list);
        return $this->fetch();
    }



    public function apply(){
        $list = $this->bis->getBisList();
        $this->assign('list', $list);
        return $this->fetch();
    }



    public function detial(){
    	if(!$this->request->has('id')) $this->error('非法请求！');
        $id = $this->request->param('id');
        // 获得基本信息
        $bis = $this->bis->get(['id' => $id]);
        // 获得总店信息
        $location = $this->location->get(['bis_id' => $id]);
        // 获得账号信息
        $account = $this->account->get(['bis_id' => $id]);
        // 获得城市信息
        $city = $this->city->get(['id' => $bis->city_id]);
        // 获得分类信息
        $category = $this->category->get(['id' => $bis->category_id]);

        $this->assign('bis', $bis);
        $this->assign('location', $location);
        $this->assign('account', $account);
        $this->assign('city', $city);
        $this->assign('category', $category);

        return $this->fetch();
    }


    public function changeStatus(){
    	// 获取 ID
    	if(!$this->request->isPost() && !$this->request->has('id') && !$this->request->has('status'))
    		$this->error('请求非法！');
    	$id = $this->request->param('id');
    	$status = $this->request->param('status');

    	if($this->bis->changeStatus($id, $status) && $this->location->changeStatus($id, $status) && $this->account->changeStatus($id, $status)){
			// 发送邮件
			// TODO: 
			$this->success('状态修改成功！');
    	}
    	else $this->error('状态修改失败！');
    }
}

