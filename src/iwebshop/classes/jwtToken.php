<?php
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * JWT Token相关操作
 * 
 * @author yiluomyt
 */
class JWTToken
{
    private static $issuer = 'pf_jwt';

    /**
     * 生成JWT Token
     * @param array $claims 需要加载的信息
     * @param string $audience 授权的对象，默认为小程序
     * 
     * @return string JWT Token
     */
    public static function generate(array $claims, string $audience = 'wechatMiniProgram')
    {
        $builder = new Builder();
        // 设置颁发者，接收者，过期时间
        $builder->setIssuer(self::$issuer)
                ->setAudience($audience)
                ->setExpiration(time() + 3600);
        // 添加Claims信息
        foreach ($claims as $name => $value) {
            $builder->set($name, $value);
        }
        // 签名
        $builder->sign(new Sha256(), self::getPrivateKey());
        // 生成Token对象
        $token = $builder->getToken();
        // 转换为字符串并返回
        return (string)$token;
    }

    /**
     * 检验JWT Token是否有效
     * 若无效则直接返回错误原因
     * @param string $jwt JWT Token
     */
    public static function check(string $jwt)
    {
        // 解析JWT Token
        $token = (new Parser())->parse($jwt);
        // 验证签名
        if(!$token->verify(new Sha256(), self::getPrivateKey()))
        {
            JsonResult::fail('非法Token');
        }
        // 验证过期时间
        if($token->isExpired())
        {
            JsonResult::fail('Token已过期');
        }
    }

    /**
     * 解析JWT Token中的信息
     * @param string $jwt JWT Token
     * 
     * @return array claims
     */
    public static function parse(string $jwt)
    {
        $token = (new Parser())->parse($jwt);
        return $token->getClaims();
    }

    /**
     * 获取私钥
     */
    private static function getPrivateKey()
    {
        return IWeb::$app->config['encryptKey'];
    }
}