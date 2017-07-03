<?php
namespace app\admin\controller;
use think\Controller;
use think\Request;
use think\Loader;
use app\common\model\Category as CategoryModel;



class Category extends Controller
{
	private $category;
	private $validate;

	protected function _initialize()
	{
		$this->category = new CategoryModel;
    	$this->validate = Loader::validate('Category');

	}

    public function index(Request $request)
    {
    	// 获取通过 $_GET 传过来的 id，将其作为 $param_id 传给模型，以获得子分类列表
    	$id = $request->has('parent_id', 'get') ? $request->param('parent_id') : 0;

    	// 获得分类列表。
    	$categorys = $this->category->getCategoryList($id);
    	return $this->fetch('', [
			'categorys' => $categorys,
		]);
    }

    public function add(Request $request)
    {
    	// $parent_id = $request->has('parent_id', 'get') ? $request->param('parent_id') : 0;
    	$categorys = $this->category->getNormalCategoryList();
    	return $this->fetch('', [
			'categorys' => $categorys,	
		]);
    }

    public function save(Request $request)
    {
    	// 如果接收到的是 $_GET 请求，则提示错误信息。
    	if($request->isGet()){
    		$this->error('非法请求！');
    	}
    	// 获取 $_POST 的数据。
    	$data = $request->param();

    	// 判断是否有 id 的 $_POST 传值，如有，说明是编辑页面。
    	if(array_key_exists('id', $data)){
    		// 验证数据合法性。
	    	if(!$this->validate->scene('edit')->check($data)){
				$this->error($this->validate->getError());
	    	}else{
	    		// 数据入库。
	    		$action = '编辑';
	    		$result = $this->category->edit($data);
    		}
    	}else{
	    	// 验证数据合法性。
	    	if(!$this->validate->scene('add')->check($data)){
				$this->error($this->validate->getError());
	    	}else{
	    		// 数据入库。
	    		$action = '添加';
	    		$result = $this->category->add($data);
	    	}
    	}


    	if($result){
    		$this->success('生活服务分类'.$action.'成功！');
    	}else{
    		$this->error('生活服务分类'.$action.'失败！');
    	}

    }

    public function edit(Request $request)
    {
    	$id = $request->has('id', 'get') ? intval($request->param('id')) : 0;
    	// 获取分类列表。
    	$categorys = $this->category->getNormalCategoryList();
    	// 获取当前 id 的分类。
    	$info = $this->category->getCategoryInfo($id);

    	return $this->fetch('', [
			'categorys' => $categorys,
			'info' => $info,
		]);

    }


    /**
    * 实现动态修改 listorder
    */
    public function listorder(Request $request)
    {
		if(!$request->isPost() && $request->isAjax()){
			$this->error('非法请求！');
		}
		// 获取数据
		$id = $request->has('id', 'post') ? intval($request->param('id')) : 0;
		$listorder = $request->has('listorder', 'post') ? intval($request->param('listorder')) : 0;
		$httpReferer = $request->server('HTTP_REFERER');
		// 验证数据合法性
		if(!$this->validate->scene('listorder')->check(['id'=>$id, 'listorder'=>$listorder])){
			$this->error($this->validate->getError());
		}else{
			// 数据入库
			$result = $this->category->changeListorder($id, $listorder);
		}
		// 数据输出
		if($result){
			// $this->result($_SERVER['HTTP_REFERER'], 1, 'success');
	        return json(['data'=>$httpReferer,'code'=>1,'message'=>'success']);
		}else{
			return json(['data'=>$httpReferer,'code'=>0,'message'=>'请求失败！']);
		}

    }

    /**
    * 删除分类。
    */
    public function delete(){
    	// 获取数据
    	$id = $this->request->get('id');
    	// 数据验证
    	if(!$this->validate->scene('delete')->check(['id'=>$id])){
    		$this->error('数据非法！');
    	}else{
	    	// 通过入库改变 status 的状态
	    	// 默认当 status 值为 -1 时，表示该分类数据已删除
    		$result = $this->category->changeStatus($id, -1);
    		if($result){
    			$this->success('该生活服务分类删除成功！');
    		}else{
    			$this->error('该生活服务分类删除失败！');
    		}
    	}
    }

    /**
    * 修改分类状态。
    */
    public function changeStatus(){
    	// 获取数据
    	$id = $this->request->get('id');
    	$status = $this->request->get('status');
    	// 数据验证
    	if(!$this->validate->scene('changStatus')->check(['id'=>$id, 'status'=>$status])){
    		$this->error($this->validate->getError());
    	}else{
	    	// 通过入库改变 status 的状态
	    	// 0 -> 待审，1 -> 正常
    		$result = $this->category->changeStatus($id, $status);
    		if($result){
    			$this->success('状态修改成功！');
    		}else{
    			$this->error('状态修改失败！');
    		}
    	}
    }


}
