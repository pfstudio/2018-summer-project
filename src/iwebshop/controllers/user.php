<?php
/**
 * 用户相关API
 */
class User extends IController
{
    /**
     * 学生自主注册
     * @param string phone 手机号
     * @param string pin   短信验证码
     * 
     * @return JWT Token
     */
    public function CreateWithPhone()
    {
        echo __FUNCTION__;
    }
    
    /**
     * 更换手机号
     * @param string phone 手机号
     * @param string pin   短信验证码
     */
    public function ChangePhone()
    {
        echo __FUNCTION__;
    }
    

    /**
     * 后台添加学生
     * @param string phone 手机号
     * 
     * @return int id 学生ID
     */
    public function CreateStudent()
    {
        echo __FUNCTION__;
    }

    /**
     * 后台添加教师
     * @param string phone 手机号
     * 
     * @return int id 教师ID
     */
    public function CreateTeacher()
    {
        echo __FUNCTION__;
    }

    /**
     * 删除用户（学生/教师）
     * @param int id 用户ID
     * @param bool true_del 软/硬删除
     */
    public function Delete()
    {
        echo __FUNCTION__;
    }

    /**
     * 恢复软删除用户
     * @param int id 用户ID
     */
    public function Restore()
    {
        echo __FUNCTION__;
    }
}