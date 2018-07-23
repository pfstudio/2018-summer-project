<?php
/**
 * 用户相关API
 */
class User extends IController
{
    /**
     * 学生自主注册
     * @param string phone 手机号
     * @param string pin   短信验证码
     * 
     * @return JWT Token
     */
    public function CreateWithPhone()
    {
        //获取手机号和验证码
        $phone = IFilter::act(IReq::get('phone'));
        $pin = IFilter::act(IReq::get('pin'),'int');
        //验证码模块还未完成
        if(!($phone && true))
        {
            JsonResult::fail('验证码错误');
        }
        //创建数据库对象对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        //检查手机号是否重复
        if($userDB->getObj("phone='".$phone."'"))
        {
            JsonResult::fail('改手机号已被注册');
        }
        // 创建用户对象
        $user = array(
            'phone' => $phone,
            'role'  => 0,
            'create_time' => date('Y-m-d H:i:s')
        );
        // 添加用户记录
        $userDB->setData($user);
        // 添加返回的是user的id
        $user_id = $userDB->add();
        // 初始化学生信息
        // 这里只初始化user_id
        $student = array('user_id' => $user_id);
        $studentDB->setData($student);
        $studentDB->add();
        //注册完毕进行登录操作，获得token,并将id以及token返回
    }
    
    /**
     * 更换手机号
     * @param string phone 手机号
     * @param string pin   短信验证码
     */
    public function ChangePhone()
    {
        //id应通过token获取
        $user_id = IFilter::act(IReq::get('id'),'int');
        $phone = IFilter::act(IReq::get('phone'));
        $pin = IFilter::act(IReq::get('pin'),'int');
        //创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        //此处还需进行用户权限验证
        if(true)
        {}
        // 检验用户是否存在，以及是否处在软删除状态
        if(!$user_id || !$userDB->getObj('id = '.$user_id.' and is_del = 0'))
        {
            die(JsonResult::fail('该用户不存在'));
        }
        //验证码验证模块
        if(!($phone && true))
        {
            die(JsonResult::fail('验证码错误'));
        }
        //检查手机号是否重复
        if($userDB->getObj("phone='".$phone."'"))
        {
            die(JsonResult::fail('该手机号已被注册'));
        }
        $user = array(
            'id' => $user_id,
            'phone' => $phone
        );
        $userDB->setData($user);
        $userDB->update('id = '.$user_id);
    }
    

    /**
     * 后台添加学生
     * @param string phone 手机号
     * 
     * @return int id 学生ID
     */
    public function CreateStudent()
    {
        $phone = IFilter::act(IReq::get('phone'));
        //创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        if ($userDB->getObj("phone='".$phone."'"))
        {
            JsonResult::fail('改手机号已被注册');
        }
        $user = array(
            'phone' => $phone,
            'role' => 0,
            'create_time' => date('Y-m-d H:i:s')
        );
        $userDB->setData($user);
        $user_id = $userDB->add();
        $student = array('user_id' => $user_id);
        $studentDB->setData($student);
        $studentDB->add();
    }

    /**
     * 后台添加教师
     * @param string phone 手机号
     * 
     * @return int id 教师ID
     */
    public function CreateTeacher()
    {
        $phone = IFilter::act(IReq::get('phone'));
        $userDB = new IModel('user');
        $teacherDB = new IModel('teacher');
        if ($userDB->getObj("phone='".$phone."'"))
        {
            JsonResult::fail('改手机号已被注册');
        }
        $user = array(
            'phone' => $phone,
            'role' => 0,
            'create_time' => date('Y-m-d H:i:s')
        );
        $userDB->setData($user);
        $user_id = $userDB->add();
        $teacher = array('user_id' => $user_id);
        $teacherDB->setData($teacher);
        $teacherDB->add();
        echo $user_id;
    }

    /**
     * 删除用户（学生/教师）
     * 仅采用软删除方法
     * @param int id 用户ID
     */
    public function Delete()
    {
        $id = IFilter::act(IReq::get('id'),'int');
        $userDB = new IModel('user');
        if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
        {
            JsonResult::fail('该用户不存在');
        }
        $userDB->setData(array(
            'is_del' => 1
        ));
        $userDB->update('id = '.$id);
    }

    /**
     * 恢复软删除用户
     * @param int id 用户ID
     */
    public function Restore()
    {
        $id = IFilter::act(IReq::get('id'),'int');
        $userDB = new IModel('user');
        //检测用户是否存在
        if(!$id || !$userDB->getObj('id = '.$id))
        {
            JsonResult::fail('该用户不存在');
        }
        //检测用户是否已被删除
        if($userDB->getObj('id = '.$id.' and is_del = 0'))
        {
            JsonResult::fail('该用户未被删除');
        }
        $userDB->setData(array(
            'is_del' => 0
        ));
        $userDB->update('id = '.$id);
    }
}