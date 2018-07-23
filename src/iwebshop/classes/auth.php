<?php
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
     * 授权类型
     * owner/all
     * 
     * @var string
     */
    public $auth;

    private function __construct(string $info)
    {
        $params = explode('.', $info);
        $this->type = $params[0];
        $this->operate = $params[1];
        $this->auth = $params[2];
    }

    /**
     * 解析授权信息
     */
    public static function parse(string $info)
    {
        if(!preg_match('/^([a-z]+|\*).(read|write|\*).(owner|all|\*)$/', $info))
            throw new InvalidArgumentException('授权信息格式错误');
        return new Auth($info);
    }
    
    public static function match(Auth $target, array $scopes)
    {
        foreach ($scopes as $scope) {
            // 判定授权资源类型
            if($target->type != $scope->type && $scope->type != '*')
                continue;
            // 判定授权资源操作
            if($target->operate != $scope->operate && $scope->operate != '*')
                continue;
            // 确定授权类型
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