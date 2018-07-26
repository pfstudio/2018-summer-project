<?php
class Users extends IController
{
    function user_reclaim()
	{
		$user_ids = IReq::get('check');
		$user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
		$user_ids = IFilter::act($user_ids,'int');
		if($user_ids)
		{
			$ids = implode(',',$user_ids);
			if($ids)
			{
                $userDB = new IModel('user');
                $userDB->setData(array('is_del'=>1));
                $where = 'id in ('.$ids.')';
                $userDB->update($where);
			}
		}
		$this->callback_list();
    }
    
    function user_restore()
	{
		$user_ids = IReq::get('check');
		$user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
		if($user_ids)
		{
			$user_ids = IFilter::act($user_ids,'int');
			$ids = implode(',',$user_ids);
			if($ids)
			{
                $userDB = new IModel('user');
                $userDB->setData(array('is_del'=>0));
                $where = 'id in ('.$ids.')';
                $userDB->update($where);
			}
		}
		$this->callback_recycling();
    }
    
    function user_del()
	{
		$user_ids = IReq::get('check');
		$user_ids = is_array($user_ids) ? $user_ids : array($user_ids);
		$user_ids = IFilter::act($user_ids,'int');
		if($user_ids)
		{
			$ids = implode(',',$user_ids);

			if($ids)
			{
                $userDB = new IModel('user');
                $where = 'id in ('.$ids.')';
                $userDB->del($where);

				$logObj = new log('db');
				$logObj->write('operation',array("管理员:".$this->admin['admin_name'],"删除了用户","被删除的用户ID为：".$ids));
			}
		}
		$this->callback_recycling();
	}

    function user_lock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $userDB = new IModel('user');
            $userDB->setData(array('is_lock' => 1));
            $userDB->update('id = '.$id);
        }
        $this->callback_list();
    }

    function user_unlock()
    {
        $id = IFilter::act(IReq::get('id'), 'int');
        if($id)
        {
            $userDB = new IModel('user');
            $userDB->setData(array('is_lock' => 0));
            $userDB->update('id = '.$id);
        }
        $this->callback_list();
	}
	
	private function callback_list()
	{
		$callback = IFilter::act(IReq::get('callback'));
		if($callback == 'student')
			$this->redirect('/students/student_list');
		else if($callback == 'teacher')
			$this->redirect('/teachers/teacher_list');
		else
			$this->redirect('/system/default');
	}

	private function callback_recycling()
	{
		$callback = IFilter::act(IReq::get('callback'));
		if($callback == 'student')
			$this->redirect('/students/recycling');
		else if($callback == 'teacher')
			$this->redirect('/teachers/recycling');
		else
			$this->redirect('/system/default');
	}
}