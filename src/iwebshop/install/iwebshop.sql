SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 表的结构 `{pre}user`
--

DROP TABLE IF EXISTS `{pre}user`;
CREATE TABLE `{pre}user` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(20) NOT NULL COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `head_ico` varchar(255) default NULL COMMENT '头像',
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}student`
--

DROP TABLE IF EXISTS `{pre}student`;
CREATE TABLE `{pre}student`  (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `phone` varchar(50) DEFAULT NULL COMMENT '个人电话',
  `parents_phone` varchar(50) DEFAULT NULL COMMENT '父母联系方式',
  `area` varchar(255) DEFAULT NULL COMMENT '所在地区',
  `address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `qq` varchar(20) DEFAULT NULL COMMENT 'qq号',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信号',
  `sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别 0: 男 1: 女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户状态 0: 正常 1: 已删除 2: 锁定',
  `last_login` datetime(0) DEFAULT NULL COMMENT '上次登陆时间',
  `email` varchar(255) DEFAULT NULL COMMENT 'Email',
  `grade` varchar(50) DEFAULT NULL COMMENT '年级',
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '学生信息表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}teacher`
--

DROP TABLE IF EXISTS `{pre}teacher`;
CREATE TABLE `{pre}teacher`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教师ID',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `username` varchar(50) NOT NULL COMMENT '登录名',
  `password` char(32) NOT NULL COMMENT '密码',
  `sex` tinyint(1) DEFAULT 0 COMMENT '性别 0: 男 1: 女',
  `create_time` datetime(0) DEFAULT NULL COMMENT '加入时间',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未删除 1: 已删除',
  `is_lock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未锁定 1: 已锁定',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `phone` varchar(50) NOT NULL COMMENT '手机号',
  `qq` varchar(20) DEFAULT NULL COMMENT 'QQ',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信',
  `photo` varchar(255) DEFAULT '' COMMENT '照片',
  `introduction` text COMMENT '介绍',
  PRIMARY KEY (`id`, `_hash`),
  UNIQUE INDEX `username`(`username`),
  INDEX `username_password`(`username`, `password`),
  INDEX `name`(`name`),
  INDEX `is_del`(`is_del`),
  INDEX `is_lock`(`is_lock`),
  INDEX `email`(`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教师表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}admin`
--

DROP TABLE IF EXISTS `{pre}admin`;
CREATE TABLE `{pre}admin` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '管理员ID',
  `admin_name` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `role_id` int(11) unsigned NOT NULL COMMENT '角色ID',
  `create_time` datetime default NULL COMMENT '创建时间',
  `email` varchar(255) default NULL COMMENT 'Email',
  `last_ip` varchar(30) default NULL COMMENT '最后登录IP',
  `last_time` datetime default NULL COMMENT '最后登录时间',
  `is_del` tinyint(1) NOT NULL default '0' COMMENT '删除状态 1删除,0正常',
  PRIMARY KEY  (`id`),
  index (`admin_name`),
  index (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员用户表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}course`
--

DROP TABLE IF EXISTS `{pre}course`;
CREATE TABLE `{pre}course`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '课程ID',
  `name` varchar(50) NOT NULL COMMENT '课程名称',
  `price` decimal(15, 2) NULL DEFAULT NULL COMMENT '课程的基础价格',
  `introduction` text COMMENT '课程介绍',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '课程状态 0 正常 1 已删除 2 不可开班',
  PRIMARY KEY (`id`),
  INDEX `status`(`status`),
  INDEX `name`(`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '课程信息表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}class`
--

DROP TABLE IF EXISTS `{pre}class`;
CREATE TABLE `{pre}class`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教学班ID',
  `course_id` int(11) UNSIGNED NOT NULL COMMENT '课程ID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `price` decimal(15, 2) NULL DEFAULT NULL COMMENT '实际价格',
  `introduction` text COMMENT '具体介绍 - 对外公开的信息',
  `total_num` int(11) NULL DEFAULT NULL COMMENT '容量上限',
  `selected_num` int(11) NULL DEFAULT NULL COMMENT '已选人数',
  `comment` text COMMENT '对教学班的说明 - 仅对内可见',
  `status` tinyint(1) NULL DEFAULT 0 COMMENT '教学班状态 0: 正常 1: 不可报名',
  PRIMARY KEY (`id`),
  INDEX `name`(`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教学班表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}class_student`
--

DROP TABLE IF EXISTS `{pre}class_student`;
CREATE TABLE `{pre}class_student`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` int(11) UNSIGNED NOT NULL COMMENT '课程id',
  `student_id` int(11) UNSIGNED NOT NULL COMMENT '学生id',
  PRIMARY KEY (`id`),
  INDEX `class_id`(`class_id`),
  INDEX `student_id`(`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教学班与学生关系表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}class_teacher`
--

DROP TABLE IF EXISTS `{pre}class_teacher`;
CREATE TABLE `{pre}class_teacher`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id',
  `class_id` int(11) UNSIGNED NOT NULL COMMENT '课程id',
  `teacher_id` int(11) UNSIGNED NOT NULL COMMENT '教师id',
  PRIMARY KEY (`id`),
  INDEX `class_id`(`class_id`),
  INDEX `teacher_id`(`teacher_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教学班和老师的关系表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}log_sql`
--

DROP TABLE IF EXISTS `{pre}log_sql`;
CREATE TABLE `{pre}log_sql` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `content` varchar(255) NOT NULL COMMENT '执行的SQL语句',
  `runtime` decimal(15,2) unsigned NOT NULL COMMENT '语句执行时间(秒)',
  `datetime` datetime NOT NULL COMMENT '发生的时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='SQL日志记录';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}plugin`
--

DROP TABLE IF EXISTS `{pre}plugin`;
CREATE TABLE `{pre}plugin` (
  `id` int(11) unsigned NOT NULL auto_increment COMMENT '插件ID',
  `name` varchar(255) NOT NULL COMMENT '插件名称',
  `class_name` varchar(255) NOT NULL COMMENT '插件类库名称',
  `config_param` text COMMENT '配置参数',
  `description` text COMMENT '描述说明',
  `is_open` tinyint(1) NOT NULL default '1' COMMENT '安装状态 0禁用 1启用',
  `sort` smallint(5) NOT NULL default '99' COMMENT '排序',
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`class_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='插件表';

-- --------------------------------------------------------

--
-- 建立外键关系
--

ALTER TABLE `{pre}student` ADD foreign key(user_id) references `{pre}user`(id) ON UPDATE CASCADE ON DELETE CASCADE;