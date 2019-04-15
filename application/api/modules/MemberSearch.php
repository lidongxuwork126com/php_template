<?php
namespace app\api\modules;
use app\api\model\Member;
use app\common\Auth;
use app\common\Date;
use app\common\Result;

/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/4/15
 * Time: 14:59
 */
class MemberSearch
{
    // 验证jwt合法性
    public static function searchJwt($uid, $jwt){
        return Member::where([
            'uid' => $uid,
            "jwt" => $jwt
        ])->find();
    }
    
    // 用户名+密码登录
    public static function searchAccountPass($userName, $userPass){
        $userObj = Member::where([
            'user_name' => $userName,
            'user_password' => $userPass
        ])->find();
        $time = Date::time();
        $jwt = Auth::getJWT($userObj['uid'], $time);
        // 登录信息同步到数据库中
        if (Member::enterLoginJwt($userObj['uid'], $jwt, $time)){
            if ($userObj){
                return Result::success([
                    'token' => $jwt,
                    "uid" => $userObj['uid']
                ], "登录成功");
            } else {
                return Result::msg([], "账号或密码错误, 请检查");
            }
        } else {
            return Result::msg([], "登录状态录入数据库失败");
        }
        
    }
    
    // 获取用户信息
    public static function searchUserInfo($uid){
        $userObj = Member::where([
            'uid' => $uid
        ])->find();
        return Result::success([
            'roles' => ['admin'],
            "name" => $userObj['user_nickname'],
            "avatar" => is_null($userObj['user_head_url']) ? STATIC_IMG."/default_head.png" : $userObj['user_head_url']
        ]);
    }
}