<?php
/**
 * Created by PhpStorm.
 * User: lidongxu
 * Date: 2019/3/19
 * Time: 17:20
 */

namespace app\common;

use PHPMailer\PHPMailer\PHPMailer;

// 验证码类
class Code
{
    // 发送邮件
    public static function sendEmail($data = []){
        // 第一步安装: $ composer require phpmailer/phpmailer
        // 第二步: 编写如下代码
            $mail = new PHPMailer(true);
            $mail->isSMTP(); // 启用SMTP
            $mail->Host = 'smtp.126.com'; //SMTP服务器 以126邮箱为例子
            $mail->Port = 465;  //邮件发送端口
            $mail->SMTPAuth = true;  //启用SMTP认证
            $mail->SMTPSecure = "ssl";   // 设置安全验证方式为ssl
            $mail->CharSet = "UTF-8"; //字符集
            $mail->Encoding = "base64"; //编码方式
            $mail->Username = 'lidongxu_work@126.com';  //你的邮箱
            $mail->Password = '520shouquan';  //你的密码
            $mail->Subject = '【友相册】验证'; //邮件标题
            $mail->From = 'lidongxu_work@126.com';  //发件人地址（也就是你的邮箱）
            $mail->FromName = '友相册';  //发件人姓名
            if($data && is_array($data)){
                foreach ($data as $k=>$v){
                    $mail->addAddress($v['user_email']); //添加收件人（地址，昵称）
                    $mail->isHTML(true); //支持html格式内容
                    $mail->Body = "【友相册】欢迎使用友相册，验证码:".$v['content']; //邮件主体内容

                    if ($mail->send()) {
                        return true;
                    }else{
                        return false;
                    }
                }
            } else {
                return false;
            }
            
            return false;
    }
}