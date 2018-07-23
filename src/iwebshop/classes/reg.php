<?php
/**
 * 正则表达式
 * 
 * @author yiluomyt
 */
class Reg
{
    /**
     * 匹配手机号
     */
    public static function phone($phone)
    {
        return preg_match('/^\d{11}$/', $phone);
    }

    /**
     * 匹配邮箱
     */
    public static function email($email)
    {
        return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email);
    }

    /**
     * 匹配日期
     */
    public static function date($date)
    {
        return preg_match('/^\d{4}-\d{1,2}-\d{1,2}/', $date);
    }
}