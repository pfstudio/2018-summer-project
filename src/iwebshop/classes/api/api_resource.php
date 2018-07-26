<?php
return array(
    // 获取课程信息
    'getCourseInfo' => array(
        'query' => array(
            'name'   => 'course as c',
            'where'  => 'c.id = #id# and is_del = 0',
            'type'   => 'row'
        )
    ),
    // 获取课程列表
    'getCourseList' => array(
        'query' => array(
            'name' => 'course',
            'where' => 'is_del = 0',
            'fields' => 'id, name'
        )
    ),
    // 获取教学班信息
    'getClassInfo' => array(
        'query' => array(
            'name' => 'teaching_class as tc',
            'join' => 'left join course as c on c.id = tc.course_id',
            'fields' => 'tc.id,tc.name,tc.price,tc.total_num,tc.selected_num,tc.is_lock,tc.introduction,tc.comment,'.
                        'c.id as course_id, c.name as course_name',
            'where' => 'tc.id = #id# and tc.is_del = 0',
            'type' => 'row'
        )
    ),
    // 获取学生信息
    'getStudentInfo' => array(
        'query' => array(
            'name'   => 'student as s',
            'join'   => 'left join user as u on u.id = s.user_id',
            'fields' => 'u.id,s.*,u.phone,u.is_lock,u.is_del',
            'where'  => 's.user_id = #id# and u.is_del = 0',
            'type'   => 'row'
        )
    ),
    // 获取教师信息
    'getTeacherInfo' => array(
        'query' => array(
            'name'   => 'teacher as t',
            'join'   => 'left join user as u on u.id = t.user_id',
            'fields' => 'u.id,t.*,u.phone,u.is_lock,u.is_del',
            'where'  => 't.user_id = #id# and u.is_del = 0',
            'type'   => 'row'
        )
    ),
);