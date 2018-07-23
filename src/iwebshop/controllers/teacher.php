<?php
/**
 * 教师
 */
class Teacher extends IController
{
    /**
     * 获取单条教师信息
     * @param int id 教师ID
     */
    public function Get()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取教师列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 教师姓名(可选)
     */
    public function List()
    {
        echo __FUNCTION__;
    }

    /**
     * 更新教师信息
     * @param int id 教师ID
     * @param string name 姓名
     * @param int sex 性别 0: 男 1: 女
     * @param string email 电子邮箱
     * @param string wechat 微信号
     * @param string photo 教师照片URL
     * @param string introduction 教师介绍
     * 
     * @return 更新后的教师信息
     */
    public function Update()
    {
        return __FUNCTION__;
    }

    /**
     * 删除教师
     * @param int id 教师ID
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复已删除教师
     * @param int id 教师ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取教师所教授的教学班
     * @param int id 教师ID
     * 
     * @return 教学班列表
     */
    public function GetClasses()
    {
        echo __FUNCTION__;
    }
}