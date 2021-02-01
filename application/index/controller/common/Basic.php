<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 6:42
 */

namespace app\index\controller\common;

use think\Controller;

abstract class Basic extends Controller
{
    protected abstract function getTemplateName();

    protected abstract function getTitle();

    protected function setParams()
    {
        $this->assign('title',$this->getTitle());
    }

    public function index()
    {
        $this->setParams();
        return $this->fetch($this->getTemplateName());
    }
}