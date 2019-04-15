<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/4/15
 * Time: 14:59
 */

namespace app\api\model;
use think\Db;
use app\common\Result;
class Member extends BaseModel
{
    // 登录更新jwt值
    public static function enterLoginJwt($uid, $jwt, $time){
        Db::startTrans();
        try{
            Member::where("uid", $uid)->update([
                'jwt' => $jwt,
                'jwt_time' => $time
            ]);
            Db::commit();
            return true;
        } catch (\Exception $e){
            Db::rollback();
            return false;
        }
    }
    
    // 注销
    public static function userOut($uid){
        Db::startTrans();
        try{
            Member::where("uid", $uid)->update([
                'jwt' => "",
                'jwt_time' => ""
            ]);
            Db::commit();
            return Result::success([], "登出成功");
        } catch (\Exception $e){
            Db::rollback();
            return Result::msg([], $e->getMessage());
        }
    }
}