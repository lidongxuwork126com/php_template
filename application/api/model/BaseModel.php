<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/3/18
 * Time: 16:15
 */

namespace app\api\model;

use think\Model;
class BaseModel extends Model
{
    protected $hidden = [
        "id",
        "status"
    ];
}