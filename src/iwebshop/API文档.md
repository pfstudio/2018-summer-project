# API文档

## 权限说明

这里我们希望做的是基于资源的授权，每一条授权信息包括以下三个内容：

1. type    资源类型
2. operate 操作类型
3. auth    权限类型

权限信息格式为：`{type}.{operate}.{auth}`，
多条权限信息之前使用`;`分隔。

每一个用户（包括学生/教师）或管理员对应一个权限对象。

每一个权限对象可以拥有多条权限信息。

### 资源类型

可以用*匹配任意资源。

### 操作类型

包括以下类型：

1. read  读操作
2. write 写操作

可用*匹配任意操作。

### 权限类型

包括以下类型：

1. Owner 所有者权限
  仅对拥有所有权的资源进行授权。
2. All   全体权限
  对任意资源进行授权。

可用*匹配任意权限。

### 资源所有权的判定

因为资源的存储格式不同，无法提供统一的判断逻辑。

这里希望实现实现的判定流程如下：

1. 根据调用的API确定所访问的资源类型。
2. 约定所有资源都通过id进行索引，以便确定对应的资源对象。
3. 通过Token确定执行操作的用户对象。
4. 根据资源类型映射到对应的判定函数。
  > 约定判定函数均接受两个参数：资源对象，用户对象。

## Token说明

Token计划采用JWT Token的形式。

其中Claims包括以下内容：

- uid  用户/管理员的id
- role 身份（student 学生/teacher 教师/admin 管理员）
- name 姓名

## 符号说明

- {type} 返回单个某类型的资源
- [types] 返回某类型资源的列表
- type@prop 表示对应type的类型的prop属性
- {type:prop}/[types:prop] 使用prop限制资源的内容

## 登陆/授权 Auth

- /Login 登陆(学生/教师)
  - 参数:
    - phone 手机号
    - pin 短信验证码
  - 权限: None
  - success:
    - token JWT Token
- /AdminLogin 管理员登陆
  - 参数:
    - admin_name 管理员用户名
    - password 密码
  - 权限: None
  - success:
    - token JWT Token
- /Logout 登出
  > 将token列入黑名单
  - 参数:
    - token JWT Token
  - 权限: None
  - success: null
- /RefreshToken 刷新Token
  - 参数:
    - token JWT Token
  - 权限: None
  - success:
    - token 新的JWT Token
- /WechatLogin 微信登陆
  - 待定
- /WechatBind 绑定微信
  - 待定

## 公开信息 Public

- /Get 获取一条指定类型的公开信息
  - 参数:
    - id type@ID
    - type 指定的资源类型
  - 权限: None
  - success:
    - {type:public} {type}的公开信息
- /List 获取指定类型的公开信息列表
  - 参数:
    - type 指定的资源类型
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
  - 权限: None
  - success:
    - [types:public] {type}的公开信息列表

## 用户相关 User

包括用户（学生/教师）统一性接口，以及账号相关操作。

- /CreateWithPhone 学生自主注册
  - 参数:
    - phone 手机号
    - pin 短信验证码
  - 权限: None
  - success:
    - token JWT Token
- /ChangePhone 更换手机号
  - 参数:
    - phone 新手机号
    - pin 短信验证码
  - 权限: user.write.owner
  - success: null
- /CreateStudent 后台添加学生
  - 参数:
    - phone 手机号
  - 权限: user.write.all;student.write.all
  - success:
    - id 学生ID
- /CreateTeacher 后台添加教师
  - 参数:
    - phone 手机号
  - 权限: user.write.all;teacher.write.all
  - success:
    - id 教师ID
- /Delete 后台删除用户
  - 参数:
    - id 用户ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: user.write.all;student.write.all;teacher.write.all
  - success: null
- /Restore 恢复软删除用户
  - 参数:
    - id 用户ID
  - 权限: user.write.all;student.write.all;teacher.write.all
  - success: null

## 短信验证码 SMS

用于发生短信验证码。

- /SendPINWithPhone 发送登陆/用短信验证码
  - 参数:
    - phone 手机号
  - 权限: None
  - success: null
- /SendPIN 发送短信验证码
  - 参数: None
  - 权限: user.read.owner
  - success: null

## 课程 course

课程内容

- /Get 获取单条课程信息
  - 参数:
    - id 课程ID
  - 权限: course.read.*
  - success:
    - {course} 课程信息
- /List 获取课程列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - name (可选)课程名称，可以模糊匹配
  - 权限: course.read.all
  - success:
    - [courses] 课程信息列表
- /Create 添加课程
  - 参数:
    - name 课程名称
    - price (可选)课程价格
    - introduction (可选)课程简介
  - 权限: course.write.all
  - success:
    - {course} 课程信息
- /Update 更新课程信息
  - 参数:
    - id 课程ID
    - name (可选)课程名称
    - price (可选)课程价格
    - introduction (可选)课程简介
  - 权限: course.write.all
  - success:
    - {course} 课程信息
- /Delete 删除课程
  - 参数:
    - id 课程ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: course.write.all
  - success: null

## 教学班 Class

教学班信息

- /Get 获取单条教学班信息
  - 参数:
    - id 教学班ID
  - 权限: class.read.*
  - success:
    - {class} 教学班信息
