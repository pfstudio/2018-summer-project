<?php
/**
 * 教学班
 * 
 * @author yiluomyt
 */
class TeachingClass extends IController
{
    /**
     * 获取单条教学班信息
     * @param int id 教学班ID
     * 
     * @return 教学班信息
     */
    public function Get()
    {
        // 获取教学班ID
        $class_id = IFilter::act(IReq::get('id'), 'int');
        // 创建教学班对象
        $classDB = new IModel('teaching_class');
        // 检验教学班是否存在
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        // 返回教学班信息
        JsonResult::success($classDB->getObj('id = '.$class_id));
    }

    /**
     * 获取教学班列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param int course_id 课程ID(可选)
     * @param string name 教学班名称(可选)
     * 
     * @return 教学班列表
     */
    public function List()
    {
        //获取列表页码，页数以及搜索名称
        $page = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        $course_id = IFilter::act(IReq::get('course_id'), 'int');
        $name = IFilter::act(IReq::get('name'));
        // 创建课程对象
        $classDB = new IQuery('teaching_class');
        // 配置查询条件
        $wheres = array("is_del = 0");
        if($name)
            array_push($wheres, "name like '".$name."%'");
        if($course_id)
            array_push($wheres, "course_id = ".$course_id);
        $classDB->where = join(' and ', $wheres);
        // 返回列表
        $classDB->page = $page;
        $classDB->pagesize = $pagesize;
        $result = $classDB->find();
        JsonResult::success(array(
            'totalpage' => $classDB->paging->totalpage,
            'index' => $classDB->paging->index,
            'pagesize' => $classDB->paging->pagesize,
            'result' => $result
        ));
    }

    /**
     * 新建教学班
     * @param int course_id 课程ID
     * @param string name 教学班名称(可选)
     * @param float price 教学班价格(可选)
     * @param string introduction 教学班介绍(可选)
     * @param int total_num 教学班容量上限(可选)
     * @param string comment 教学班注释(可选)
     * 
     * @return 教学班ID
     */
    public function Create()
    {
        $course_id = IFilter::act(IReq::get('course_id'), 'int');
        // 创建数据库对象
        $courseDB = new IModel('course');
        $classDB = new IModel('teaching_class');
        if (!$course_id || !$courseDB->getObj('id = '.$course_id))
            JsonResult::fail('该课程不存在');
        // 课程对象
        $course = $courseDB->getObj('id = '.$course_id);
        $name = IReq::get('name') ? IFilter::act(IReq::get('name')) : $course['name'];
        $price = IReq::get('price') ? IFilter::act(IReq::get('price'), 'float') : $course['price'];
        $introduction = IReq::get('introduction') ? IFilter::act(IReq::get('introduction')) : $course['introduction'];
        $total_num = IFilter::act(IReq::get('total_num'), 'int');
        $comment = IFilter::act(IReq::get('comment'));
        // 教学班对象
        $class = array(
            'course_id' => $course_id,
            'name' => $name,
            'price' => $price,
            'introduction' => $introduction,
            'total_num' => $total_num,
            'comment' => $comment
        );
        $classDB->setData($class);
        $class_id = $classDB->add();
        JsonResult::success($class_id);
    }

    /**
     * 更新教学班信息
     * @param int id 教学班ID
     * @param string name 教学班名称(可选)
     * @param float price 教学班价格(可选)
     * @param string introduction 教学班介绍(可选)
     * @param int total_num 教学班容量上限(可选)
     * @param string comment 教学班注释(可选)
     * @param int is_lock 锁定教学班(可选)
     */
    public function Update()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $name = IFilter::act(IReq::get('name'));
        $price = IFilter::act(IReq::get('price'), 'float');
        $introduction = IFilter::act(IReq::get('introduction'));
        $total_num = IFilter::act(IReq::get('total_num'), 'int');
        $comment = IFilter::act(IReq::get('comment'));
        $is_lock = IFilter::act(IReq::get('is_lock'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        // 检查教学班是否存在
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if($is_lock != 0 && $is_lock != 1)
            JsonResult::fail('is_lock 仅为0/1');
        // 创建教学班对象
        $class = array();
        if($name) $class['name'] = $name;
        if($price) $class['price'] = $price;
        if($introduction) $class['introduction'] = $introduction;
        if($total_num) $class['total_num'] = $total_num;
        if($comment) $class['comment'] = $comment;
        if($is_lock) $class['is_lock'] = $is_lock;
        // 更新教学班状态
        $classDB->setData($class);
        $classDB->update('id = '.$class_id);
    }

    /**
     * 删除教学班
     * 
     * @param int id 教学班ID
     */
    public function Delete()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        $classDB->setData(array('is_del' => 1));
        $classDB->update('id = '.$class_id);
    }

