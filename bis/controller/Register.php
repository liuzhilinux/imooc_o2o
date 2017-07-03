<?php
namespace app\bis\controller;
use think\Controller;
use app\common\model\Bis;
use app\common\model\BisLocation;
use app\common\model\BisAccount;
use com\liuzhiling\Map;
use com\liuzhiling\Mail;
use think\Config;

class Register extends Controller
{    
	private $bis;
    private $location;
    private $account;

    protected function _initialize(){
        $this->bis = new Bis;
        $this->location = new BisLocation;
        $this->account = new BisAccount;
    }


    public function index()
    {
        // 获得一级城市列表
        $citys = model('City')->getCityListByParent();
        // 获取一级分类列表
        $categorys = model('Category')->getNormalCategoryList();
        // 传递数据给模板
        $this->assign('citys', $citys);
        $this->assign('categorys', $categorys);
    	return $this->fetch();
    }
    public function add()
    {
        $req = $this->request;
        // 判断是否为 POST 传值
        if(!$req->isPost()) $this->error('请求非法！');
        // 获取数据
        $data = $req->param();
        // city_id
        if($req->has('thr_city_id') && $req->param('thr_city_id') != 0)
            $city_id = $req->param('thr_city_id');
        else if($req->has('sec_city_id') && $req->param('sec_city_id') != 0)
            $city_id = $req->param('sec_city_id');
        else $city_id = $req->param('city_id');
        // category_id
        if($req->has('sec_category_id'))
            $category_id = $req->param('sec_category_id');
        else $category_id = $req->param('category_id');
        // 经纬度
        if($req->has('lng') && $req->param('lng')){
            $lng = floatval($req->param('lng'));
            $lat = floatval($req->param('lat'));
        }else{
            $this->error('请填写地址并标记！');
        }
        // 判断是否为重复注册
        if($this->account->getAccount($data['username'])) $this->error('用户名已存在，请勿重复注册！');
        // 组织数据
        // 基本信息
        $bisData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'logo' => $data['logo'],
            'licence_logo' => $data['licence_logo'],
            'description' => $req->has('description') ? $data['description'] : '',
            'city_id' => intval($city_id),
            'category_id' => intval($category_id),
            'bank_info' => $data['bank_info'],
            'bank_name' => $data['bank_name'],
            'bank_user' => $data['bank_user'],
            'faren' => $data['faren'],
            'faren_tel' => $data['faren_tel'],
        ];
        // 总店信息
        $locationData = [
            'name' => $data['name'],
            'logo' => $data['logo'],
            'address' => $data['address'],
            'tel' => $data['tel'],
            'contact' => $data['contact'],
            'xpoint' => $lng,
            'ypoint' => $lat,
            'open_time' => $data['open_time'],
            'content' => $req->has('content') ? $data['content'] : '',
            'is_main' => 1,
            'city_id' => $city_id,
            'category_id' => $category_id,
        ];
        // 账户相关信息
        $salt = mt_rand(100,100000); // 密码加盐
        $accountData = [
            'username' => $data['username'],
            'password' => md5($data['password'].$salt),
            'code' => $salt,
            'is_main' =>1,
        ];


        // 数据验证
        // if(true !== $result = $this->validate($data, 'Bis.add')) $this->error($result);
        if(true !== $result = $this->validate($bisData, 'Bis.bis')) $this->error($result);
        if(true !== $result = $this->validate($locationData, 'Bis.location')) $this->error($result);
        if(true !== $result = $this->validate($accountData, 'Bis.account')) $this->error($result);
        else{
            // 数据入库
            if(!$bis_id = $this->bis->add($bisData)) $this->error('添加基本信息失败！');
            $locationData['bis_id'] = $accountData['bis_id'] = $bis_id;
            if(!$location_id = $this->location->add($locationData))
                $this->error('添加总店信息失败！');
            if(!$account_id = $this->account->add($accountData))
                $this->error('添加用户信息失败！');
            else{
                // 发送邮件
                $config = [
                    'host' => Config::get('mail.host'),
                    'username' => Config::get('mail.username'),
                    'password' => Config::get('mail.password'),
                    'port' => Config::get('mail.port'),
                ];
                $mail = Mail::getInstance($config);
                $from = [
                    'my_email' => Config::get('my_email'),
                    'my_name' => Config::get('my_name'),
                    'to' => $data['email'],
                    'to_name' => $data['contact'],
                    'reply_to' => '',
                    'reply_to_name' => '',
                    'cc' => '',
                    'bcc' => '',
                ];
                $mail->setFrom($from);
                $url = $req->domain().url('waiting',['id' => $account_id]);
                $subject = '商户入驻申请通知';
                $body = '亲爱的用户'.$data['contact'].'你好：<br/>';
                $body .= '你已经提交成功商户入驻申请，正在等待审核。<br/>';
                $body .= '你可以点击<a herf="'.$url.'" target="_blank">此处</a>查看。';
                if(true !== $result = $mail->send($subject, $body)) $this->error($result);
                $this->success('商户入驻申请发送成功！', url('bis/waiting', ['id' => $account_id]));
            }
        }
    }


    public function waiting(){
        $id = $this->request->param('id');
        $account = $this->account->getAccountById($id);
        $this->assign('account', $account);
        return $this->fetch();
    }


}
