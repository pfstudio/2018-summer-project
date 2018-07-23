<?php
/**
 * 教师
 */
class Teacher extends IController
{
    /**
     * 获取单条教师信息
     * @param int id 教师ID
     */
    public function Get()
    {  
        // 获取参数
        $id = IFilter::act(IReq::get('id'),'int');
        // 创建数据库对象
        $userDB = new IModel('user');
        // 检验用户是否存在
        if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该教师不存在');
        // 查询teacher表
        $teacherDB = new IQuery('teacher');
        $teacherDB->where = 'user_id = '.$id;
        JsonResult::success($teacherDB->find()); 
    }

    /**
     * 获取教师列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 教师姓名(可选)
     */
    public function List()
    {
        $page     = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        $name     = IFilter::act(IReq::get('name'));
        $teacherDB = new IQuery('teacher');
        if($name)
            $teacherDB->where = "name like '".$name."%'";
        $teacherDB->page = $page;
        $result = $teacherDB->find();
        JsonResult::success(array(
            'totalpage' => $teacherDB->paging->totalpage,
            'index'     => $teacherDB->paging->index,
            'pagesize'  => $teacherDB->paging->pagesize,
            'result'    => $result
        ));
    }

    /**
     * 更新教师信息
     * @param int id 教师ID
     * @param string name 姓名
     * @param int sex 性别 0: 男 1: 女
     * @param string email 电子邮箱
     * @param string wechat 微信号
     * @param string photo 教师照片URL
     * @param string introduction 教师介绍
     * 
     * @return 更新后的教师信息
     */
    public function Update()
    {
        // 获取参数
        $user_id     = IFilter::act(IReq::get('id'), 'int');
        $name   = IFilter::act(IReq::get('name'));
        $sex    = IFilter::act(IReq::get('sex'), 'int');
        $wechat = IFilter::act(IReq::get('wechat'));
        $email  = IFilter::act(IReq::get('email'));
        $photo  = IFilter::act(IReq::get('photo'));
        $introduction = IFilter::act(IReq::get('introduciton'));
        // 创建数据库对象
        $userDB    = new IModel('user');
        $teacherDB = new IModel('teacher');
        // 检验用户是否存在
        if(!$user_id || !$userDB->getObj('id = '.$user_id.' and is_del = 0'))
            JsonResult::fail('该教师不存在');
        // 检验参数格式
        // 检验性别
        if($sex && ($sex != 0 && $sex != 1))
            $this->setError('性别只能为 0: 男 1: 女');
        //检验邮箱
        if($email)
        {   
            if(!Reg::email($email))
            $this->setError('邮箱格式错误');
            if($teacherDB->getObj("email='".$email."'"))
            JsonResult::fail('该邮箱已被注册');
        }  
        //若参数检验过程中有错误，返回错误信息
        if($errors = $this->getAllError())
            JsonResult::fail($errors);
        // 设置需要更新的实体
        $teacher = array();
        if($name) $teacher['name'] = $name;
        if($sex) $teacber['sex'] = $sex;
        if($wechat) $teacher['wechat'] = $wechat;
        if($email) $teacher['email'] = $email;
        if($photo) $teacher['photo'] = $photo;
        if($introduction) $teacher['introduction'] = $introduction;
        // 更新教师信息
        $teacherDB->setData($teacher);
        $teacherDB->update('user_id = '.$user_id);
        // 返回更新后的教师信息
        JsonResult::success($teacherDB->getObj('user_id = '.$user_id));
    }

    /**
     * 删除教师
     * @param int id 教师ID
     */
    public function Delete()
    {
      // 获取参数
      $id  = IFilter::act(IReq::get('id'), 'int');
      // 创建数据库对象
      $userDB = new IModel('user');
      // 检验用户是否存在
      if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
          JsonResult::fail('该教师不存在');
      // 软删除
      $userDB->setData(array('is_del' => 1));
      $userDB->update('id = '.$id);
    }

    /**
     * 恢复已删除教师
     * @param int id 教师ID
     */
    public function Restore()
    {
     // 获取参数
     $id = IFilter::act(IReq::get('id'),'int');
     // 创建数据库对象
     $userDB = new IModel('user');
     //检测用户是否存在
     if(!$id || !$userDB->getObj('id = '.$id))
         JsonResult::fail('该教师不存在');
     //检测用户是否已被删除
     if($userDB->getObj('id = '.$id.' and is_del = 0'))
         JsonResult::fail('该教师未被删除');
     // 恢复用户
     $userDB->setData(array('is_del' => 0));
     $userDB->update('id = '.$id);
    }

    /**
     * 获取教师所教授的教学班
     * @param int id 教师ID
     * 
     * @return 教学班列表
     */
    public function GetClasses()
    {
      // 获取参数
      $user_id  = IFilter::act(IReq::get('id'), 'int');
      // 创建数据库对象
      $userDB = new IModel('user');
      //检测用户是否存在
      if(!$user_id || !$userDB->getObj('id = '.$user_id))
          JsonResult::fail('该用户不存在');
      $classesHandle = new IQuery('teaching_class as c');
      $classesHandle->fields = 'c.id,c.course_id,c.name,c.price,c.introduction';
      $classesHandle->join = 'left join class_teacher as cs on cs.class_id = c.id';
      $classesHandle->where = 'cs.class_id = '.$user_id;
      $classes = $classesHandle->find();
      JsonResult::success($classes);
    }
}