<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/2/10
 * Time: 15:58
 */
namespace app\common;

use think\Request;

class Common
{
    
    function __construct()
    {
        
    }
    
    /**
     * 返回几个数组中不是空数组的数组
     */
    public static function returnNotEmptyArr()
    {
        $numargs = func_get_args();
        foreach ($numargs as $key => $val) {
            if (count($val) > 0) {
                return $val;
            }
        }
    }
    
    /**
     * 判断数组是否为空, 替换成空字符串
     */
    public static function returnArrFirst($arr)
    {
        if (count($arr) > 0) {
            return $arr[0];
        } else {
            return "";
        }
    }
    
    /**
     * 替换所有null字段为"", 数据库查询出来肯定是数组里放的对象
     */
    public static function replaceNullData($arr)
    {
        
        // 是对象型数组, 不是关联数组, 需要转成array
        if (!is_array($arr)) {
            $arr = $arr->toArray();
        }
        foreach ($arr as $key => $val) {
            // 如果还是数组/对象则递归调用方法, 执行一遍
            if (is_array($val) || is_object($val)) {
                $arr[$key] = Common::replaceNullData($val);
            } else {
                if (is_null($val)) {
                    $arr[$key] = "";
                }
            }
        }
        return $arr;
    }
    
    // 把下划线转成驼峰标识
    public static function changeHumpData($arr)
    {
        // 是对象型数组, 不是关联数组, 需要转成array
        if (!is_array($arr)) {
            $arr = $arr->toArray();
        }
        foreach ($arr as $key => $val) {
            // 如果还是数组/对象则递归调用方法, 执行一遍
            if (is_array($val) || is_object($val)) {
                $arr[$key] = Common::changeHumpData($val);
            } else {
                $v = $val;
                unset($arr[$key]);
                $arr[preg_replace_callback('/_+([a-z])/', function ($matches) {
                    return strtoupper($matches[1]);
                }, $key)] = $v;
                
            }
        }
        return $arr;
    }
    
    
    // GET网络请求
    public static function httpGet($url)
    {
        // 初始化对象
        $curl = curl_init();
        // curl_setopt() 设置会话参数
        // CURLOPT_RETURNTRANSFER 结果保存到字符串中
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // CURLOPT_TIMEOUT 超时秒数
        curl_setopt($curl, CURLOPT_TIMEOUT, TIME_OUT);
        // 注意:
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
//        curl_setopt($curl, CURLOPT_URL, $url);
        
        // 执行会话
        $res = curl_exec($curl);
        curl_close($curl);
        
        return $res;
    }
    
    
    // 解密小程序信息
    public static function decryptData($appid, $sessionKey, $encryptedData, $iv)
    {
        $OK = 0;
        $IllegalAesKey = -41001;
        $IllegalIv = -41002;
        $IllegalBuffer = -41003;
        $DecodeBase64Error = -41004;
        
        if (strlen($sessionKey) != 24) {
            return $IllegalAesKey;
        }
        $aesKey = base64_decode($sessionKey);
        
        if (strlen($iv) != 24) {
            return $IllegalIv;
        }
        $aesIV = base64_decode($iv);
        
        $aesCipher = base64_decode($encryptedData);
        
        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj = json_decode($result);
        if ($dataObj == NULL) {
            return $IllegalBuffer;
        }
        if ($dataObj->watermark->appid != $appid) {
            return $DecodeBase64Error;
        }
        $data = json_decode($result, true);
        
        return $data;
    }
    
    
    // 获取: UUID
    public static function getUUID($prefix = "")
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }
    
    // 生成 6位 验证码数字
    public static function getSixCode($length = 6)
    {
        return rand(pow(10, ($length - 1)), pow(10, $length) - 1);
    }
    
    
    // 返回必要字段检测
    public static function getNecessaryKey()
    {
        // status 数据有效为1
        // 有些表查询 需要设置有效值校验
        return array(
            'status' => 1
        );
    }
    
    // 计算用户身份
    public static function calUserRoleTitle($state)
    {
        // 账号身份(0超级管理员1渠道管理员2普通渠道员3咨询管理员4普通咨询师5财务管理员6财务7职规管理员8普通职规师9代理管理员10普通代理员11企业顾问管理员12企业顾问13讲师管理员14普通讲师)
        return STATE_ARR[$state]['value'];
    }
    
    // 计算用户权限(用于前端动态路由)
    public static function calUserRoles($state)
    {
        $arr = STATE_ARR;
        return [$arr[$state]['role']];
    }
}
