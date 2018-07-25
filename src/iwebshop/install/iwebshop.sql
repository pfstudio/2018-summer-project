SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- 表的结构 `{pre}user`
--

DROP TABLE IF EXISTS `{pre}user`;
CREATE TABLE `{pre}user` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `phone` varchar(50) NOT NULL COMMENT '个人电话-用于登陆和找回密码',
  `job` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户身份 0: 学生 1: 教师',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未删除 1: 已删除',
  `is_lock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未锁定 1: 已锁定',
  `create_time` datetime(0) DEFAULT NULL COMMENT '加入时间',
  `last_time` datetime(0) DEFAULT NULL COMMENT '上次登陆时间',
  PRIMARY KEY  (`id`),
  UNIQUE KEY (`phone`),
  INDEX `is_del` (`is_del`),
  INDEX `is_lock` (`is_lock`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='用户表-对应账号操作';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}student`
--

DROP TABLE IF EXISTS `{pre}student`;
CREATE TABLE `{pre}student`  (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `name` varchar(50) DEFAULT NULL COMMENT '真实姓名',
  `parents_phone` varchar(50) DEFAULT NULL COMMENT '父母联系方式',
  `address` varchar(255) DEFAULT NULL COMMENT '联系地址',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信号',
  `sex` tinyint(1) NOT NULL DEFAULT 0 COMMENT '性别 0: 男 1: 女',
  `birthday` date DEFAULT NULL COMMENT '生日',
  `grade` varchar(50) DEFAULT NULL COMMENT '年级',
  PRIMARY KEY (`user_id`),
  INDEX `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '学生信息表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}teacher`
--

DROP TABLE IF EXISTS `{pre}teacher`;
CREATE TABLE `{pre}teacher`  (
  `user_id` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `sex` tinyint(1) DEFAULT 0 COMMENT '性别 0: 男 1: 女',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '电子邮箱',
  `wechat` varchar(50) DEFAULT NULL COMMENT '微信号',
  `photo` varchar(255) DEFAULT '' COMMENT '照片',
  `introduction` text COMMENT '介绍',
  PRIMARY KEY (`user_id`),
  INDEX `name` (`name`),
  INDEX `wechat` (`wechat`),
  INDEX `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教师信息表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}admin`
--

DROP TABLE IF EXISTS `{pre}admin`;
CREATE TABLE `{pre}admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '管理员ID',
  `name` varchar(50) NOT NULL COMMENT '姓名',
  `admin_name` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(32) NOT NULL COMMENT '密码',
  `phone` varchar(50) default NULL COMMENT '电话',
  `email` varchar(255) default NULL COMMENT 'Email',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未删除 1: 已删除',
  `is_lock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: 未锁定 1: 已锁定',
  `create_time` datetime(0) DEFAULT NULL COMMENT '加入时间',
  `last_time` datetime(0) DEFAULT NULL COMMENT '上次登陆时间',
  `last_ip` varchar(30) default NULL COMMENT '最后登录IP',
  PRIMARY KEY  (`id`),
  UNIQUE INDEX `admin_name` (`admin_name`),
  INDEX `phone` (`phone`),
  INDEX `email` (`email`),
  INDEX `is_del` (`is_del`),
  INDEX `is_lock` (`is_lock`)
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
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '课程状态 0 正常 1 已删除',
  `is_lock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 正常 1 不可开班',
  PRIMARY KEY (`id`),
  INDEX `name`(`name`),
  INDEX `is_del` (`is_del`),
  INDEX `is_lock` (`is_lock`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '课程信息表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}teaching_class`
--

DROP TABLE IF EXISTS `{pre}teaching_class`;
CREATE TABLE `{pre}teaching_class`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教学班ID',
  `course_id` int(11) UNSIGNED NOT NULL COMMENT '课程ID',
  `name` varchar(50) NOT NULL COMMENT '名称',
  `price` decimal(15, 2) NULL DEFAULT NULL COMMENT '实际价格',
  `introduction` text COMMENT '具体介绍 - 对外公开的信息',
  `total_num` int(11) NULL DEFAULT NULL COMMENT '容量上限',
  `selected_num` int(11) NULL DEFAULT NULL COMMENT '已选人数',
  `comment` text COMMENT '对教学班的说明 - 仅对内可见',
  `is_del` tinyint(1) NOT NULL DEFAULT 0 COMMENT '删除状态 0: 正常 1: 已被删除',
  `is_lock` tinyint(1) NOT NULL DEFAULT 0 COMMENT '教学班状态 0: 正常 1: 不可报名',
  PRIMARY KEY (`id`),
  INDEX `course_id` (`course_id`),
  INDEX `name`(`name`),
  INDEX `is_del` (`is_del`),
  INDEX `is_lock` (`is_lock`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '教学班表';

-- --------------------------------------------------------

--
-- 表的结构 `{pre}register`
--

DROP TABLE IF EXISTS `{pre}register`;
CREATE TABLE `{pre}register`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `class_id` int(11) UNSIGNED NOT NULL COMMENT '课程id',
  `student_id` int(11) UNSIGNED NOT NULL COMMENT '学生id',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `class_id_and_student_id` (`class_id`, `student_id`),
  INDEX `class_id` (`class_id`),
  INDEX `student_id` (`student_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT = '学生报名信息';

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
  INDEX `class_id` (`class_id`),
  INDEX `student_id` (`student_id`)
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
  INDEX `class_id` (`class_id`),
  INDEX `teacher_id` (`teacher_id`)
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
-- 表的结构 `{pre}log_operation`
--

DROP TABLE IF EXISTS `{pre}log_operation`;
CREATE TABLE `{pre}log_operation` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `author` varchar(80) NOT NULL COMMENT '操作人员',
  `action` varchar(200) NOT NULL COMMENT '动作',
  `content` text COMMENT '内容',
  `datetime` datetime NOT NULL COMMENT '时间',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='日志操作记录';

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
ALTER TABLE `{pre}teacher` ADD foreign key(user_id) references `{pre}user`(id) ON UPDATE CASCADE ON DELETE CASCADE;

-- --------------------------------------------------------

--
-- 插入测试数据
--

-- ----------------------------
-- Records of {pre}course
-- ----------------------------
INSERT INTO `{pre}course` VALUES (1, '高等代数', 500.00, '这是一门挂了很多人的科目', 0, 0);
INSERT INTO `{pre}course` VALUES (2, '离散数学', 100.00, '这是一门没什么卵用的科目', 0, 0);
INSERT INTO `{pre}course` VALUES (3, '数学建模', 400.00, '这是一门爆肝的科目', 0, 0);

-- ----------------------------
-- Records of {pre}teaching_class
-- ----------------------------
INSERT INTO `{pre}teaching_class` VALUES (1, 1, '', NULL, NULL, 40, 30, '', 0, 0);
INSERT INTO `{pre}teaching_class` VALUES (2, 1, '', NULL, NULL, 40, 30, '上本课是进阶课程', 0, 0);
INSERT INTO `{pre}teaching_class` VALUES (3, 2, '该节课名字被修改', 50.50, '该节课简介被修改', NULL, NULL, NULL, 0, 0);
INSERT INTO `{pre}teaching_class` VALUES (4, 3, '', NULL, '该节课被删除', NULL, NULL, NULL, 1, 0);

-- ----------------------------
-- Records of {pre}user
-- ----------------------------
INSERT INTO `{pre}user` VALUES (1, '13336150000', 0, 0, 0, '2018-07-24 01:27:37', NULL);
INSERT INTO `{pre}user` VALUES (2, '13336150001', 0, 0, 0, '2018-07-24 01:29:58', NULL);
INSERT INTO `{pre}user` VALUES (3, '13336150002', 0, 0, 0, '2018-07-24 01:32:34', NULL);
INSERT INTO `{pre}user` VALUES (4, '13336150003', 0, 0, 0, '2018-07-24 01:36:55', NULL);
INSERT INTO `{pre}user` VALUES (5, '13336150004', 0, 0, 0, '2018-07-24 01:39:28', NULL);
INSERT INTO `{pre}user` VALUES (6, '18936150004', 0, 0, 0, '2018-07-24 01:44:39', NULL);
INSERT INTO `{pre}user` VALUES (7, '18936150001', 0, 0, 0, '2018-07-24 01:45:38', NULL);
INSERT INTO `{pre}user` VALUES (8, '18936150002', 0, 0, 0, '2018-07-24 01:47:29', NULL);

-- ----------------------------
-- Records of {pre}student
-- ----------------------------
INSERT INTO `{pre}student` VALUES (1, '王建柏', '13336150000', NULL, '13336150000', 0, NULL, '高一');
INSERT INTO `{pre}student` VALUES (2, '张博远', NULL, '', NULL, 1, NULL, '初三');
INSERT INTO `{pre}student` VALUES (3, '小江', NULL, '四川', NULL, 0, NULL, '高三');
INSERT INTO `{pre}student` VALUES (4, '小英', NULL, '', 'None', 1, NULL, '大三');
INSERT INTO `{pre}student` VALUES (5, '小李', NULL, '', 'None', 0, '1998-10-23', '大二');

-- ----------------------------
-- Records of {pre}teacher
-- ----------------------------
INSERT INTO `{pre}teacher` VALUES (6, '李老师', 0, '', NULL, '', '这是一位负责人的老师');
INSERT INTO `{pre}teacher` VALUES (7, '裘老师', 0, '123456789@gmail.com', NULL, '', NULL);
INSERT INTO `{pre}teacher` VALUES (8, '杨老师', 0, '', '172655165', '', '');

-- ----------------------------
-- Records of {pre}class_student
-- ----------------------------
INSERT INTO `{pre}class_student` VALUES (1, 1, 1);
INSERT INTO `{pre}class_student` VALUES (2, 1, 2);
INSERT INTO `{pre}class_student` VALUES (3, 1, 3);
INSERT INTO `{pre}class_student` VALUES (4, 1, 4);
INSERT INTO `{pre}class_student` VALUES (5, 1, 5);
INSERT INTO `{pre}class_student` VALUES (6, 2, 1);
INSERT INTO `{pre}class_student` VALUES (7, 2, 3);
INSERT INTO `{pre}class_student` VALUES (8, 3, 1);
INSERT INTO `{pre}class_student` VALUES (9, 3, 5);
INSERT INTO `{pre}class_student` VALUES (10, 4, 1);

-- ----------------------------
-- Records of {pre}class_teacher
-- ----------------------------
INSERT INTO `{pre}class_teacher` VALUES (1, 1, 6);
INSERT INTO `{pre}class_teacher` VALUES (2, 1, 8);
INSERT INTO `{pre}class_teacher` VALUES (3, 2, 8);
INSERT INTO `{pre}class_teacher` VALUES (4, 3, 7);