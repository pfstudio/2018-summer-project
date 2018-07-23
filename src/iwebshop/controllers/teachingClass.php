<?php
/**
 * 教学班
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
        echo __FUNCTION__;
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
        echo __FUNCTION__;
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
        echo __FUNCTION__;
    }

    /**
     * 更新教学班信息
     * @param int id 教学班ID
     * @param string name 教学班名称
     * @param string name 教学班名称(可选)
     * @param float price 教学班价格(可选)
     * @param string introduction 教学班介绍(可选)
     * @param int total_num 教学班容量上限(可选)
     * @param string comment 教学班注释(可选)
     * @param int status 教学班状态(0: 正常,1: 不可报名;默认 0)
     */
    public function Update()
    {
        echo __FUNCTION__;
    }

    /**
     * 删除教学班
     * @param int id 教学班ID
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复已删除的教学班
     * @param int id 教学班ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }

    /**
     * 报名
     * @param int id 教学班ID
     * @param int user_id 用户ID
     */
    public function Register()
    {
        echo __FUNCTION__;
    }

    /**
     * 取消报名
     * @param int id 教学班ID
     * @param int user_id 用户ID
     */
    public function UnRegister()
    {
        echo __FUNCTION__;
    }

    /**
     * 添加学生
     * @param int id 教学班ID
     * @param int student_id 学生ID
     */
    public function AddStudent()
    {
        echo __FUNCTION__;
    }

    /**
     * 移除学生
     * @param int id 教学班ID
     * @param int student_id 学生ID
     */
    public function RemoveStudent()
    {
        echo __FUNCTION__;
    }

    /**
     * 添加教师
     * @param int id 教学班ID
     * @param int teacher_id 教师ID
     */
    public function AddTeacher()
    {
        echo __FUNCTION__;
    }

    /**
     * 移除教师
     * @param int id 教学班ID
     * @param int teacher_id 教师ID
     */
    public function RemoveTeacher()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取教学班的教师
     * @param int id 教学班ID
     * 
     * @return 教师列表
     */
    public function GetTeachers()
    {
        echo __FUNCTION__;
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
        echo __FUNCTION__;
    }
}