<?php
/**
 * 课程
 * 
 * @author monitor4
 */
class Course extends IController
{
    /**
     * 获取单条课程信息
     * @param int id 课程ID
     * 
     * @return 课程信息
     */
    public function Get()
    {
        //获取课程ID
        $course_id = IFilter::act(IReq::get('id'),'int');
        //创建课程对象
        $courseDB = new IModel('course');
        //检验课程是否存在
        if(!$course_id || !$courseDB->getObj('id = '.$course_id))
            JsonResult::fail('该课程不存在');
        //返回课程信息
        JsonResult::success($courseDB->getObj('id = '.$course_id));
    }

    /**
     * 获取课程列表
     * @param int page 页码 默认为1
     * @param int pagesize 每页数量 默认20
     * @param string name (可选)课程名称，模糊匹配
     * 
     * @return 课程信息列表
     */
    public function List()
    {
        //获取列表页码，页数以及搜索名称
        $page = IReq::get('page') ? IFilter::act(IReq::get('page'),'int') : 1;
        $pagesize = IReq::get('pagesize') ? IFilter::act(IReq::get('pagesize'),'int') : 20;
        $name = IFilter::act(IReq::get('name'));
        //创建课程对象
        $courseDB = new IQuery('course');
        //模糊匹配
        if($name)
            $courseDB->where = "name like '".$name."%'";
        //返回列表
        $courseDB->page = $page;
        $result = $courseDB->find();
        JsonResult::success(array(
            'totalpage' => $courseDB->paging->totalpage,
            'index' => $courseDB->paging->index,
            'pagesize' => $courseDB->paging->pagesize,
            'result' => $result
        ));
    }

    /**
     * 添加课程
     * @param string name 课程名称
     * @param float price 课程价格(可选)
     * @param string introduction 课程简介(可选)
     * 
     * @return 所添加的课程信息
     */
    public function Create()
    {
        //获取添加课程的名称(必需)，以及信息
        $name = IFilter::act(IReq::get('name'));
        $price = IFilter::act(IReq::get('price'),'float');
        $introduction = IFilter::act(IReq::get('introduction'));
        $courseDB = new IModel('course');
        //名称合法性检验
        if(!$name)
            JsonResult::fail('未输入课程名');
        //创建课程并写入数据库
        $course = array(
            'name' => $name,
            'price' => $price,
            'introduction' => $introduction
        );
        $courseDB->setData($course);
        $course_id = $courseDB->add();
        JsonResult::success($courseDB->getObj('id = '.$course_id));
    }

    /**
     * 更新课程信息
     * @param int id 课程ID
     * @param string name 课程名称
     * @param float price 课程价格
     * @param string introduction 课程简介
     * 
     * @return 更新后的课程信息
     */
    public function Update()
    {
        //获取课程ID(必需),以及更新的信息(课程名，价格，简介)
        $course_id = IFilter::act(IReq::get('id'),'int');
        $name = IFilter::act(IReq::get('name'));
        $price = IFilter::act(IReq::get('price'),'float');
        $introduction = IFilter::act(IReq::get('introduction'));
        //创建课程对象
        $courseDB = new IModel('course');
        //检查课程是否存在
        if(!$course_id || !$courseDB->getObj('id = '.$course_id))
            JsonResult::fail('该课程不存在');
        //创建更新对象
        $course = array(
            'id' => $course_id
            // 'name' => $name,
            // 'price' => $price,
            // 'introduction' => $introduction
        );
        //检查信息是否为空，为空默认为不修改
        if($name)
            $course['name'] = $name;
        if($price)
            $course['price'] = $price;
        if($name)
            $course['introduction'] = $introduction;
        //数据库更新信息
        $courseDB->setData($course);
        $courseDB->update('id = '.$course_id);
        JsonResult::success($courseDB->getObj('id = '.$course_id));
    }

    /**
     * 删除课程
     * @param int id 课程ID
     */
    public function Delete()
    {
        //获取课程ID
        $course_id = IFilter::act(IReq::get('id'),'int');
        //创建课程对象
        $courseDB = new IModel('course');
        //检查课程是否存在，且未删除
        if(!$course_id || !$courseDB->getObj('id = '.$course_id.' and status = 0'))
            JsonResult::fail('该课程不存在');
        //删除状态标记变为1
        $courseDB->setData(array(
            'status' => 1
        ));
        $courseDB->update('id = '.$course_id);
    }

    /**
     * 恢复删除的课程
     * @param int id 课程ID
     * 
     * @return 恢复后的课程
     */
    public function Restore()
    {
        //获取课程ID
        $course_id = IFilter::act(IReq::get('id'),'int');
        //创建课程对象
        $courseDB = new IModel('course');
        //检查课程是否存在
        if(!$course_id || !$courseDB->getObj('id = '.$course_id))
            JsonResult::fail('该课程不存在');
        //检查课程是否未被删除
        if($courseDB->getObj('id = '.$course_id.' and status = 0'))
            JsonResult::fail('该课程未被删除');
        //课程删除状态修改为0
        $courseDB->setData(array(
            'status' => 0
        ));
        $courseDB->update('id = '.$course_id);
        JsonResult::success($courseDB->getObj('id = '.$course_id));
    }
}