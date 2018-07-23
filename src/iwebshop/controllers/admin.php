<?php
/**
 * 管理员
 */
class Admin extends IController
{
    /**
     * 获取单条管理员信息
     * @param int id 管理员ID
     */
    public function Get()
    {
        //获取管理员ID
        $admin_id = IFilter::act(IReq::get('id'),'int');
        //创建管理员对象
        $adminDB = new IModel('admin');
        //检验管理员是否存在
        if(!$admin_id || !$adminDB->getObj('id = '.$admin_id))
            JsonResult::fail('该管理员不存在');
        //返回管理员信息
        JsonResult::success($adminDB->getObj('id = '.$admin_id));
    }

    /**
     * 获取管理员列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 管理员姓名(可选)
     */
    public function List()
    {
        //获取列表页码，页数以及搜索名称
        $page = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        $name = IFilter::act(IReq::get('name'));
        //创建管理员对象
        $adminDB = new IQuery('admin');
        //模糊匹配
        if($name)
            $adminDB->where = "name like '".$name."%'";
        //返回列表
        $adminDB->page = $page;
        $result = $adminDB->find();
        JsonResult::success(array(
            'totalpage' => $adminDB->paging->totalpage,
            'index' => $adminDB->paging->index,
            'pagesize' => $adminDB->paging->pagesize,
            'result' => $result
        ));
    }

    /**
     * 添加管理员
     * @param string admin_name 用户名
     * @param string password 密码
     * @param string email 邮箱
     * @param string phone 手机
     * 
     * @return 管理员ID
     */
    public function Create()
    {
        //获取注册信息(各项均必填)
        $admin_name = IFilter::act(IReq::get('admin_name'));
        $password = IFilter::act(IReq::get('password'));
        $email = IFilter::act(IReq::get('email'));
        $phone = IFilter::act(IReq::get('phone'));
        //创建管理员对象
        $adminDB = new IModel('admin');
        if(!$admin_name || !$password || !$email || !$phone)
            JsonResult::fail('各项均不能为空');
        //TODO :可在查表时使用or优化
        if($adminDB->getObj("admin_name = '".$admin_name."'"))
            JsonResult::fail('用户名已被注册');
        if($adminDB->getObj("email = '".$email."'"))
            JsonResult::fail('邮箱已被注册');
        if($adminDB->getObj("phone = '".$phone."'"))
            JsonResult::fail('手机已被注册');
        $admin = array(
            'admin_name' => $admin_name,
            'password' => $password,
            'email' => $email,
            'phone' => $phone
        );
        $adminDB->setData($admin);
        $admin_id = $adminDB->add();
        JsonResult::success($admin_id);
    }

    /**
     * 更新管理员信息
     * @param int id 管理员ID
     * @param string name 姓名
     * @param string email 电子邮箱
     * @param string phone 手机号
     * 
     * @return 更新后的管理员信息
     */
    public function Update()
    {
        //获取需要更新的管理员ID(必需),以及要更新的信息(姓名，邮箱，手机号)
        $admin_id = IFilter::act(IReq::get('id'),'int');
        $name = IFilter::act(IReq::get('name'));
        $email = IFilter::act(IReq::get('email'));
        $phone = IFilter::act(IReq::get('phone'));
        //创建管理员对象
        $adminDB = new IModel('admin');
        //检查管理员是否存在
        if(!$admin_id || !$adminDB->getObj('id = '.$admin_id))
            JsonResult::fail('管理员不存在');
        $admin = array(
            'id' => $admin_id
        );
        //检查字段是否为空，为空则默认不修改
        if($name)
            $admin['name'] = $name;
        if($email)
            $admin['email'] = $email;
        if($phone)
            $admin['phone'] = $phone;
        //更新数据库信息
        $adminDB->setData($admin);
        $adminDB->update('id = '.$admin_id);
        JsonResult::success($adminDB->getObj('id = '.$admin_id));
    }

    /**
     * 删除管理员
     * @param int id 管理员ID
     */
    public function Delete()
    {
        //获取管理员ID
        $id = IFilter::act(IReq::get('id'),'int');
        //创建管理员对象
        $adminDB = new IModel('admin');
        //检查管理员是否存在，且未被删除
        if (!$id || !$adminDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该管理员不存在');
        //删除状态修改为1
        $adminDB->setData(array(
            'is_del' => 1
        ));
        $adminDB->update('id = '.$id);
    }

    /**
     * 恢复已删除管理员
     * @param int id 管理员ID
     */
    public function Restore()
    {
        //获取管理员ID
        $id = IFilter::act(IReq::get('id'),'int');
        //创建管理员对象
        $adminDB = new IModel('admin');
        //检查管理员是否存在
        if(!$id || !$adminDB->getObj('id = '.$id))
            JsonResult::fail('该管理员不存在');
        //检查该管理员是否未被删除
        if($adminDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该管理员未被删除');
        //删除状态改为0
        $adminDB->setData(array(
            'is_del' => 0
        ));
        //数据库更新
        $adminDB->update('id = '.$id);
        JsonResult::success($adminDB->getObj('id = '.$id));
    }
}