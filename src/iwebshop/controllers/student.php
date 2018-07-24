<?php
/**
 * 学生
 * 
 * @author yiluomyt
 */
class Student extends IController
{
    /**
     * 获取单条学生信息
     * @param int id 学生ID
     */
    public function Get()
    {
        // 获取参数
        $id = IFilter::act(IReq::get('id'),'int');
        // 创建数据库对象
        $userDB = new IModel('user');
        // 检验用户是否存在
        if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该学生不存在');
        // 查询student表
        $studentDB = new IQuery('student as s');
        $studentDB->join = 'left join user as u on u.id = s.user_id';
        $studentDB->fields = 's.user_id as id,s.name,u.phone,s.parents_phone,'.
            's.address,s.wechat,s.sex,s.birthday,s.grade,u.create_time,u.last_time';
        $studentDB->where = 's.user_id = '.$id;
        $result = $studentDB->find()[0];
        JsonResult::success($result);
    }

    /**
     * 获取学生列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 学生姓名(可选)
     */
    public function List()
    {
        $page     = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        $name     = IFilter::act(IReq::get('name'));
        $studentDB = new IQuery('student as s');
        $studentDB->join = 'left join user as u on u.id = s.user_id';
        $studentDB->fields = 's.user_id as id,s.name,u.phone,s.parents_phone,'.
        's.address,s.wechat,s.sex,s.birthday,s.grade,u.create_time,u.last_time';
        $wheres = array("u.is_del = 0");
        if($name)
            array_push($wheres, "s.name like '".$name."%'");
        $studentDB->where = join(" and ", $wheres);
        $studentDB->page = $page;
        $studentDB->pagesize = $pagesize;
        $result = $studentDB->find();
        JsonResult::success(array(
            'totalpage' => $studentDB->paging->totalpage,
            'index'     => $studentDB->paging->index,
            'pagesize'  => $studentDB->paging->pagesize,
            'result'    => $result
        ));
    }

    /**
     * 更新学生信息
     * @param int id 学生ID
     * @param string name 姓名(可选)
     * @param int sex 性别 0: 男 1: 女(可选)
     * @param string wechat 微信号(可选)
     * @param string address 联系地址(可选)
     * @param string parents_phone 家长手机号(可选)
     * @param string grade 学生年级(可选)
     * @param date birthday 学生生日(可选)
     * 
     * @return 更新后的学生信息
     */
    public function Update()
    {
        // 获取参数
        $user_id  = IFilter::act(IReq::get('id'), 'int');
        $name     = IFilter::act(IReq::get('name'));
        $sex      = IFilter::act(IReq::get('sex'), 'int');
        $wechat   = IFilter::act(IReq::get('wechat'));
        $address  = IFilter::act(IReq::get('address'));
        $parents_phone = IFilter::act(IReq::get('parents_phone'));
        $grade    = IFilter::act(IReq::get('grade'));
        $birthday = IFilter::act(IReq::get('birthday'));
        // 创建数据库对象
        $userDB    = new IModel('user');
        $studentDB = new IModel('student');
        // 检验用户是否存在
        if(!$user_id || !$userDB->getObj('id = '.$user_id.' and is_del = 0'))
            JsonResult::fail('该学生不存在');
        // 检验参数格式
        if($sex && ($sex != 0 && $sex != 1))
            $this->setError('性别只能为 0: 男 1: 女');
        if($parents_phone && !Reg::phone($parents_phone))
            $this->setError('父母手机号格式错误');
        if($birthday)
        {
            if(Reg::date($birthday))
                $birthday = DateTime::createFromFormat('Y-m-d', $birthday);
            else
                $this->setError('生日格式错误');
        }
        // 若参数检验过程中有错误，返回错误信息
        if($errors = $this->getAllError())
            JsonResult::fail($errors);
        // 设置需要更新的实体
        $student = array();
        if($name) $student['name'] = $name;
        if($sex) $student['sex'] = $sex;
        if($wechat) $student['wechat'] = $wechat;
        if($address) $student['address'] = $address;
        if($parents_phone) $student['parents_phone'] = $parents_phone;
        if($grade) $student['grade'] = $grade;
        if($birthday) $student['birthday'] = $birthday;
        // 更新学生信息
        $studentDB->setData($student);
        $studentDB->update('user_id = '.$user_id);
        // 返回更新后的学生信息
        JsonResult::success($studentDB->getObj('user_id = '.$user_id));
    }

    /**
     * 删除学生
     * @param int id 学生ID
     */
    public function Delete()
    {
        // 获取参数
        $id  = IFilter::act(IReq::get('id'), 'int');
        // 创建数据库对象
        $userDB = new IModel('user');
        // 检验用户是否存在
        if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该学生不存在');
        // 软删除
        $userDB->setData(array('is_del' => 1));
        $userDB->update('id = '.$id);
    }

    /**
     * 恢复已删除学生
     * @param int id 学生ID
     */
    public function Restore()
    {
        // 获取参数
        $id = IFilter::act(IReq::get('id'),'int');
        // 创建数据库对象
        $userDB = new IModel('user');
        //检测用户是否存在
        if(!$id || !$userDB->getObj('id = '.$id))
            JsonResult::fail('该用户不存在');
        //检测用户是否已被删除
        if($userDB->getObj('id = '.$id.' and is_del = 0'))
            JsonResult::fail('该用户未被删除');
        // 恢复用户
        $userDB->setData(array('is_del' => 0));
        $userDB->update('id = '.$id);
    }

    /**
     * 获取学生上课的教学班
     * @param int id 学生ID
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
        $classesHandle->join = 'left join class_student as cs on cs.class_id = c.id';
        $classesHandle->where = 'cs.student_id = '.$user_id.' and c.is_del =0';
        $classes = $classesHandle->find();
        JsonResult::success($classes);
    }
}