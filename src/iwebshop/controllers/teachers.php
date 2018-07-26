<?php
class Teachers extends IController
{
    public $layout = 'admin';

	function teacher_edit()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		$teacher = array();

		if($id)
		{
            $teacher = Api::run('getTeacherInfo',array('id'=>$id));
			if(!$teacher)
			{
				$this->teacher_list();
				Util::showMessage("没有找到相关记录！");
				exit;
			}
		}
		$this->setRenderData(array('teacher' => $teacher));
		$this->redirect('teacher_edit');
	}

	function teacher_save()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
        $name = IFilter::act(IReq::get('name'));
		$is_lock = IFilter::act(IReq::get('is_lock'), 'int');
        $sex = IFilter::act(IReq::get('sex'), 'int');
        $phone = IFilter::act(IReq::get('phone'));
        $email = IFilter::act(IReq::get('email'));
        $wechat = IFilter::act(IReq::get('wechat'));
        $introduction = IReq::get('introduction');
        //创建数据库对象
        $userDB = new IModel('user');
        $teacherDB = new IModel('teacher');
		// 检验参数
		if($is_lock != 0 && $is_lock!= 1)
            $this->setError('教师状态错误');
        if($sex != 0 && $sex != 1)
            $this->setError('性别仅为 0: 男 1: 女');
        if(!$phone || !Reg::phone($phone))
            $this->setError('手机号格式错误');
        if(!$id && $userDB->getObj('phone = '.$phone))
            $this->setError('该手机号已被注册');
        if($email && !Reg::email($email))
            $this->setError('邮箱格式错误');
		//操作失败表单回填
		if($errorMsg = $this->getError())
		{
			$this->setRenderData(array('teacher' => $_POST));
			$this->redirect('teacher_edit',false);
			Util::showMessage($errorMsg);
		}
        // 设置需要更新的实体
        $user = array(
            'is_lock' => $is_lock,
            'phone'   => $phone
        );
        $teacher = array(
            'name'         => $name,
            'sex'          => $sex,
            'email'        => $email,
            'wechat'       => $wechat,
            'introduction' => $introduction
        );

        //附件上传$_FILE
		if($_FILES)
		{
			$uploadObj = new PhotoUpload();
			$uploadObj->setIterance(false);
			$photoInfo = $uploadObj->run();

			// 照片上传
			if(isset($photoInfo['photo']['img']) && file_exists($photoInfo['photo']['img']))
			{
				$teacher['photo'] = $photoInfo['photo']['img'];
			}
        }

        // 添加教师
		if(!$id)
		{
            $user['create_time'] = date('Y-m-d H:i:s');
            $user['job'] = 1;
            $userDB->setData($user);
            $user_id = $userDB->add();
            $teacher['user_id'] = $user_id;
            $teacherDB->setData($teacher);
            $teacherDB->add();
		}
		// 编辑教师信息
		else
		{
            $userDB->setData($user);
            $userDB->update('id = '.$id);
            $teacherDB->setData($teacher);
            $teacherDB->update('user_id = '.$id);
		}
		$this->redirect('teacher_list');
	}

    function teacher_list()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("teacher as t");
        $query->join   = 'left join user as u on u.id = t.user_id';
        $query->where  = 'u.is_del = 0';
        $query->fields = 'u.id,t.*,u.phone,u.is_lock';
        $query->order  = 't.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('teacher_list');
    }

    function recycling()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("teacher as t");
        $query->join   = 'left join user as u on u.id = t.user_id';
        $query->where  = 'u.is_del = 1';
        $query->fields = 'u.id,t.*,u.phone,u.is_lock';
        $query->order  = 't.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('recycling');
    }

    function introduction()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
		if(!$id) IError::show(403);
		$courseDB = new IModel('teacher');
		$course = $courseDB->getObj('user_id = '.$id);
		if(!$course) IError::show(404, '无法找到该信息');
		echo $course['introduction'];
    }
    
    function teacher_search()
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
        $query = new IQuery("teacher as t");
        $query->join   = 'left join user as u on u.id = t.user_id';
        $query->where  = 'u.is_del = 0 and '.$where;
        $query->fields = 'u.id,t.*,u.phone,u.is_lock';
        $query->order  = 't.user_id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('teacher_search');
    }
}