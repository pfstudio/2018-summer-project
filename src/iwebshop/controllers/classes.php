<?php
class Classes extends IController
{
    public $layout = 'admin';

    /**
	 * 添加教学班
	 */
	function class_edit()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		$class = array();

		//编辑教学班信息
		if($id)
		{
            $class = Api::run('getClassInfo',array('id'=>$id));
			if(!$class)
			{
				$this->class_list();
				Util::showMessage("没有找到相关记录！");
				exit;
			}
		}
		$this->setRenderData(array('class' => $class));
		$this->redirect('class_edit');
	}

	//保存教学班信息
	function class_save()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
		$name = IFilter::act(IReq::get('name'));
		$course_id = IFilter::act(IReq::get('course_id'), 'int');
		$price = IFilter::act(IReq::get('price'), 'float');
		$total_num = IFilter::act(IReq::get('total_num'), 'int');
		$is_lock = IFilter::act(IReq::get('is_lock'), 'int');
		$introduction = IReq::get('introduction');
		$comment = IFilter::act(IReq::get('comment'));
		//创建教学班对象
        $classDB = new IModel('teaching_class');
		// 检查教学班状态
		if($is_lock != 0 && $is_lock!= 1)
			$this->setError('教学班状态错误');
		//操作失败表单回填
		if($errorMsg = $this->getError())
		{
			$this->setRenderData(array('class' => $_POST));
			$this->redirect('class_edit',false);
			Util::showMessage($errorMsg);
		}
		$class = array(
			'name' => $name,
			'course_id' => $course_id,
			'price' => $price,
			'total_num' => $total_num,
			'is_lock' => $is_lock,
			'introduction' => $introduction,
			'comment' => $comment
		);

		// 添加教学班
		if(!$id)
		{
			$classDB->setData($class);
			$classDB->add();
		}
		// 编辑教学班
		else
		{
			$classDB->setData($class);
			$classDB->update('id = '.$id);
		}
		$this->redirect('class_list');
	}

    function class_list()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("teaching_class as tc");
        $query->join = 'left join course as c on c.id = tc.course_id';
        $query->where  = 'tc.is_del = 0';
        $query->fields = 'tc.id,tc.name,tc.price,tc.total_num,tc.selected_num,tc.is_lock,tc.comment,'.
        				 'c.id as course_id,c.name as course_name';
        $query->order  = 'tc.id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('class_list');
	}

	function class_students()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		if(!$id) IError::show(403, '访问方式错误');

		// 获取教学班信息
		$class = Api::run('getClassInfo',array('id'=>$id));
		if(!$class)
		{
			$this->class_list();
			Util::showMessage("没有找到相关记录！");
			exit;
		}
		$this->setRenderData(array('class' => $class));
		$page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("class_student as cs");
		$query->join = 'left join teaching_class as tc on tc.id = cs.class_id '.
					   'left join student as s on s.user_id = cs.student_id '.
					   'left join user as u on u.id = cs.student_id';
		$query->fields = 's.user_id as id,s.name,u.phone,s.parents_phone,s.address,s.wechat,s.sex,s.birthday,s.grade';
		$query->where  = 'cs.class_id = '.$id.' and tc.is_del = 0 and u.is_del = 0';
		$query->order  = 'cs.id desc';
        $query->page   = $page;
		$this->query   = $query;
        $this->redirect('class_students');
	}

	function class_teachers()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		if(!$id) IError::show(403, '访问方式错误');

		// 获取教学班信息
		$class = Api::run('getClassInfo',array('id'=>$id));
		if(!$class)
		{
			$this->class_list();
			Util::showMessage("没有找到相关记录！");
			exit;
		}
		$this->setRenderData(array('class' => $class));
		$page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("class_teacher as ct");
		$query->join = 'left join teaching_class as tc on tc.id = ct.class_id '.
					   'left join teacher as t on t.user_id = ct.teacher_id '.
					   'left join user as u on u.id = ct.teacher_id';
		$query->fields = 't.user_id as id,t.name,t.sex,u.phone,t.email,t.wechat,t.photo,t.introduction';
		$query->where  = 'ct.class_id = '.$id.' and tc.is_del = 0 and u.is_del = 0';
		$query->order  = 'ct.id desc';
        $query->page   = $page;
		$this->query   = $query;
        $this->redirect('class_teachers');
	}

    function recycling()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("teaching_class as tc");
        $query->join = 'left join course as c on c.id = tc.course_id';
        $query->where  = 'tc.is_del = 1';
        $query->fields = 'tc.id,tc.name,tc.price,tc.total_num,tc.selected_num,tc.is_lock,tc.introduction,tc.comment,'.
        'c.id as course_id,c.name as course_name';
        $query->order  = 'tc.id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('recycling');
	}

	function introduction()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
		if(!$id) IError::show(403);
		$courseDB = new IModel('teaching_class');
		$course = $courseDB->getObj('id = '.$id);
		if(!$course) IError::show(404, '无法找到该信息');
		echo $course['introduction'];
	}
	
	function class_register()
	{
		$page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
		$query = new IQuery('register as r');
		$query->join = 'left join teaching_class as tc on tc.id = r.class_id '.
					   'left join course as c on c.id = tc.course_id '.
					   'left join student as s on s.user_id = r.student_id '.
					   'left join user as u on u.id = s.user_id';
		$query->fields = 'r.id,r.student_id,s.name as student_name,r.class_id,tc.name as class_name,'.
						 'c.id as course_id,c.name as course_name,'.
						 'tc.selected_num,tc.total_num,tc.is_lock,tc.comment';
		$query->where = 'tc.is_del = 0 and u.is_del = 0';
		$query->order = 'r.id desc';
		$query->page = $page;
		$this->query = $query;
		$this->redirect('class_register');
	}

	function register_pass()
	{
		$register_ids = IReq::get('check');
		$register_ids = is_array($register_ids) ? $register_ids : array($register_ids);
		$register_ids = IFilter::act($register_ids,'int');
		if($register_ids)
		{
			$ids = implode(',',$register_ids);
			if($ids)
			{
				// 检查该学生和教学班是否存在
                $registerHandle = new IQuery('register as r');
				$registerHandle->join = 'left join student as s on s.user_id = r.student_id '.
										'left join teaching_class as tc on tc.id = r.class_id '.
										'left join user as u on u.id = r.student_id';
				$registerHandle->fields = 'r.id,r.student_id,r.class_id';
				$registerHandle->where = 'r.id in ('.$ids.') and u.is_del = 0 and tc.is_del = 0';
				$infos = $registerHandle->find();
				if($infos)
				{
					$csDB = new IModel('class_student');
					$registerDB = new IModel('register');
					foreach ($infos as $info) {
						// 判定该学生是否已经在教学班中
						// 若不在教学班中则添加
						if(!$csDB->getObj('class_id = '.$info['class_id'].' and student_id ='.$info['student_id']))
						{
							$csDB->setData(array(
								'class_id' => $info['class_id'],
								'student_id' => $info['student_id']
							));
							$csDB->add();
						}
						$registerDB->del('id = '.$info['id']);
					}
				}
			}
		}
		$this->class_register();
	}

	function register_del()
	{
		$register_ids = IReq::get('check');
		$register_ids = is_array($register_ids) ? $register_ids : array($register_ids);
		$register_ids = IFilter::act($register_ids,'int');
		if($register_ids)
		{
			$ids = implode(',',$register_ids);
			if($ids)
			{
				$registerDB = new IModel('register');
				$registerDB->del('id in ('.$ids.')');
			}
		}
		$this->class_register();
	}

    function class_reclaim()
	{
		$class_ids = IReq::get('check');
		$class_ids = is_array($class_ids) ? $class_ids : array($class_ids);
		$class_ids = IFilter::act($class_ids,'int');
		if($class_ids)
		{
			$ids = implode(',',$class_ids);
			if($ids)
			{
                $classDB = new IModel('teaching_class');
                $classDB->setData(array('is_del'=>1));
                $where = 'id in ('.$ids.')';
                $classDB->update($where);
			}
		}
		$this->class_list();
    }
    
    function class_restore()
	{
		$class_ids = IReq::get('check');
		$class_ids = is_array($class_ids) ? $class_ids : array($class_ids);
		if($class_ids)
		{
			$class_ids = IFilter::act($class_ids,'int');
			$ids = implode(',',$class_ids);
			if($ids)
			{
                $classDB = new IModel('teaching_class');
                $classDB->setData(array('is_del'=>0));
                $where = 'id in ('.$ids.')';
                $classDB->update($where);
			}
		}
		$this->redirect('recycling');
    }
    
    function class_del()
	{
		$class_ids = IReq::get('check');
		$class_ids = is_array($class_ids) ? $class_ids : array($class_ids);
		$class_ids = IFilter::act($class_ids,'int');
		if($class_ids)
		{
			$ids = implode(',',$class_ids);

			if($ids)
			{
                $classDB = new IModel('teaching_class');
                $where = 'id in ('.$ids.')';
                $classDB->del($where);

				$logObj = new log('db');
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了用户","被删除的用户ID为：".$ids));
			}
		}
		$this->redirect('recycling');
	}

    function class_lock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $classDB = new IModel('teaching_class');
            $classDB->setData(array('is_lock' => 1));
            $classDB->update('id = '.$id);
        }
        $this->redirect('class_list');
    }

    function class_unlock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $classDB = new IModel('teaching_class');
            $classDB->setData(array('is_lock' => 0));
            $classDB->update('id = '.$id);
        }
        $this->redirect('class_list');
	}
	
	/**
     * 添加学生
     * @param int id 教学班ID
     * @param array check 学生ID
     */
    public function add_students()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
		$student_ids = IReq::get('check');
		$student_ids = is_array($student_ids) ? $student_ids : array($student_ids);
		$student_ids = IFilter::act($student_ids,'int');
		if(!$student_ids) JsonResult::fail('未选择学生');
		// 创建数据库对象
		$classDB = new IModel('teaching_class');
		$userDB = new IModel('user');
		$csDB = new IModel('class_student');
		// 检查状态
		if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
			JsonResult::fail('该教学班不存在');
		foreach ($student_ids as $student_id) {
			if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
			{
				$this->setError('学生('.$student_id.')不存在');
				continue;
			}
			if($csDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
			{
				$this->setError('学生('.$student_id.')已在教学班中');
				continue;
			}
			$csDB->setData(array(
				'class_id'   => $class_id,
				'student_id' => $student_id
			));
			$csDB->add();
		}
		if($errors = $this->getAllError())
			JsonResult::fail($errors);
		else
			JsonResult::success();
	}
	
	/**
     * 移除学生
     * @param int id 教学班ID
     * @param array check 学生ID
     */
    public function remove_students()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
		$student_ids = IReq::get('check');
		$student_ids = is_array($student_ids) ? $student_ids : array($student_ids);
		$student_ids = IFilter::act($student_ids,'int');
		if(!$student_ids) IError::show(422, '未选择学生');
		// 创建数据库对象
		$classDB = new IModel('teaching_class');
		$userDB = new IModel('user');
		$csDB = new IModel('class_student');
		// 检查状态
		if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
			IError::show(404, '教学班不存在');
		foreach ($student_ids as $student_id) {
			if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
				continue;
			if(!$csDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
				continue;
			$csDB->del('class_id = '.$class_id.' and student_id = '.$student_id);
		}
		$this->redirect('/classes/class_students/id/'.$class_id);
	}
	
	/**
     * 添加教师
     * @param int id 教学班ID
     * @param array check 教师ID
     */
    public function add_teachers()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
		$teacher_ids = IReq::get('check');
		$teacher_ids = is_array($teacher_ids) ? $teacher_ids : array($teacher_ids);
		$teacher_ids = IFilter::act($teacher_ids,'int');
		if(!$teacher_ids) JsonResult::fail('未选择教师');
		// 创建数据库对象
		$classDB = new IModel('teaching_class');
		$userDB = new IModel('user');
		$ctDB = new IModel('class_teacher');
		// 检查状态
		if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
			JsonResult::fail('该教学班不存在');
		foreach ($teacher_ids as $teacher_id) {
			if(!$teacher_id || !$userDB->getObj('id = '.$teacher_id.' and is_del = 0 and job = 1'))
			{
				$this->setError('教师('.$teacher_id.')不存在');
				continue;
			}
			if($ctDB->getObj('class_id = '.$class_id.' and teacher_id = '.$teacher_id))
			{
				$this->setError('教师('.$teacher_id.')已在教学班中');
				continue;
			}
			$ctDB->setData(array(
				'class_id'   => $class_id,
				'teacher_id' => $teacher_id
			));
			$ctDB->add();
		}
		if($errors = $this->getAllError())
			JsonResult::fail($errors);
		else
			JsonResult::success();
	}
	
	/**
     * 移除教师
     * @param int id 教学班ID
     * @param array check 教师ID
     */
    public function remove_teachers()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
		$teacher_ids = IReq::get('check');
		$teacher_ids = is_array($teacher_ids) ? $teacher_ids : array($teacher_ids);
		$teacher_ids = IFilter::act($teacher_ids,'int');
		if(!$teacher_ids) IError::show(422, '未选择教师');
		// 创建数据库对象
		$classDB = new IModel('teaching_class');
		$userDB = new IModel('user');
		$ctDB = new IModel('class_teacher');
		// 检查状态
		if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
			IError::show(404, '该教学班不存在');
		foreach ($teacher_ids as $teacher_id) {
			if(!$teacher_id || !$userDB->getObj('id = '.$teacher_id.' and is_del = 0 and job = 1'))
				continue;
			if(!$ctDB->getObj('class_id = '.$class_id.' and teacher_id = '.$teacher_id))
				continue;
			$ctDB->del('class_id = '.$class_id.' and teacher_id = '.$teacher_id);
		}
		$this->redirect('/classes/class_teachers/id/'.$class_id);
	}
}