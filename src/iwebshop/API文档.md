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

- /CreateWithPhone 用户自主注册
  - 参数:
    - phone 手机号
    - pin 短信验证码
    - role 角色(0: 学生, 1: 教师; 默认0)
  - 权限: None
- /CreateStudent 后台添加学生
  - 参数:
    - phone 手机号
  - 权限: user.write.all;student.write.all
- /CreateTeacher 后台添加教师
  - 参数:
    - phone 手机号
  - 权限: user.write.all;teacher.write.all
- /Delete 删除用户
  - 参数:
    - id 用户ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)
  - 权限: user.write.all;student.write.all;teacher.write.all
- /ChangePhone 更换手机号
  - 参数:
    - phone 新手机号
    - pin 短信验证码
  - 权限: user.write.owner

## 短信验证码 SMS

用于发生短信验证码。

- /SendPINWithPhone 发送登陆/用短信验证码
  - 参数:
    - phone 手机号
- /SendPIN 发送短信验证码
  - 参数: 无
  - 权限: 

## 课程 course

课程内容

- /Get 获取单条课程信息
  - 参数:
    - id 课程ID
- /List 获取课程列表
  - 参数:
    - page 页码(默认 1)
    - pagesize 每页数量(默认 20)
    - name (可选)课程名称，可以模糊匹配
- /Create 添加课程
  - 参数:
    - name 课程名称
    - price (可选)课程价格
    - introduction (可选)课程简介
- /Update 更新课程信息
  - 参数:
    - id 课程ID
    - name (可选)课程名称
    - price (可选)课程价格
    - introduction (可选)课程简介
- /Delete 删除课程
  - 参数:
    - id 课程ID
    - true_del 软/硬删除(true: 硬, false: 软; 默认 false)

## 教学班 class

教学班信息

- /Get 获取单条教学班信息
  - 参数:
    - id 教学班ID
- /List 获取教学班列表
  - 参数:
    