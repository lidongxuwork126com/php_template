<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/4/12
 * Time: 15:59
 */

namespace app\api\controller;


use app\api\model\Member;
use app\api\modules\MemberSearch;

class MemberController extends BaseController
{
    // 登录
    function loginIn(){
        return MemberSearch::searchAccountPass($this->getParam("account"), $this->getParam("pass"));
    }
    
    // 获取用户信息
    function userInfo(){
        return MemberSearch::searchUserInfo($this->getParam("uid"));
    }
    
    // 登出
    function userLogOut(){
        return Member::userOut($this->getParam("uid"));
    }
}