<?php
namespace app\index\controller;
use app\index\controller\Base;

class Order extends Base
{
    private $deal_id;
    private $count;
    private $deal;

    protected function _initialize()
    {
        parent::_initialize();
        if(!$this->user) $this->error('请登入！', url('/login'));
        // 获取 GET 数据
        if(!input('get.id') && input('get.count')) $this->error('请求非法！');
        $this->deal_id = input('get.id');
        $this->count = input('get.count', 1);
        // 获取团购信息
        $this->deal = model('deal')->get($this->deal_id);
        if(!$this->deal || $this->deal->status != 1)  $this->error('请求非法！');

    }

    public function index()
    {
        $referer = input('server.http_referer');
        if(!$referer) $this->error('请求非法！');

        // 组织数据
        $data = [
            'out_trade_no' => getOrderSn(),
            'user_id' => $this->user->id,
            'username' => $this->user->username,
            'deal_id' => $this->deal_id,
            'deal_count' => $this->count,
            'total_price' => $this->count * $this->deal->current_price,
            'referer' => $referer,
        ];
        // 数据入库
        $orderId = model('order')->add($data);
        if($orderId) return $this->redirect(url('/pay/index', ['id'=> $orderId]));
        else return $this->error('订单提交失败！');
    }

    public function confirm()
    {


        $this->assign('title', '订单支付');
        $this->assign('css', 'pay');
        $this->assign('count', $this->count);
        $this->assign('deal', $this->deal);

        return $this->fetch();
    }

}
