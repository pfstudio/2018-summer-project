<?php
/**
 * 课程
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
        echo __FUNCTION__;
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
        echo __FUNCTION__;
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
        echo __FUNCTION__;
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

    }

    /**
     * 删除课程
     * @param int id 课程ID
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复删除的课程
     * @param int id 课程ID
     * 
     * @return 恢复后的课程ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }
}