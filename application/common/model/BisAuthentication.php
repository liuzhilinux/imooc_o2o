<?php
/**
* 商户注册用户登入身份验证模块。
*/
namespace app\common\model;
use think\Session;
use think\Cookie;

class BisAuthentication
{
    /**
    * 当用户登入验证成功后在服务器端和用户端标记用户登入信息。
    * @param array $data 用户信息
    * @return null
    */

    public function setMark($data){
        $code = mt_rand(100,10000);
        Session::set('id', $data['id']);
        Session::set('user_name', $data['username']);
        Session::set('code', $code);
        Session::set('is_login', 1);
        Cookie::set('id', $data['id']);
        Cookie::set('user_mark', md5($data['username'].$code));
        Cookie::set('user_name', $data['username']);
    }

    /**
	* 判断当前用户是否为登入状态。
	* @param null
	* @return null
	* 
	*/
	public function isLogin(){
        if(Session::get('is_login') == 1 && Cookie::has('id') &&
            Cookie::has('user_name') && Cookie::has('user_mark')){

            $id = Cookie::get('id');
            $username = Cookie::get('user_name');
            $mark = Cookie::get('user_mark');
            // 身份验证
            if($id == Session::get('id') && $username == Session::get('user_name') &&
                $mark = md5(Session::get('user_name') . Session::get('code')))

            	return TRUE;
        }
    	return FALSE;
	}

    /**
	* 登出当前用户。
	* @param null
	* @return null
	* 
	*/
	public function logout(){
		Session::clear();
		Cookie::clear('');
	}

    /**
    * 获得登入的用户的 id
    * @param null
    * @return int id
    */
    public function getCurrentUserId(){
        return intval(Session::get('id'));
    }



}
