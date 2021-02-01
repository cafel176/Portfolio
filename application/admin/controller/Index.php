<?php

namespace app\admin\controller;

use app\admin\controller\common\Basic;
use app\common\Factory\DataFactory;

class Index extends Basic
{
    protected function getTemplateName()
    {
        return 'index';
    }

    protected function getTitle()
    {
        return lang('system_title_index');
    }

    protected function setParams($arr = array())
    {
        parent::setParams($arr);

        $limits = $this->checkLimits($arr['limits']);

        $data = array();
        $data['limit']=$this->showList('limit',$limits);
        $data['file']=$this->showList('file',$limits);
        $this->assign('indexdata',$data);
    }

    protected function showList($name,$limits)
    {
        $data = array();
        $dt = DataFactory::getDataTransaction($this->getSession($name).'_table');
        $struct = $dt->getTableStruct();
        $data['struct']=$struct;

        $query = $dt->getTable();
        if($limits!=null && $limits!='all')
        {
            if($name=='limit')
                $query = array('id' => $limits);
            else if($name=='file')
                $query = array('limit' => $limits);
        }

        $where = $dt->whereArray($query);
        $re = $dt->select($where);
        $data['list']=$re;

        return $data;
    }

    public function lang()
    {
        parent::lang();
        $this->redirect(url('/adminindex'));
    }
}