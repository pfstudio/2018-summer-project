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
		$price = IFilter::act(IReq::get('price'), 'float');
		$introduction = IFilter::act(IReq::get('introduction'));
		$is_lock = IFilter::act(IReq::get('is_lock'), 'int');
		//创建教学班对象
        $classDB = new IModel('class');
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
			'price' => $price,
			'introduction' => $introduction,
			'is_lock' => $is_lock
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
        $query->fields = 'tc.id,tc.name,tc.price,tc.total_num,tc.selected_num,tc.is_lock,tc.introduction,tc.comment,'.
        'c.id as course_id,c.name as course_name';
        $query->order  = 'tc.id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('class_list');
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
		$this->redirect('class_list');
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
}