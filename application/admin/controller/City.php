<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Loader;
use app\common\model\City as CityModel;
use think\Config;


class City extends Controller
{
    private $validate;
    private $city;

    protected function _initialize()
    {
        $this->city = new CityModel;
        $this->validate = Loader::validate('City');

    }

    /**
     * 显示城市列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        // 获得 $_GET 传过来的 id.
        $id = $request->has('parent_id') ? $request->param('parent_id') : 0;
        // 获得当前城市的信息。
        $citys = $this->city->getCityList($id);
        $this->assign('citys', $citys);
        return $this->fetch();
    }

    public function add()
    {
        // $tree = $this->city->getCityTree();
        $tree = $this->city->getCityTree_2();
        return $this->fetch('',[
                'tree' => $tree,
                'api' => Config::get('fanyi.api'),
                'appid' => Config::get('fanyi.appid'),
                'key' => Config::get('fanyi.key'),
                'salt' => Config::get('fanyi.salt'),
                'from' => Config::get('fanyi_lang.zh'),
                'to' => Config::get('fanyi_lang.en'),
            ]);
    }


    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 是否为 POST
        if(!$request->isPost()){
            $this->error('请求非法！');
        }
        // 数据获取
        $data = [
            'name' => $request->param('name'),
            'uname' => $request->param('uname'),
            'parent_id' => $request->param('parent_id'),
        ];
        // 数据验证
        $res = $this->validate->scene('save')->check($data);
        if(true !== $res){
            // 转到异常提示页面
            $this->error($this->validate->getError());
        }else{
            // 数据入库
            if($this->city->add($data)) $this->success('添加成功！');
            else $this->error('添加失败！');
        }

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        // 验证数据合法性
        if(!$this->validate->scene('edit')->check(['id' => $id])){
            $this->error($this->validate->getError());
        }
        // 获取要编辑的数据以准备填充
        $city = $this->city->getCityInfo($id);
        $tree = $this->city->getCityTree_2();
        if(!$city && !$tree){
            $this->error('请求失败！');
        }
        $this->assign('city', $city);
        $this->assign('tree', $tree);
        $this->assign('api', Config::get('fanyi.api'));
        $this->assign('appid', Config::get('fanyi.appid'));
        $this->assign('key', Config::get('fanyi.key'));
        $this->assign('salt', Config::get('fanyi.salt'));
        $this->assign('from', Config::get('fanyi_lang.zh'));
        $this->assign('to', Config::get('fanyi_lang.en'));
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request)
    {
        // 判断是否为 POST 传值
        if(!$request->isPost()) $this->error('请求非法！');
        // 获取请求数据
        $data = $request->param();
        // 验证数据的合法性
        if(!$this->validate->scene('update')->check($data)) $this->error($this->validate->getError());
        // 数据入库
        if($this->city->edit($data)) $this->success('修改成功！');
        else $this->error('修改失败！');
    }

    /**
     * 删除城市
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        // 验证数据合法性
        if(!$this->validate->scene('delete')->check(['id' => $id])){
            $this->error($this->validate->getError());
        }
        // 执行删除操作
        if($this->city->deleteCity($id)){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }
}
