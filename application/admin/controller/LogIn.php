<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/28
 * Time: 13:45
 */

namespace app\admin\controller;

use app\admin\controller\common\Base;
use app\common\DataBase\DataTransaction;
use think\facade\Request;
use app\common\Factory\DataFactory;

use phpmailer\phpmailer;

class LogIn extends Base
{
    private $topTable = 'top';
    private $limitTable ='limit';
    private $admininfoTable ='admininfo';
    private $fileTable ='file';

    protected function getTemplateName()
    {
        return 'login';
    }

    protected function getTitle()
    {
        return lang('system_title_login');
    }

    //判断是否是post方法发送的数据：如果是则开始登陆
    public function login()
    {
        if (Request::instance()->isPost())
        {
            $admin_username = input('post.admin_username');//接收前台用户名
            $admin_password = input('post.admin_password');//接收前台密码
            if(empty($admin_username) || empty($admin_password))
            {
                $this->error(lang('login_hint_empty'));
            }
            //从数据库读取数据
            $dt = DataFactory::getDataTransaction('admininfo_table');
            $where = $dt->whereArray(['name'=>$admin_username]);
            $admin_info = $dt->select($where);
            $admin_info = current($admin_info);
            if(empty($admin_info))
            {
                $this->error(lang('login_hint_nouser'),url('/admin'));
            }
            else
            {
                if($admin_password != $admin_info['password'])
                {
                    $this->error(lang('login_hint_incorrect_pwd'),url('/admin'));
                }
                else
                {
                    $this->setSession('admin_username',$admin_username);
                    $this->setSession('admin_limits',$admin_info['limits']);
                    $this->afterLogIn();
                    $this->success(lang('login_hint_success'),url('/adminindex'));
                }
            }
        }
        else
        {//如果不是post，则返回登陆界面
            return $this->fetch('login');
        }
    }

    public function lang()
    {
        parent::lang();
        $this->redirect(url('/admin'));
    }

    public function logout()
    {
        $this->clearSession();//清空session
        return $this->success(lang('login_hint_out_success'),url('/admin'));//跳转到登录页面
    }

    private function afterLogIn()
    {
        $this->setSession('top',$this->topTable);
        $this->setSession('limit',$this->limitTable);
        $this->setSession('admininfo',$this->admininfoTable);
        $this->setSession('file',$this->fileTable);
    }

    public function index()
    {
        $this->initDB();

        if($this->hasSession('admin_username'))
        {
            return $this->redirect(url('/adminindex'));
        }
        else
        {
            $this->assign('title',$this->getTitle());

            return $this->fetch($this->getTemplateName());
        }
    }

    private function initDB()
    {
        if(!DataTransaction::hasTable($this->topTable.'_table'))
        {
            DataTransaction::initDB($this->topTable,$this->limitTable,$this->admininfoTable,$this->fileTable);
            $dt = DataFactory::getDataTransaction($this->admininfoTable.'_table');
            $dt->insert(['name'=>'admin','password'=>md5('password'),'limits'=>'all']);
            $dt = DataFactory::getDataTransaction($this->limitTable.'_table');
            $dt->insert(['name'=>'default']);
        }
    }

    //发送邮件用于找回密码，验证码逻辑是用户注册时间的md5
    //未实现
    public function email()
    {
        $toemail = 'xxx@qq.com';//定义收件人的邮箱

        $mail = new PHPMailer();

        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "xxx@163.com";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">
        $mail->Password = "xxxxxx";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="color:#333333;">
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式</span><span style="color:#333333;">
        $mail->Port = 994;// 163邮箱的ssl协议方式端口号是465/994

        $mail->setFrom("xxx@163.com","Mailer");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail,'Wang');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        //$mail->addReplyTo("xxx@163.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件

        $mail->Subject = "这是一个测试邮件";// 邮件标题
        $mail->IsHTML(true); //支持html格式内容
        $mail->Body = "邮件内容是 <b>您的验证码是：123456</b>，哈哈哈！";// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if(!$mail->send()){// 发送邮件
            return "Mailer Error: ".$mail->ErrorInfo;// 输出错误信息
        }else{
            return 'success';
        }
    }

}