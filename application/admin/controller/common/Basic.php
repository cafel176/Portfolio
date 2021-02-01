<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 18:44
 */

namespace app\admin\controller\common;

abstract class Basic extends Base
{
    protected $left;
    protected $header;

    protected function getParams()
    {
        $user = $this->getSession('admin_username');
        $limits = $this->getSession('admin_limits');

        $top = input("top");
        $group = input("group");
        
        return array('user'=>$user,'top'=>$top,'group'=>$group,'limits'=>$limits);
    }

    protected function setParams($arr = array())
    {
        $this->assign('title',$this->getTitle());
        $this->assign('top',$arr['top']);
        $this->assign('group',$arr['group']);
        $this->assign('user',$arr['user']);
        $this->assign('limits',$arr['limits']);

        $limits = $this->checkLimits($arr['limits']);
        $this->left = new Left($limits,$this->getSession('top'));
        $this->setParamArray($this->left->index());

        $this->header = new Header();
        $this->setParamArray($this->header->index());
    }

    protected function setParamArray($params)
    {
        foreach($params as $k=>$val)
        {
            $this->assign($k,$val);
        }
    }

    protected function checkLimits($limits)
    {
        if($limits=='all')
            return null;
        else
            return array('in',$limits);
    }

    public function index()
    {
        //判断有无admin_username这个session，如果没有，跳转到登陆界面
        if(!$this->hasSession('admin_username'))
        {
            return $this->error('您没有登陆',url('/admin'));
        }
        else
        {
            $arr = $this->getParams();
            $this->setParams($arr);
            return $this->fetch($this->getTemplateName());
        }
    }
}