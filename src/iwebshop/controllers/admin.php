<?php
/**
 * 管理员
 */
class Admin extends IController
{
    /**
     * 获取单条管理员信息
     * @param int id 管理员ID
     */
    public function Get()
    {
        echo __FUNCTION__;
    }

    /**
     * 获取管理员列表
     * @param int page 页码 默认1
     * @param int pagesize 每页数量 默认20
     * @param string name 管理员姓名(可选)
     */
    public function List()
    {
        echo __FUNCTION__;
    }

    /**
     * 添加管理员
     * @param string admin_name 用户名
     * @param string password 密码
     * @param string email 邮箱
     * @param string phone 手机
     * 
     * @return 管理员ID
     */
    public function Create()
    {
        echo __FUNCTION__;
    }

    /**
     * 更新管理员信息
     * @param int id 管理员ID
     * @param string name 姓名
     * @param string email 电子邮箱
     * @param string phone 手机号
     * 
     * @return 更新后的管理员信息
     */
    public function Update()
    {
        echo __FUNCTION__;
    }

    /**
     * 删除管理员
     * @param int id 管理员ID
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复已删除管理员
     * @param int id 管理员ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }
}