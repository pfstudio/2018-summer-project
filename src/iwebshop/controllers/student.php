<?php
/**
 * 学生
 */
class Student extends IController
{
    /**
     * 获取单条学生信息
     * @param int id 学生ID
     */
    public function Get()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取学生列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 学生姓名(可选)
     */
    public function List()
    {
        echo __FUNCTION__;
    }

    /**
     * 更新学生信息
     * @param int id 学生ID
     * @param string name 姓名
     * @param int sex 性别 0: 男 1: 女
     * @param string email 电子邮箱
     * @param string wechat 微信号
     * @param string parents_phone 家长手机号
     * @param string grade 学生年级
     * @param date birthday 学生生日
     * 
     * @return 更新后的学生信息
     */
    public function Update()
    {
        echo __FUNCTION__;
    }

    /**
     * 删除学生
     * @param int id 学生ID
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复已删除学生
     * @param int id 学生ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取学生上课的教学班
     * @param int id 学生ID
     * 
     * @return 教学班列表
     */
    public function GetClasses()
    {
        echo __FUNCTION__;
    }
}