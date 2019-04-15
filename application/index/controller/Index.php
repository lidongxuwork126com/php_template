<?php
namespace app\index\controller;

use app\common\Auth;
use app\common\Result;
use app\common\RSA;

class Index
{
    public function index()
    {
        return "首页";
    }
    
    // 获取key值给前端
    public function news(){
        return "(function(){window[\"\x6b\x65\x79\"] = ".'\''.RSA::$key.'\''.";})()";
    }
    
}
