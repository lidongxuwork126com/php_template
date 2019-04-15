<?php

namespace app\api\controller;

/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/2/10
 * Time: 14:35
 */

use app\api\model\Member;
use app\api\modules\MemberSearch;
use app\common\Auth;
use app\common\Date;
use think\Controller;
use think\Request;
use app\common\Result;
use app\common\RSA;

// 基类
class BaseController extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->checkApiAuth();
        // 只要传了uid的接口, 都验证jwt, 没传uid就无需验证
        if ($this->getParam("uid") != null) {
            $this->checkLoginAuth();
        }
    }
    
    /**
     * 获取客户端参数
     * @param        $name
     * @param string $defaultValue
     * @return array|mixed
     */
    public function getParam($name)
    {
        if ($this->request) {
            $arg = $this->request->post($name);
            
            if ($arg == null || $arg == "") {
                return null;
            }
            
            if (is_null(json_decode($arg, true))) {
                return $arg;
            }
            return json_decode($arg, true);
        } else {
            return "";
        }
    }
    
    /**
     * 验证前端, AuthCode请求是否合法
     * @param $obj          数据传入
     * @return array        返回JSON信息数组
     */
    public function checkApiAuth()
    {
        // 从HTTP头部取出AuthCode
        // 前端传递AuthCode (由key+/+时间戳+/+uuid)再用公钥RSA加密得来的
        $header = new Request();
        $res = Auth::checkApiUseCode($header->header('AuthCode'));
        if (is_string($res)) {
            // 秘钥不对
            die(Result::guest([], $res));
        }
    }
    
    /**
     * 验证需要用户授权的jwt值是否合法
     */
    public function checkLoginAuth(){
        $uid = $this->getParam("uid");
        $header = new Request();
        $jwt = $header->header('Authorization');
        // 判断uid对应的jwt是否相同 (引入用户类)
        if (MemberSearch::searchJwt($uid, $jwt)){
            // 通过
        } else {
            die(Result::guest([], "用户权限验证错误"));
        }
    }
    
}