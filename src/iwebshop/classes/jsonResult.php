<?php
class JsonResult
{
    public static function success($data = null)
    {
        return JSON::encode(array(
            'data'   => $data,
            'status' => 'success',
            'error'  => null
        ));
    }

    public static function fail($error)
    {
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