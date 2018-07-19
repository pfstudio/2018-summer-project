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

## 用户相关 User

包括用户（学生/教师）统一性接口，以及账号相关操作。

- /Login 登陆
  - 参数:
    - phone 手机号
    - pin 短信验证码
  - 权限: None
  - success:
    - token JWT Token
- /CreateWithPhone 学生自主注册
  - 参数:
    - phone 手机号
    - pin 短信验证码
  - 权限: None
  - success:
    - token JWT Token
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
- /Delete 删除用户
  - 参数:
    - id 用户ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: user.write.all;student.write.all;teacher.write.all
  - success: null
- /ChangePhone 更换手机号
  - 参数:
    - phone 新手机号
    - pin 短信验证码
  - 权限: user.write.*
  - success: null

## 短信验证码 SMS

用于发生短信验证码。

- /SendPINWithPhone 发送登陆/用短信验证码
  - 参数:
    - phone 手机号
  - 权限: None
  - success: null
- /SendPIN 发送短信验证码
  - 参数: 无
  - 权限: user.read.owner
  - success: null

## 课程 course

课程内容

- /Get 获取单条课程信息
  - 参数:
    - id 课程ID
  - 权限: None
  - success:
    - {course} 课程信息
- /List 获取课程列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - name (可选)课程名称，可以模糊匹配
  - 权限: None
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

## 教学班 class

教学班信息

- /Get 获取单条教学班信息
  - 参数:
    - id 教学班ID
  - 权限: None
  - success:
    - {class} 教学班信息
- /List 获取教学班列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - course_id (可选)课程ID
    - name (可选)教学班名称
  - 权限: None
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
- /RegisterWithUser 后台添加报名
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
- /GetComment 获取教学班的备注信息
  - 参数:
    - id 教学班ID
  - 权限: class.read.*
  - success:
    - comment 教学班的备注信息
- /GetTeachers 获取教学班的授课老师
  - 参数:
    - id 教学班ID
  - 权限: None
  - success:
    - [teachers] 教师列表
- /GetStudents 获取教学班的学生
  - 参数:
    - id 教学班ID
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
  - 权限: None
  - success:
    - [students] 学生列表
