<?php
namespace app\index\controller;
use app\index\controller\Base;

class Lists extends Base
{
    public function index()
    {
        $title = '分类';
        $id = intval(input('id', 0));
        $topid = 0;
        $subid = 0;
        // 判断 id 对应分类级别：大分类 or 分支分类
        if($id){
            if(0 != $tmp = model('category')->get($id)->parent_id){
                $topid = $tmp;
                $subid = $id;
            }else{
                $topid = $id;
            }
        }


        // 获取顶级分类
        $topcat = model('category')->getCategoryByParent(0);
        // 获取对应顶级分类的分支分类
        $subcat = model('category')->getCategoryByParent($topid);

        // 获取排序方式
        $order = input('order', '');

        switch ($order) {
            case 'sales':
                $order = 'buy_count';
                break;
            case 'price':
                $order = 'current_price';
                break;
            case 'time':
                $order = 'create_time';
                break;      
            default:
                $order = '';
        }
        // 获取二级分类集合
        $cate_ids = []; // 存放筛选的分类 ID
        if($subid){ // 表示有二级分类
            $cate_ids[] = $subid;
        }else if($topid){
            // 包含所有该一级分类下的子分类的数组
            $cate_ids = model('category')->getSubCategoryIdsByParent($topid);
        }
        // 根据排序获取团购列表
        $deals = model('deal')->getOrderDealsByIds($cate_ids, $order);


        $this->assign('topid', $topid);
        $this->assign('subid', $subid);
        $this->assign('topcat', $topcat);
        $this->assign('subcat', $subcat);
        $this->assign('id', $id);
        $this->assign('order', $order);
        $this->assign('deals', $deals);

        $this->assign('title', $title);
    	return $this->fetch();
    }

}
