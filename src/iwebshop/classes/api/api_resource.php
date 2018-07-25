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
    )   
);