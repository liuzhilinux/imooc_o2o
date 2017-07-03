<?php
/**
*商户后台页面
*/
namespace app\bis\controller;
use app\bis\controller\Base;


class Index extends Base
{

    public function index()
    {
        $this->assign('user', $this->user);
        return $this->fetch();

    }


    public function logout(){
        $this->authentication->logout();
        return $this->redirect(url('/bis/login'));
    }

}
