<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/30
 * Time: 1:54
 */

namespace app\admin\controller;

use app\admin\controller\common\Basic;

class About extends Basic
{
    protected function getTemplateName()
    {
        return 'about';
    }

    protected function getTitle()
    {
        return lang('system_title_about');
    }
}