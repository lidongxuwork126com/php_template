<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/2/10
 * Time: 15:03
 */

namespace app\common;

/**
 * Class Result
 * @package app\common
 */
class Result
{
    /** 成功 */
    const SUCCESS = '10000';
    /** 程序错误 */
    const ERROR_MSG = '10001';
    /** 接口权限验证失败 */
    const ERROR_AUTH = '10002';
    
    /**
     * 权限错误, 验证失败返回值
     * @return array
     */
    public static function guest($data = [], $msg = '')
    {
        return json_encode(['result' => self::ERROR_AUTH, 'data' => $data, 'msg' => $msg, 'now' => Date::time()]);
    }
    
    /**
     * 没有数据返回的错误请求
     * @param string $msg
     * @param array  $data
     * @param string $code
     * @return array
     */
    public static function msg($data = [], $msg = '', $code = Result::ERROR_MSG)
    {
        return json_encode(['result' => $code, 'data' => empty($data) ? "" : $data, 'msg' => $msg, 'now' => Date::time()]);
    }
    
    /**
     * 获取客户端返回值Json成功数组
     * @param array|string $data 返回值
     * @param string       $msg
     * @return array
     */
    public static function success($data = [], $msg = '')
    {
        $data = Common::replaceNullData($data);
        // 把下划线都转成驼峰标识
        $data = Common::changeHumpData($data);
        return json_encode(['result' => self::SUCCESS, 'data' => empty($data) ? "" : $data, 'msg' => $msg, 'now' => Date::time()]);
    }
   
}
