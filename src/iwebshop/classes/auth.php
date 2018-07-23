<?php
/**
 * 权限信息相关操作
 * 
 * @author yiluomyt
 */
class Auth
{
    /**
     * 资源类型
     * 
     * @var string
     */
    public $type;
    /**
     * 操作类型
     * read/write
     * 
     * @var string
     */
    public $operate;
    /**
     * 权限类型
     * owner/all
     * 
     * @var string
     */
    public $auth;

    /**
     * 私有构造函数
     * 创建Auth对象，通过parse进行
     * 
     * @param string 权限信息
     */
    private function __construct(string $info)
    {
        $params = explode('.', $info);
        $this->type = $params[0];
        $this->operate = $params[1];
        $this->auth = $params[2];
    }

    /**
     * 解析权限信息
     * @param string $info 权限信息
     * 
     * @throws InvalidArgumentException 权限信息格式错误
     * @return Auth 权限对象
     */
    public static function parse(string $info)
    {
        // 用正则匹配权限信息格式
        if(!preg_match('/^([a-z]+|\*).(read|write|\*).(owner|all|\*)$/', $info))
            throw new InvalidArgumentException('权限信息格式错误');
        return new Auth($info);
    }
    
    /**
     * 匹配权限类型
     * @param Auth $target 所需权限信息
     * @param array $scopes 已授权权限
     * 
     * @return null/owner/all
     */
    public static function match(Auth $target, array $scopes)
    {
        foreach ($scopes as $scope) {
            // 判定权限资源类型
            if($target->type != $scope->type && $scope->type != '*')
                continue;
            // 判定权限资源操作
            if($target->operate != $scope->operate && $scope->operate != '*')
                continue;
            // 确定权限类型
            if($target->auth == $scope->auth || $target->auth == '*' || $scope->auth == '*')
            {
                if($target->auth == 'owner' || $scope->auth == 'owner')
                    return 'owner';
                else
                    return 'all';
            }
        }
        return null;
    }
}