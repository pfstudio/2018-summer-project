<?php
/**
 * @brief 学生模块
 * @class Student
 */
class Student extends IController
{
    /**
     * @brief 获取单条学生信息
     */
    function get()
    {
        $user_id = IFilter::act(IReq::get('id'));
        echo $user_id;
    }

    /**
     * @brief 新建学生
     */
    function create()
    {
        // 获取用户名和密码
        $username = IFilter::act(IReq::get('username'));
        $password = IReq::get('password');
        // 检验用户名和密码格式
        if(!$username || $password == '')
        {
            die(JsonResult::fail('请提供正确的用户名和密码'));
        }
        // 创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        // 检验用户名状态
        if($userDB->getObj("username='".$username."'"))
        {
            die(JsonResult::fail('用户名重复'));
        }
        // 创建用户对象
        $user = array(
            'username' => $username,
            'password' => md5($password)
        );
        // 添加用户记录
        $userDB->setData($user);
        $user_id = $userDB->add();
        // 初始化学生信息
        $student = array('user_id' => $user_id);
        $studentDB->setData($student);
        $studentDB->add();
        // 返回结果，用户的id
        echo JsonResult::success($user_id);
    }

    /**
     * @brief 更新学生信息
     */
    function update()
    {
        // 获取参数
        $user_id = IFilter::act(IReq::get('id'), 'int');
        $name = IFilter::act(IReq::get('name'));
        $phone = IFilter::act(IReq::get('phone'));
        $parents_phone = IFilter::act(IReq::get('parents_phone'));
        $area = IFilter::act(IReq::get('area'));
        $address = IFilter::act(IReq::get('address'));
        $qq = IFilter::act(IReq::get('qq'));
        $wechat = IFilter::act(IReq::get('wechat'));
        $sex = IFilter::act(IReq::get('sex'), 'int');
        $birthday = IFilter::act(IReq::get('birthday'));
        $email = IFilter::act(IReq::get('email'));
        $grade = IFilter::act(IReq::get('grade'));

        // 创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');

        // 检验参数格式
        if(!$user_id || !$userDB->getObj('id = '.$user_id)
        || !$studentDB->getObj('user_id = '.$user_id.' and status = 0'))
        {
            die(JsonResult::fail('该用户不存在'));
        }

        if(!($sex == 0 || $sex == 1))
        {
            $this->setError('性别仅限 0:男 1:女');
        }

		if($email && $memberDB->getObj("email='".$email."' and user_id != ".$user_id))
		{
			$this->setError('邮箱重复');
		}

		if($phone && $memberDB->getObj("phone='".$phone."' and user_id != ".$user_id))
		{
			$this->setError('手机号码重复');
		}

        if($errors = $this->getAllError())
        {
            die(JsonResult::fail($errors));
        }

        // 创建对象
        $student = array(
            'name' => $name,
            'phone' => $phone,
            'parents_phone' => $parents_phone,
            'area' => $area,
            'address' => $address,
            'qq' => $qq,
            'wechat' => $wechat,
            'sex' => $sex,
            'birthday' => $birthday,
            'email' => $email,
            'grade' => $grade
        );
        $studentDB->setData($student);
        $studentDB->update('user_id = '.$user_id);
        // 返回更新后的用户信息
        echo JsonResult::success($studentDB->getObj('user_id = '.$user_id));
    }

    /**
     * 删除学生
     */
    function delete()
    {
        $user_id = IFilter::act(IReq::get('id'), 'int');
        $true_del = IFilter::act(IReq::get('true_del'), 'bool');

        // 创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
        
        // 检验参数状态
        if(!$user_id || !$userDB->getObj('id = '.$user_id))
        {
            die(JsonResult::fail('该用户不存在'));
        }

        if($true_del)
        // 硬删除
        {
            $userDB->del('id = '.$user_id);
        }
        else
        // 软删除
        {
            $studentDB->setData(array(
                'status' => 1
            ));
            $studentDB->update('user_id = '.$user_id);
        }

        echo JsonResult::success();
    }
}
?>