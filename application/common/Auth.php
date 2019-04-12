<?php
/**
 * PHP Version 7.1
 * @category  PHP
 * @package   Com.womenshigaoruanjiande.hyj
 * @author    hyj <hanyoujun@gmail.com>
 * @copyright 2018 womenshigaoruanjiande.com
 * @license   http://www.womenshigaoruanjiande.com/license/ No License
 * @link      http://www.womenshigaoruanjiande.com
 *
 */

namespace app\common;
use app\api\model\Member;

session_start();

/**
 * Class Auth
 * @package app\common
 */
class Auth
{
    // 根据UID, 生成这个用户的JWT的值(有效期根据数据库字段判断)
    // 获取: 验证是否登录的JWT码
    public static function getJWT($uid, $time) {
        return RSA::authcode(RSA::$key."/".$uid."/".$time);
    }
    
    // 获取: 上传权限使用的权限码(本地非7牛云)
    public static function getUploadCode($uid, $jwt){
        return RSA::authcode(Date::time()."/".$uid."/".$jwt);
    }
    
    // 验证: 接口请求权限验证
    public static function checkApiUseCode($authCode){
        // 前端发来的key和时间戳进行逐个判断
        $arr = explode("/", RSA::authcode($authCode, "D"));
        // 签名格式不对
        if (count($arr) <= 1){
            return "签名格式非法";
        }
        // 拿到key
        $key = $arr[0];
        // 拿到时间戳
        $time = $arr[1];
        // 拿到uuid值 (把中划线换成下划线)
        $uuid = $arr[2];
        // 超过60秒后再次请求, 判定为重放攻击
        if(Date::time() - $time > TIME_OUT){
           return "请求超时, 60秒后请求";
        }
        // 判断UUID是否已经存在, 请求只有一次有效
        if (array_key_exists($uuid, $_SESSION)) {
            return "url只能使用一次";
        }
        
        // 判断最后一次操作Session的时间如果大于60秒了, 清空整个Session
        if(isset($_SESSION['last_access']) && (time() - $_SESSION['last_access']) >= 60){
            session_destroy();
        }
        
        // 记录本次UUID到Session中
        $_SESSION[$uuid] = 1;
        $_SESSION['last_access'] = $time;
        
        return true;
    }
    
    // 验证: 登录jwt状态是否正确
    public static function checkJwtTimeOut($uid){
        $memberObj = Member::where([
            'uid' => $uid
        ]);
        // 判断登录状态是否过期
        if (!Date::effectiveTime2((int)($memberObj['jwt_time']), LOGIN_OUT_TIME)) {
            return array(
                'result'=> true,
                'msg' => '登录状态已经过期'
            );
        } else {
            return array(
                'result'=> false,
                'msg' => ''
            );
        }
    }
    
}
