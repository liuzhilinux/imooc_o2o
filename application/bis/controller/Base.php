<?php
/**
*商户后台基类
*/
namespace app\bis\controller;
use think\Controller;
use app\common\model\Bis;
use app\common\model\BisLocation;
use app\common\model\BisAccount;
use app\common\model\BisAuthentication;

class Base extends Controller
{
    protected $bis;
    protected $location;
    protected $account;
    protected $user;
    protected $authentication;

    protected function _initialize(){
        $this->bis = new Bis;
        $this->location = new BisLocation;
        $this->account = new BisAccount;
        $this->authentication = new BisAuthentication;
        // 判断用户是否已经登入
        if($this->authentication->isLogin()){
        	$this->user = $this->account->getAccountById($this->authentication->getCurrentUserId());
        }else{
			// return $this->error('请登入！', url('/bis/login'));    		
    		return $this->redirect(url('/bis/login'));
        }
    }

}
