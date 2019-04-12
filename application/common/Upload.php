<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/2/18
 * Time: 10:32
 */

namespace app\common;

// 图片相关处理
class Upload
{
    // 保存网络图片到本地
    public static function uploadImgToLocal($imgUrl){
        $imageName = Auth::getUUID().".png";
        $imagePath = Common::$MemberImagePath.$imageName;
        $img = file_get_contents($imgUrl);
        if (file_put_contents(Common::$StaticPath.$imagePath, $img) > 0){
            return [
                "status"=> true,
                "imgPath"=> $imagePath,
                "imgType"=> "png"
            ];
        } else {
            return [
                "status"=> false,
            ];
        }
    }
}