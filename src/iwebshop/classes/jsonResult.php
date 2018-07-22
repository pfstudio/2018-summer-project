<?php
/**
 * 规范化返回的JSON结果
 */
class JsonResult
{
    /**
     * 操作成功
     * @param $data 需要返回的数据，默认为null
     */
    public static function success($data = null)
    {
        // 设置返回内容为json
        header('Content-type:application/json');
        return JSON::encode(array(
            'data'   => $data,
            'status' => 'success',
            'error'  => null
        ));
    }

    /**
     * 操作失败
     * @param $error 错误信息
     */
    public static function fail($error)
    {
        // 设置返回内容为json
        header('Content-type:application/json');
        // 将返回的错误转换为统一的数组形式
        if(!is_array($error))
        {
            $error = array($error);
        }
        return JSON::encode(array(
            'data'   => null,
            'status' => 'fail',
            'error'  => $error
        ));
    }
}