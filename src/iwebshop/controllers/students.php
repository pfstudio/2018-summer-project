<?php
class Students extends IController
{
    public $layout = 'admin';

	function student_edit()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		$student = array();

		if($id)
		{
            $student = Api::run('getStudentInfo',array('id'=>$id));
			if(!$student)
			{
				$this->student_list();
				Util::showMessage("没有找到相关记录！");
				exit;
			}
		}
		$this->setRenderData(array('student' => $student));
		$this->redirect('student_edit');
	}

	function student_save()
	{
        $id = IFilter::act(IReq::get('id'), 'int');
        $name = IFilter::act(IReq::get('name'));
		$is_lock = IFilter::act(IReq::get('is_lock'), 'int');
        $sex = IFilter::act(IReq::get('sex'), 'int');
        $phone = IFilter::act(IReq::get('phone'));
        $parents_phone = IFilter::act(IReq::get('parents_phone'));
        $grade = IFilter::act(IReq::get('grade'));
        $birthday = IFilter::act(IReq::get('birthday'));
        $wechat = IFilter::act(IReq::get('wechat'));
        $address = IFilter::act(IReq::get('address'));
        //创建数据库对象
        $userDB = new IModel('user');
        $studentDB = new IModel('student');
		// 检验参数
		if($is_lock != 0 && $is_lock!= 1)
            $this->setError('学生状态错误');
        if($sex != 0 && $sex != 1)
            $this->setError('性别仅为 0: 男 1: 女');
        if(!$phone || !Reg::phone($phone))
            $this->setError('手机号格式错误');
        if(!$id && $userDB->getObj('phone = '.$phone))
            $this->setError('该手机号已被注册');
        if($parents_phone && !Reg::phone($parents_phone))
            $this->setError('家长电话格式错误');
        if($birthday && !Reg::date($birthday))
            $this->setError('生日格式错误');
		//操作失败表单回填
		if($errorMsg = $this->getError())
		{
			$this->setRenderData(array('student' => $_POST));
			$this->redirect('student_edit',false);
			Util::showMessage($errorMsg);
		}
        // 设置需要更新的实体
        $user = array();
        $user['is_lock'] = $is_lock;
        if($phone) $user['phone'] = $phone;
        $student = array();
        if($name) $student['name'] = $name;
        $student['sex'] = $sex;
        if($wechat) $student['wechat'] = $wechat;
        if($address) $student['address'] = $address;
        if($parents_phone) $student['parents_phone'] = $parents_phone;
        if($grade) $student['grade'] = $grade;
        if($birthday) $student['birthday'] = $birthday;

        // 添加学生
		if(!$id)
		{
            $user['create_time'] = date('Y-m-d H:i:s');
            $user['job'] = 0;
            $userDB->setData($user);
            $user_id = $userDB->add();
            $student['user_id'] = $user_id;
            $studentDB->setData($student);
            $studentDB->add();
		}
		// 编辑学生信息
		else
		{
            $userDB->setData($user);
            $userDB->update('id = '.$id);
            $studentDB->setData($student);
            $studentDB->update('user_id = '.$id);
		}
		$this->redirect('student_list');
	}

    function student_list()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("student as s");
        $query->join   = 'left join user as u on u.id = s.user_id';
        $query->where  = 'u.is_del = 0';
        $query->fields = 'u.id,s.*,u.phone,u.is_lock';
        $query->order  = 's.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('student_list');
    }

    function recycling()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("student as s");
        $query->join   = 'left join user as u on u.id = s.user_id';
        $query->where  = 'u.is_del = 1';
        $query->fields = 'u.id,s.*,u.phone,u.is_lock';
        $query->order  = 's.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('recycling');
    }

    function student_search()
    {
        $this->layout = 'search';
        $search = IFilter::act(IReq::get('search'),'strict');
		$keywords = IFilter::act(IReq::get('keywords'));
        $where = ' 1 ';
		if($search && $keywords)
		{
			$where .= " and $search like '%{$keywords}%' ";
        }
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("student as s");
        $query->join   = 'left join user as u on u.id = s.user_id';
        $query->where  = 'u.is_del = 0 and '.$where;
        $query->fields = 'u.id,s.*,u.phone,u.is_lock';
        $query->order  = 's.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('student_search');
    }
}