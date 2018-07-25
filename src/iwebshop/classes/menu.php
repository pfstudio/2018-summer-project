<?php
/**
 * @copyright Copyright(c) 2016 aircheng.com
 * @file menu.php
 * @brief 后台系统菜单管理
 * @author nswe
 * @date 2016/3/4 23:59:33
 * @version 4.4
 */
class Menu
{
    //菜单的配制数据
	public static $menu = array(
		'课程'=>array(
			'课程管理'=>array(
				'/courses/course_list' => '课程列表',
				'/courses/course_edit' => '课程添加'
			),
			'教学班管理' => array(
				'/classes/class_list'  => '教学班列表',
				'/classes/class_edit'  => '教学班添加'
			)
		),

		'用户'=>array(
			'学生管理'=>array(
	    		'/students/student_list' 	=> '学生列表',
	     		'/students/student_edit' 	=> '添加学生'
			),
			'教师管理' => array(
				'/teachers/teacher_list' => '教师列表',
				'/teachers/teacher_edit' => '添加教师'
			),
		),

        '系统'=>array(
    		'后台首页'=>array(
    			'/system/default' => '首页',
    		),
        	'网站管理'=>array(
        		'/system/conf_base' => '网站设置',
        		'/system/conf_guide' => '网站导航',
        		'/system/conf_banner' => '首页幻灯图',
        		'/system/conf_ui/type/site'   => '网站前台主题',
        		'/system/conf_ui/type/system'   => '后台管理主题',
        		'/system/conf_ui/type/seller'   => '商家管理主题',
            ),
        	'权限管理'=>array(
        		'/system/admin_list' => '管理员',
        		'/system/role_list'  => '角色',
        		'/system/right_list' => '权限资源'
        	),
		)
	);

	//非菜单连接映射关系,array(视图名称 => menu数组中已存在的菜单连接)
	public static $innerPathUrl = array(
		"/system/navigation" => "/system/default",
		"/system/navigation_edit" => "/system/default",
	);

    /**
     * @brief 根据权限初始化菜单
     * @return array 菜单数组
     */
    public static function init()
    {
		//菜单创建事件触发
        plugin::trigger("onSystemMenuCreate");
        
		return self::$menu;
    }

    /**
     * @brief 根据当前URL动态生成菜单分组
     * @param array  $menu 菜单数据
     * @param string $info 连接信息
     * @return array 菜单数组
     */
    public static function get($menu,$info)
    {
    	$result = self::menuInfo($menu,$info);
    	if($result)
    	{
    		return $result;
    	}

		//历史URL信息
		$lastInfo = IUrl::getRefRoute();
		if($lastInfo && strpos($lastInfo,$info) === false && $result = self::menuInfo($menu,$lastInfo))
		{
			ICookie::set('lastInfo',$lastInfo);
			return $result;
		}

		//从COOKIE读取URL信息
		$lastInfo = ICookie::get('lastInfo');
		if($lastInfo)
		{
			return self::menuInfo($menu,$lastInfo);
		}
		return array('插件' => self::$menu['插件']);
    }

	/**
	 * @brief 判断url路径获取定义的菜单项
	 * @param array  $menu 当前管理员权限合法的菜单
	 * @param string $info 访问的URL
	 * @return array(地址=>名称) or null
	 */
    public static function menuInfo($menu,$info)
    {
    	//已有菜单查找
		foreach($menu as $key1 => $val1)
		{
			foreach($val1 as $key2 => $val2)
			{
				foreach($val2 as $key3 => $val3)
				{
					if(strpos($key3,$info) !== false || strpos($info,$key3) !== false)
					{
						return array($key1 => $menu[$key1]);
					}
				}
			}
		}

		//配置菜单映射
		if(self::$innerPathUrl)
		{
			foreach(self::$innerPathUrl as $key => $val)
			{
				if(strpos($key,$info) !== false)
				{
					return self::menuInfo($menu,$val);
				}
			}
		}
		return null;
    }

    /**
     * @brief 获取顶级分类关系数据
     * @param array $menu 菜单数据
     * @return array 顶级菜单数组
     */
    public static function getTopMenu($menu)
    {
    	$result = array();
		foreach($menu as $key1 => $val1)
		{
			foreach($val1 as $key2 => $val2)
			{
				foreach($val2 as $key3 => $val3)
				{
					$result[$key1] = $key3;
					break 2;
				}
			}
		}
		return $result;
    }
}