- /List 获取教学班列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - course_id (可选)课程ID
    - name (可选)教学班名称
  - 权限: class.read.all
  - success:
    - [classes] 教学班列表
- /Create 新建教学班
  - 参数:
    - course_id 课程ID
    - name (可选)教学班名称,若为空则继承课程名称
    - price (可选)教学班价格,若为空则继承课程价格
    - introduction (可选)教学班介绍,若为空则继承课程简介
    - total_num (可选)教学班容量上限
    - comment (可选)教学班注释，仅对内可见
  - 权限: class.write.all
  - success:
    - id 教学班ID
- /Update 更新教学班信息
  - 参数:
    - id 教学班ID
    - name (可选)教学班名称
    - price (可选)教学班价格
    - introduction (可选)教学班介绍
    - total_num (可选)教学班容量上限
    - comment (可选)教学班注释，仅对内可见
    - status 教学班状态(0: 正常,1: 不可报名;默认 0)
  - 权限: class.write.*
  - success:
    - {class} 教学班信息
- /Delete 删除教学班
  - 参数:
    - id 教学班ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: class.write.all
  - success: null
- /Register 报名
  - 参数:
    - id 教学班ID
  - 权限: user.read.owner
  - success: null
- /UnRegister 取消报名
  - 参数:
    - id 教学班ID
  - 权限: user.read.owner
  - success: null
- /AddStudent 为教学班添加学生
  - 参数:
    - id 教学班ID
    - user_id 学生ID
  - 权限: class.write.*
  - success: null
- /RemoveStudent 移除教学班的学生
  - 参数:
    - id 教学班ID
    - user_id 学生ID
  - 权限: class.write.*
  - success: null
- /AddTeacher 为教学班添加教师
  - 参数:
    - id 教学班ID
    - teacher_id 教师ID
  - 权限: class.write.all
  - success: null
- /RemoveTeacher 移除教学班的教师
  - 参数:
    - id 教学班ID
    - teacher_id 教师ID
  - 权限: class.write.all
  - success: null
- /GetTeachers 获取教学班的授课老师
  - 参数:
    - id 教学班ID
  - 权限: None
  - success:
    - [teachers:public] 教师公开信息列表
- /GetStudents 获取教学班的学生
  - 参数:
    - id 教学班ID
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
  - 权限: None
  - success:
    - [students:public] 学生公开信息列表
- /GetClassesWithTeacher 获取教师教授的教学班
  - 参数:
    - id 教师ID
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
  - 权限: None
  - success:
    - [classes:public] 教学班公开信息列表

## 教师相关

教师统一性接口，以及账号相关操作。

- /Get 获取单条教师信息
  - 参数:
    - id 教师ID
  - 权限: teacher.read.*
  - success:
    - {teacher} 教师信息
- /List 获取教师信息
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - name (可选)教师姓名
  - 权限: teacher.read.all
  - success:
    - [teachers] 教师信息列表
- /Update 更新教师信息
  - 参数:
    - id 教师ID
    - name 真实姓名
    - sex 性别
    - email 电子邮件
    - wechat 微信号
    - photo 教师照片URL
    - introduction 教师介绍
  - 权限: teacher.write.*
  - success:
    - {teacher} 修改后的教师信息
- /Delete 删除教师
  - 参数:
    - id 教师ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: teacher.write.all
  - success: null
- /GetClasses 获取教师所教授的教学班
  - 参数: None
  - 权限: teacher.read.owner
  - success:
    - [classes] 教学班列表

## 学生 student

- /Get 获取单条学生信息
  - 参数:
    - id 学生ID
  - 权限: student.read.*
  - success:
    - {student} 学生信息
- /List 获取学生信息
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - name (可选)学生姓名
  - 权限: student.read.all
  - success:
    - [students] 学生信息列表
- /Update 更新学生信息
  - 参数:
    - id 学生ID
    - parents_phone (可选)家长手机
    - address (可选)学生地址
    - wechat  (可选)学生微信号
    - sex (可选)学生性别
    - birthday (可选)学生生日
    - grade (可选)学生年级
  - 权限: student.write.*
  - success:
    - {student} 修改后的学生信息
- /Delete 删除学生
  - 参数:
    - id 学生ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: student.write.all
  - success: null
- /GetClasses 获取学生上课的教学班
  - 参数: None
  - 权限: student.read.owner
  - success:
    - [classes] 教学班列表

## 管理员 admin

- /Get 获取管理员信息
  - 参数:
    - id 管理员ID
  - 权限: admin.read.*
  - success:
    - {admin} 管理员信息
- /List 获得管理员列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
  - 权限: admin.read.all
  - success:
    - [admins] 管理员列表
- /Create 添加管理员
  - 参数:
    - admin_name 用户名
    - password 密码
    - email 邮箱
    - phone 手机
  - 权限: admin.write.all
  - success: null
- /Update 更新管理员信息
  - 参数:
    - id 管理员ID
    - email (可选)邮箱
    - phone (可选)手机号
  - 权限: admin.write.*
  - success: null
- /Delete 删除管理员用户
  - 参数:
    - id 管理员ID
    - true_del 软/硬删除(True 硬, false 软, 默认 false)
  - 权限: admin.write.all
  - success: null