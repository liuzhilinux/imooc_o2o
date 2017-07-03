<?php
namespace app\admin\controller;
use think\Controller;

class Deal extends Controller
{
    public function index()
    {
        $sdata = [];

        // 城市数据
        // 分类数据
        // TODO
        // halt($this->request->param());
        // 实现日期范围搜索
        if($this->request->has('start_time') && $this->request->has('end_time')){
            $start = $this->request->param('start_time');
            $end = $this->request->param('end_time');
            if($start != '' && $end != ''){
                $sdata['start_time'] = ['gt', strtotime($start)];
                $sdata['end_time'] = ['lt', strtotime($end)];
            }
        }
        // 实现商品名称搜索
        if($this->request->has('name')){
            $sdata['name'] = ['like', '%'.$this->request->param('name').'%'];
        }
        // 获得城市 ID 与城市名称的对照表 
        $cityMap = model('city')->getNameMap();
        // 获得分类 ID 与分类名称的对照表 
        $categoryMap = model('category')->getNameMap();
        // 组织数据

        // 从数据库中取数据
        $deals = model('deal')->getDeals($sdata);
        $this->assign('deals', $deals);
        $this->assign('cityMap', $cityMap);
        $this->assign('categoryMap', $categoryMap);

        return $this->fetch();
    }

    public function changeStatus()
    {
        if(!$this->request->has('status') || !$this->request->has('id')) $this->error('请求非法！');
        $status = $this->request->get('status');
        $id = $this->request->get('id');
        if(model('deal')->changeStatus($id, $status))
            $this->success('状态修改成功！');
        else halt(1);
            $this->error('状态修改失败！');
    }
    public function detial()
    {
        dump('test1');
    }
    public function delete()
    {
        dump('test2');
    }

}
