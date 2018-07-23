<?php
/**
 * 权限检验插件
 * 
 * 根据Token中所提供的Scopes与配置文件中的权限信息相匹配
 * 拦截未授权的请求，并对只有owner权限的请求设置isOwnered为true
 * 
 * @author yiluomyt
 */
class _authorization extends pluginBase
{
    public function reg()
    {
        plugin::reg('onBeforeCreateAction', $this, 'authorizationCheck');
    }

    /**
	 * @brief 权限校验
	 * @param string $actionId 动作ID
	 */
	public function authorizationCheck($actionId)
	{
        // 读取权限配置文件
        $config = (new Config('auth_config'))->getInfo();
        // 获取控制器ID
        $controllerId = self::controller()->getId();
        // 获取对应权限信息的key
        $authKey = strtolower($controllerId.'@'.$actionId);
        // 判定该action是否需要权限
        if(isset($config[$authKey]))
        {
            // 获取权限信息
            $authInfos = $config[$authKey];
            // 获取Token
            $jwt = self::getBearerToken();
            // 若Token不存在则禁止访问
            if(!$jwt) JsonResult::forbid();
            // 检验Token的有效性
            //JWTToken::check($jwt);
            // 获取Token中的信息
            $claims = JWTToken::parse($jwt);
            if(!(isset($claims['scopes']) && is_array($claims['scopes']->getValue())))
                JsonResult::fail('Token格式错误');
            try
            {
                $scopes = array_map([Auth::class, 'parse'], $claims['scopes']->getValue());
            }
            catch(InvalidArgumentException $e)
            {
                JsonResult::fail('Token格式错误');
            }
            // 判定是否可以权限
            if(!self::isAuthorized($authInfos, $scopes))
                // 不能权限则禁止访问
                JsonResult::forbid();
        }
    }
    
    /**
     * 匹配目标权限和已授予权限
     * @param string $authInfos 目标权限
     * @param array $scopes 已授予权限
     * 
     * @return bool
     */
    function isAuthorized(string $authInfos, array $scopes)
    {
        // 拆分权限信息
        foreach(explode(';', $authInfos) as $info)
        {
            // 解析权限信息
            $target = Auth::parse($info);
            // 匹配权限
            $auth = Auth::match($target, $scopes);
            // 若无权限则禁止访问
            if($auth == null)
                JsonResult::forbid();
            // 若为owner权限，需再检验资源所有权
            if($auth == 'owner')
                self::controller()->isOwnered = true;
        }
        return true;
    }
    
    /** 
     * 获取Headers中的Authorization
     * */
    function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } 
        elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
    * 从Headers中获取Token
    * */
    function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}