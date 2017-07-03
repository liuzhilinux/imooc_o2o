<?php
namespace app\admin\controller;
use think\Controller;

class Featured extends Controller
{
    private $types;
    protected function _initialize()
    {
        // 所属分类列表
        $this->types = config('featured_type');

    }

    public function index()
    {
        $type = '';
        // 取出推荐位数据
        if($this->request->has('type') && $this->request->param('type') != ''){
            $type = $this->request->param('type');
            $featureds = model('featured')->getFeaturedsByType($type);
        }else{
            $featureds = model('featured')->getFeaturedsByType();
        }
        $this->assign('type', intval($type));
        $this->assign('featureds', $featureds);
        $this->assign('types', $this->types);
        return $this->fetch();
    }

    public function add()
    {
        if($this->request->isPost()){
            // 获取 POST 传值
            $data = $this->request->param();
            // 数据验证
            if(true !== $result = $this->validate($data, 'Featured.add')) $this->error($result);
            else{
                // 数据入库
                if(model('featured')->addFeatured($data))
                    $this->success('推荐位信息添加成功！', url('/admin/featured/index'));
                else $this->error('推荐位信息添加失败');
            }
        }
        
        $this->assign('types', $this->types);
        return $this->fetch();
    }

    public function changeStatus(){
        dump(1);
    }
    public function delete(){
        dump(2);
    }
}
