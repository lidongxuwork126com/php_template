<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
define("HOST", "http://localhost");
define("STATIC_PATH", HOST."/friend/public/static");
define("USER_ALBUM", HOST.STATIC_PATH."/upload/userAlbum");
define("UPLOAD_IMG_TEMP", HOST.STATIC_PATH."/upload_img_temp");
// 分页每页数据条数
define("PAGE_COUNT", 10);
// 超时秒数
define("TIME_OUT", 60);
// 防止刷接口的, 验证码请求期限(60秒内再请求, 就不发送新的)
define("CODE_OUT", 60);
// 验证码有效期
define("CODE_VEOUT", 600);
// 登录状态过期时间(秒)
define("LOGIN_OUT_TIME", 86400);