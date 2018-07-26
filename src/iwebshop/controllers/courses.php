<?php
class Courses extends IController
{
    public $layout = 'admin';

    /**
	 * 添加课程
	 */
	function course_edit()
	{
		$id  = IFilter::act(IReq::get('id'),'int');
		$course = array();

		//编辑课程信息
		if($id)
		{
            $course = Api::run('getCourseInfo',array('id'=>$id));
			if(!$course)
			{
				$this->course_list();
				Util::showMessage("没有找到相关记录！");
				exit;
			}
		}
		$this->setRenderData(array('course' => $course));
		$this->redirect('course_edit');
	}

	//保存教学班信息
	function course_save()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
		$name = IFilter::act(IReq::get('name'));
		$price = IFilter::act(IReq::get('price'), 'float');
		$introduction = IReq::get('introduction');
		$is_lock = IFilter::act(IReq::get('is_lock'), 'int');
		//创建课程对象
        $courseDB = new IModel('course');
		// 检查课程状态
		if($is_lock != 0 && $is_lock!= 1)
			$this->setError('课程状态错误');
		//操作失败表单回填
		if($errorMsg = $this->getError())
		{
			$this->setRenderData(array('course' => $_POST));
			$this->redirect('course_edit',false);
			Util::showMessage($errorMsg);
		}
		$course = array(
			'name' => $name,
			'price' => $price,
			'introduction' => $introduction,
			'is_lock' => $is_lock
		);

		// 添加课程
		if(!$id)
		{
			$courseDB->setData($course);
			$courseDB->add();
		}
		// 编辑课程
		else
		{
			$courseDB->setData($course);
			$courseDB->update('id = '.$id);
		}
		$this->redirect('course_list');
	}

    function course_list()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("course");
        $query->where  = 'is_del = 0';
        $query->fields = 'id,name,price,introduction,is_lock';
        $query->order  = 'id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('course_list');
    }

    function recycling()
    {
        $page  = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $query = new IQuery("course");
        $query->where  = 'is_del = 1';
        $query->fields = 'id,name,price,introduction,is_lock';
        $query->order  = 'id desc';
        $query->page   = $page;
        $this->query   = $query;
        $this->redirect('recycling');
	}
	
	function introduction()
	{
		$id = IFilter::act(IReq::get('id'), 'int');
		if(!$id) IError::show(403);
		$courseDB = new IModel('course');
		$course = $courseDB->getObj('id = '.$id);
		if(!$course) IError::show(404, '无法找到该信息');
		echo $course['introduction'];
	}

    function course_reclaim()
	{
		$course_ids = IReq::get('check');
		$course_ids = is_array($course_ids) ? $course_ids : array($course_ids);
		$course_ids = IFilter::act($course_ids,'int');
		if($course_ids)
		{
			$ids = implode(',',$course_ids);
			if($ids)
			{
                $courseDB = new IModel('course');
                $courseDB->setData(array('is_del'=>1));
                $where = 'id in ('.$ids.')';
                $courseDB->update($where);
			}
		}
		$this->course_list();
    }
    
    function course_restore()
	{
		$course_ids = IReq::get('check');
		$course_ids = is_array($course_ids) ? $course_ids : array($course_ids);
		if($course_ids)
		{
			$course_ids = IFilter::act($course_ids,'int');
			$ids = implode(',',$course_ids);
			if($ids)
			{
                $courseDB = new IModel('course');
                $courseDB->setData(array('is_del'=>0));
                $where = 'id in ('.$ids.')';
                $courseDB->update($where);
			}
		}
		$this->redirect('recycling');
    }
    
    function course_del()
	{
		$course_ids = IReq::get('check');
		$course_ids = is_array($course_ids) ? $course_ids : array($course_ids);
		$course_ids = IFilter::act($course_ids,'int');
		if($course_ids)
		{
			$ids = implode(',',$course_ids);

			if($ids)
			{
                $courseDB = new IModel('course');
                $where = 'id in ('.$ids.')';
                $courseDB->del($where);

				$logObj = new log('db');
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了用户","被删除的用户ID为：".$ids));
			}
		}
		$this->redirect('course_list');
	}

    function course_lock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $courseDB = new IModel('course');
            $courseDB->setData(array('is_lock' => 1));
            $courseDB->update('id = '.$id);
        }
        $this->redirect('course_list');
    }

    function course_unlock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $courseDB = new IModel('course');
            $courseDB->setData(array('is_lock' => 0));
            $courseDB->update('id = '.$id);
        }
        $this->redirect('course_list');
    }
}