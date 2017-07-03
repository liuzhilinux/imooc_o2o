<?php
namespace app\index\controller;
use app\index\controller\Base;

class Pay extends Base
{
    private $order;
    private $deal;

    protected function _initialize()
    {
        parent::_initialize();
        if(!$this->user) $this->error('请登入！', url('/login'));
        // 获取 GET 数据
        if(!input('get.id')) $this->error('请求非法！');
        $id = input('get.id');
        // 获取订单信息
        $this->order = model('order')->get($id);
        // 获取团购信息
        $this->deal = model('deal')->get($this->order->deal_id);
        if(!$this->deal || $this->deal->status != 1)  $this->error('请求非法！');
        if($this->user->id != $this->order->user_id) $this->error('非本人提交的订单。');

    }


    public function index()
    {
        $this->assign('title', '微信扫一扫');
        $this->assign('order', $this->order);
        $this->assign('deal', $this->deal);
        return $this->fetch();
    }


}
