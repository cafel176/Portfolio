<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/29
 * Time: 19:54
 */

namespace app\admin\controller\common;

use think\Controller;

use think\facade\Session;
use think\facade\Cookie;

abstract class Base extends Controller
{
    protected abstract function getTemplateName();

    protected abstract function getTitle();

    protected function password($password)
    {
        return md5($password);
    }

    public function index()
    {
        $this->assign('title',$this->getTitle());

        return $this->fetch($this->getTemplateName());
    }

    protected function setSession($name,$value)
    {
        Session::set($name,$value);
    }

    protected function getSession($name)
    {
        return Session::get($name);
    }

    protected function hasSession($name)
    {
        return Session::has($name);
    }

    protected function clearSession()
    {
        return Session::clear();
    }

    public function lang()
    {
        $lang = input('language');
        Cookie::set('think_var',$lang);
    }
}