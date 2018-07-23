<?php
/**
 * 测试用接口
 */
class Test extends IController
{
    /**
     * 根据scopes字段返回对应的Token
     */
    function Token()
    {
        $scopes = IReq::get('scopes');
        $claims = array(
            'scopes' => $scopes
        );
        JsonResult::success(JWTToken::generate($claims));
    }

    /**
     * 测试控制器是否能正常访问
     */
    function Hello()
    {
        echo '控制器可以正常访问，可以再试下数据连接。';
    }

    /**
     * 测试MySQL数据库是否能正常连接
     */
    function admin()
    {
        $userDB = new IModel('admin');
        $user = $userDB->getObj('id = 1');
        die(JSON::encode($user));
        return;
    }
}
?>