    /**
     * 恢复教学班
     * 
     * @param int id 教学班ID
     */
    public function Restore()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        if(!$class_id || !$classDB->getObj('id = '.$class_id))
            JsonResult::fail('该教学班不存在');
        if(!$classDB->getObj('id = '.$class_id.' and is_del = 1'))
            JsonResult::fail('该教学班未被删除');
        $classDB->setData(array('is_del' => 0));
        $classDB->update('id = '.$class_id);
    }

    /**
     * 报名
     * @param int id 教学班ID
     * @param int student_id 学生ID
     */
    public function Register()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $student_id = IFilter::act(IReq::get('student_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $registerDB = new IModel('register');
        // 检查教学班是否存在
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
            JsonResult::fail('该学生不存在');
        if($registerDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
            JsonResult::fail('已报名');
        $registerDB->setData(array(
            'class_id' => $class_id,
            'student_id' => $student_id
        ));
        $registerDB->add();
    }

    /**
     * 取消报名
     * @param int id 教学班ID
     * @param int user_id 用户ID
     */
    public function UnRegister()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $student_id = IFilter::act(IReq::get('student_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $registerDB = new IModel('register');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
            JsonResult::fail('该学生不存在');
        if(!$registerDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
            JsonResult::fail('未报名');
        $registerDB->del('class_id = '.$class_id.' and student_id = '.$student_id);
    }

    /**
     * 添加学生
     * @param int id 教学班ID
     * @param int student_id 学生ID
     */
    public function AddStudent()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $student_id = IFilter::act(IReq::get('student_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $csDB = new IModel('class_student');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
            JsonResult::fail('该学生不存在');
        if($csDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
            JsonResult::fail('该学生已在教学班中');
        $csDB->setData(array(
            'class_id' => $class_id,
            'student_id' => $student_id
        ));
        $csDB->add();
    }

    /**
     * 移除学生
     * @param int id 教学班ID
     * @param int student_id 学生ID
     */
    public function RemoveStudent()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $student_id = IFilter::act(IReq::get('student_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $csDB = new IModel('class_student');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$student_id || !$userDB->getObj('id = '.$student_id.' and is_del = 0 and job = 0'))
            JsonResult::fail('该学生不存在');
        if(!$csDB->getObj('class_id = '.$class_id.' and student_id = '.$student_id))
            JsonResult::fail('该学生未在教学班中');
        $csDB->setData(array(
            'class_id' => $class_id,
            'student_id' => $student_id
        ));
        $csDB->del('class_id = '.$class_id.' and student_id = '.$student_id);
    }

    /**
     * 添加教师
     * @param int id 教学班ID
     * @param int teacher_id 教师ID
     */
    public function AddTeacher()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $teacher_id = IFilter::act(IReq::get('teacher_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $ctDB = new IModel('class_teacher');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$teacher_id || !$userDB->getObj('id = '.$teacher_id.' and is_del = 0'))
            JsonResult::fail('该教师不存在');
        if($ctDB->getObj('class_id = '.$class_id.' and teacher_id = '.$teacher_id))
            JsonResult::fail('该教师已在教学班中');
        $ctDB->setData(array(
            'class_id' => $class_id,
            'teacher_id' => $teacher_id
        ));
        $ctDB->add();
    }

    /**
     * 移除教师
     * @param int id 教学班ID
     * @param int teacher_id 教师ID
     */
    public function RemoveTeacher()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $teacher_id = IFilter::act(IReq::get('teacher_id'), 'int');
        // 创建数据库对象
        $classDB = new IModel('teaching_class');
        $userDB = new IModel('user');
        $ctDB = new IModel('class_teacher');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        if(!$teacher_id || !$userDB->getObj('id = '.$teacher_id.' and is_del = 0'))
            JsonResult::fail('该教师不存在');
        if(!$ctDB->getObj('class_id = '.$class_id.' and teacher_id = '.$teacher_id))
            JsonResult::fail('该教师未在教学班中');
        $ctDB->del('class_id = '.$class_id.' and teacher_id = '.$teacher_id);
    }

    /**
     * 获取教学班的教师
     * @param int id 教学班ID
     * 
     * @return 教师列表
     */
    public function GetTeachers()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $classDB = new IModel('teaching_class');
        $teachersHandle = new IQuery('teacher as t');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        $teachersHandle->fields = 't.user_id,t.name,t.photo';
        $teachersHandle->join = 'left join class_teacher as ct on ct.teacher_id = t.user_id '.
                                'left join user as u on u.id = t.user_id';
        $teachersHandle->where = 'ct.class_id = '.$class_id.' and u.is_del = 0';
        $teachers = $teachersHandle->find();
        JsonResult::success($teachers);
    }

    /**
     * 获取教学班的学生
     * @param int id 教学班ID
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * 
     * @return 学生列表
     */
    public function GetStudents()
    {
        $class_id = IFilter::act(IReq::get('id'), 'int');
        $page = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        // 创建数据库对象
        $studentsHandle = new IQuery('student as s');
        $classDB = new IModel('teaching_class');
        // 检查状态
        if(!$class_id || !$classDB->getObj('id = '.$class_id.' and is_del = 0'))
            JsonResult::fail('该教学班不存在');
        $studentsHandle->join = 'left join class_student as cs on cs.student_id = s.user_id '.
                                'left join user as u on u.id = s.user_id';
        $studentsHandle->fields = 'u.id,s.name';
        $studentsHandle->where = 'cs.class_id = '.$class_id.' and u.is_del = 0';
        $studentsHandle->page = $page;
        $studentsHandle->pagesize = $pagesize;
        $students = $studentsHandle->find();
        JsonResult::success($students);
    }
}