<?php
namespace app\bis\controller;
use think\Controller;
use app\common\model\BisAccount;
use app\common\model\BisAuthentication;

class Login extends Controller
{
    public function index()
    {
        if($this->request->isPost()){
            // POST 请求表示用户登入
            // 获取数据
            if(!$this->request->has('username') && !$this->request->has('password')){
                $this->error('请求非法！');
            }
            $account = new BisAccount;
            $username = $this->request->param('username');
            $password = $this->request->param('password');
            // 从数据库中获取用户数据
            $data = $account->getAccount($username);
            if($data && $data['status'] == 1 && $data['password'] == md5($password . $data['code'])){
                // 登入成功
                // 标记 SESSION 和COOKIE 
                (new BisAuthentication)->setMark($data);
                $this->success('登入成功！', url('/bis/index'));
            }else{
                $this->error('用户名或密码错误！');
            }

        }
        // GET 请求访问时跳转到登入页面
        if((new BisAuthentication)->isLogin()) return $this->redirect(url('/bis/index'));
    	return $this->fetch();

    }


}