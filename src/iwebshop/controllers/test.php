<?php
/**
 * @brief 测试
 * @class Test
 */
class Test extends IController
{
    function hello()
    {
        echo '控制器可以正常访问，可以再试下数据连接。';
    }

    function admin()
    {
        $userDB = new IModel('admin');
        $user = $userDB->getObj('id = 1');
        die(JSON::encode($user));
        return;
    }
}
?>