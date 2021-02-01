<?php
namespace app\index\controller;

use app\index\controller\common\Basic;

class Index extends Basic
{
    protected function getTemplateName()
    {
        return 'index';
    }

    protected function getTitle()
    {
        return lang('rabbitonGames');
    }

    protected function setParams()
    {
        parent::setParams();
    }



}
