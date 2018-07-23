<?php return array_change_key_case(array(
    'User@CreateStudent' => 'user.write.all;student.write.all',
    'User@CreateTeacher' => 'user.write.all;teacher.write.all',
    'User@Delete'        => 'user.write.all;student.write.all;teacher.write.all',
    'User@ChangePhone'   => 'user.write.*'
));
?>