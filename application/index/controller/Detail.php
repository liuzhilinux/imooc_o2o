<?php
namespace app\index\controller;
use app\index\controller\Base;
use com\liuzhiling\Map;
class Detail extends Base
{
    public function index($id)
    {
        if(!intval($id)){
            $this->error('非法访问！');
        }
        // 获取分类数据
        $deal = model('Deal')->get($id);
        if(!$deal || $deal->status != 1){
            $this->error('商品不存在！');
        }

        // 获取分店信息
        $location = model('BisLocation')->get($deal->location_ids);

        // 获取分类信息
        $category = model('category')->getCategoryInfo($deal->category_id);
        
        // 抢购状态信息
        $stat = [];
        $start = $deal->start_time;
        $end = $deal->end_time;
        if(time() < $start){
            // 还未开始
            $stat['type'] = 1;
            // 天数
            $d = floor(($start - time())/3600/24);
            $stat['html'] = $d . date('天H时i分s秒', $start-time());
        }else if(time() < $end){
            // 正在进行
            $stat['type'] = 2;
            // 天数
            $d = floor(($end - time())/3600/24);
            $stat['html'] = $d . date('天H时i分s秒', $end-time());
        }else{
            // 活动结束
            $stat['type'] = 3;
            $stat['html'] = '';
        }
        // 获取地图图片的 HTML 标签 Map::getStaticimageHTMLFlag($lng, $lat)
        $mapImg = Map::getStaticimageHTMLFlag($location->xpoint, $location->ypoint);

        // 获取分店信息
        // $locations = model('BisLocation')->get(['bis_id' => $location->bis_id]);
        // halt($locations);

        $title = $deal->name;
        $this->assign('title', $title);
        $this->assign('deal', $deal);
        $this->assign('category', $category);
        $this->assign('location', $location);
        $this->assign('stat', $stat);
        $this->assign('mapImg', $mapImg);
    	return $this->fetch();
    }




}
