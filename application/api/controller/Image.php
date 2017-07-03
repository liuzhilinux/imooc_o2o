<?php
namespace app\api\controller;
use think\Controller;
use think\Request;
use think\Config;

class Image extends Controller
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

    public function upload(){
    	$file = $this->request->file('file');
	    $info = $file->move('uploads/image/');
	    $rootpath = Config::get('public_upload_image_url');
	    // $rootpath = $this->request->domain();
	    if($info && $info->getPathname()){
	    	return showResult(1, 'success', $rootpath . $info->getPathname());
	    }else{
	    	return showResult(0, '图片上传失败！'.$file->getError());
	    }
    }
}