<?php
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Hmac\Sha256;

/**
 * JWT Token相关操作
 */
class JWTToken
{
    private $issuer = 'pf_jwt';

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
        $builder->setIssuer($issuer)
                ->setAudience($audience)
                ->setExpiration(time() + 3600);
        foreach ($claims as $name => $value) {
            $builder->set($name, $value);
        }
        $builder->sign(new Sha256(), self::getPrivateKey());
        $token = $builder->getToken();
        return (string)$token;
    }

    /**
     * 检验JWT Token是否有效
     * 若无效则直接返回错误原因
     * @param string $jwt JWT Token
     */
    public static function check(string $jwt)
    {
        $token = (new Parser())->parse($jwt);
        if(!$token->verify(new Sha256(), self::getPrivateKey()))
        {
            JsonResult::fail('非法Token');
        }
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