<?php return array_change_key_case(array(
    'User@ChangePhone'   => 'user.write.owner',
    'User@CreateStudent' => 'user.write.all;student.write.all',
    'User@CreateTeacher' => 'user.write.all;teacher.write.all',
    'User@Delete'        => 'user.write.all;student.write.all;teacher.write.all',
    'User@Restore'        => 'user.write.all;student.write.all;teacher.write.all',
));
?>