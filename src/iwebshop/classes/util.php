<?php
/**
 * @copyright Copyright(c) 2011 aircheng.com
 * @file util.php
 * @brief 公共函数类
 * @author kane
 * @date 2011-01-13
 * @version 0.6
 * @note
 */

 /**
 * @class Util
 * @brief 公共函数类
 */
class Util
{
	/**
	 * @brief 显示错误信息（dialog框）
	 * @param string $message	错误提示字符串
	 */
	public static function showMessage($message)
	{
		echo '<script type="text/javascript">typeof(tips) == "function" ? tips("'.$message.'") : alert("'.$message.'");</script>';
		exit;
	}

	//字符串拼接
	public static function joinStr($id)
	{
		if(is_array($id))
		{
			$where = "id in (".join(',',$id).")";
		}
		else
		{
			$where = 'id = '.$id;
		}
		return $where;
	}

	// 返回可选的年级
	public static function getGrades()
	{
		return array(
			'小学一年级','小学二年级','小学三年级',
			'小学四年级','小学五年级','小学六年级',
			'初一','初二','初三',
			'高一','高二','高三',
			'大一','大二','大三','大四','其他'
		);
	}
}