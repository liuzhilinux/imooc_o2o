<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
    	return $this->fetch();
    }

    public function welcome()
    {
    	return $this->fetch();
    }

    public function login(){
    	echo '登入页面。';
    }

    public function register(){
    	echo '注册页面。';
    }
}
