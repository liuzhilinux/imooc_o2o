<?php
namespace app\index\controller;

use think\Controller;

class User extends Controller
{
	protected function _initialize(){

	}

    public function login()
    {
    	// 判断是否已经登入
    	if(session('?user', '', 'o2o')){
    		return $this->redirect(url('/'));
    	}

    	return $this->fetch();
    }

    public function loginCheck()
    {
    	if(!$this->request->isPost()){
    		$this->error('请求非法！');
    	}
    	// 获取 POST 数据
    	$data = $this->request->param();
    	// 数据校验
		if(true !== $result = $this->validate($data, 'User.check')) $this->error($result);
		// 从数据库获取用户数据
		try{
			$user = model('user')->getUserByName($data['username']);
		}catch(\Exception $e){
			$this->error($e->getMessage());
		}

		if($user){
			// 密码验证
			if(md5($data['password'].$user->code) == $user->password){
				// 更新用户登入时间
				try{
					$result = model('user')->updateById(['last_login_time'=>time()], $user->id);
				}catch(\Exception $e){
					$this->error($e->getMessage());
				}
				if(!$result) $this->error('登入失败！');
				// 注册 SESSION
				session('user', $user, 'o2o');
				return $this->redirect(url('/'));
			}else{
				return $this->error('密码错误！');
			}
		}else{
			return $this->error('用户不存在！');
		}
    }


    public function logout(){
    	session(null, 'o2o');
    	return $this->redirect(url('/login'));
    }


    public function register()
    {
    	// 判断是否已经登入
    	if(session('?user', '', 'o2o')){
    		return $this->redirect(url('/'));
    	}
    	// 注册表单提交逻辑
    	if($this->request->isPost()){
    		// 验证码校验
    		$verifyCode = $this->request->param('verifyCode', '');
    		if(!captcha_check($verifyCode)) $this->error('验证码输入有误！');
    		// 获取表单数据
    		$data = $this->request->param();
    		// 确认密码一致性
    		if($data['password'] !== $data['repassword']) $this->error('两次输入的密码不一致！');
    		// 数据验证
    		if(true !== $result = $this->validate($data, 'User.add')) $this->error($result);
    		// 密码合成
    		$code = mt_rand(100,10000);
    		$password = md5($data['password'].$code);

    		// 组织数据
    		$sdata = [
    			'username' => $data['username'],
    			'email' => $data['email'],
    			'email' => $data['email'],
    			'password'  => $password,
    			'code' => $code,
    		];
    		// 数据入库
    		try {
	    		$result = model('user')->add($sdata);    			
    		} catch (\Exception $e) {
    			$this->error($e->getMessage());
    		}

    		if($result) $this->success('注册成功！',url('/login'));
    		else $this->error('注册失败！');

    	}
    	// 注册页面显示逻辑
    	return $this->fetch();
    }

}
