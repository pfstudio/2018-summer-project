<?php
/**
 * @brief 学生模块
 * @class Student
 * @author yiluomyt
 */
class Student extends IController
{
    /**
     * @brief 获取单条学生信息
     * @param id
     */
    function get()
    {

    }

    /**
     * @brief 新建学生
     * @param phone, pin
     */
    function create()
    {
        // 获取手机号和验证码
        // IFilter::act 过滤传入的参数并转换为对应类型，默认为string
        // IReq::get 获取参数
        $phone = IFilter::act(IReq::get('phone'));
        $pin   = IFilter::act(IReq::get('pin'), 'int');
        // 检验手机号和验证码
        // 验证码模块还未完成，默认通过
        if(!($phone && true))
        {
            die(JsonResult::fail('验证码错误'));
        }
        // 创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        // 检验手机号是否重复
        if($userDB->getObj("phone='".$phone."'"))
        {
            die(JsonResult::fail('该手机号已被注册'));
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
        // 返回结果，用户的id
        echo JsonResult::success($user_id);
    }

    /**
     * @brief 更新学生信息
     * @param 对应student表中的字段, id必填
     */
    function update()
    {
        // 获取参数
        $user_id = IFilter::act(IReq::get('id'), 'int');
        // 其他参数
        $sex = IFilter::act(IReq::get('sex'), 'int');
        // ...

        // 创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');

        // 检验用户是否存在
        // is_del是软删除字段
        if(!$user_id || !$userDB->getObj('id = '.$user_id.' and is_del = 0'))
        {
            die(JsonResult::fail('该用户不存在'));
        }

        // 检验其他参数
        if($sex != 0 && $sex != 1)
        {
            $this->setError('性别只能为 0: 男 1: 女');
        }
        // ...

        // 若参数检验过程中有错误，返回错误信息
        if($errors = $this->getAllError())
        {
            die(JsonResult::fail($errors));
        }

        // 创建对象
        $student = array(
            'sex' => $sex
        );
        $studentDB->setData($student);
        $studentDB->update('user_id = '.$user_id);
        // 返回更新后的用户信息
        echo JsonResult::success($studentDB->getObj('user_id = '.$user_id));
    }

    /**
     * 删除学生
     * @param id, true_del
     */
    function delete()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        $true_del = IFilter::act(IReq::get('true_del'), 'bool');

        // 创建数据库对象
        $userDB = new IModel('user');

        // TODO: 这里把判断逻辑改一下分别对应软/硬删除
        // 检验参数状态
        if(!$id || !$userDB->getObj('id = '.$id.' and is_del = 0'))
        {
            die(JsonResult::fail('该用户不存在'));
        }

        if($true_del)
        // 硬删除
        {
            $userDB->del('id = '.$id);
        }
        else
        // 软删除
        {
            // 软删除将is_del设为1
            $userDB->setData(array(
                'is_del' => 1
            ));
            $userDB->update('id = '.$id);
        }

        echo JsonResult::success();
    }
}
?>