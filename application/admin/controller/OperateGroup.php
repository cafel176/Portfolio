<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/28
 * Time: 12:08
 */

namespace app\admin\controller;

use app\admin\controller\common\Basic;
use app\common\Factory\DataFactory;

class OperateGroup extends Basic
{
    protected function getTemplateName()
    {
        return 'operategroup';
    }

    protected function getTitle()
    {
        return lang('system_title_operategroup');
    }

    protected function getParams()
    {
        $arr = parent::getParams();

        $arr["success"] = input("success");
        $arr["search"] = input("search");

        $params = input("post.");
        foreach($params as $k=>$val)
        {
            $arr[$k] = $val;
        }

        return $arr;
    }

    protected function setParams($arr = array())
    {
        parent::setParams($arr);

        $this->assign('success', $arr["success"]);
        $this->assign('search',$arr["search"]);

        $dt = DataFactory::getDataTransaction($arr['top']);
        $struct = $dt->getTableStruct();
        $this->assign('groupstruct',$struct);

        $limits = $this->checkLimits($arr['limits']);
        $query = array();
        if($limits==null)
            $query = $dt->getTable();
        else
            $query['limit'] = $limits;

        $where = $dt->whereArray($query);
        $groups = $dt->select($where);
        $this->assign('groups',$groups);
    }

    public function change()
    {
        $top = input("top");
        $group = input("group");
        $params = input("post.");
        $search = input("search");

        $query = array('canedit'=>0);
        $dt = DataFactory::getDataTransaction($top);
        $changetype = '';
        $searchName = '';
        foreach($params as $k=>$val)
        {
            if($k=='changetype')
                $changetype = $val;
            else if($k=='searchName')
                $searchName = $val;
            else if($k=='password')
                $query['password'] = $this->password($val);
            else
                $query[$k] = $val;
        }

        $success = 0;
        try
        {
            if($changetype == 'update')
            {
                $where = $dt->whereArray(array('name'=>$searchName));
                $success = $dt->update($where,$query);

                if($success > 0)
                {
                    $dt2 = DataFactory::getDataTransaction($top.'_subgroup');
                    $where = $dt2->whereArray(array('group'=>$searchName));
                    $dt2->update($where,array('group'=>$query['name']));
                }
            }
            else if($changetype == 'delete')
            {
                $dt2 = DataFactory::getDataTransaction($top.'_subgroup');
                $where2 = $dt2->whereArray(array('group'=>$searchName));
                $re = $dt2->columnText($where2,'name');

                $dt3 = DataFactory::getDataTransaction($top.'_table');
                $where3 = $dt3->whereArray(array('subgroup'=>array('in',explode(',',$re))));
                $dt3->delete($where3);

                $dt2->delete($where2);

                $where = $dt->whereArray(array('name'=>$searchName));
                $success = $dt->delete($where);
            }
            else if($changetype == 'insert')
            {
                $success = $dt->insert($query);
            }
        }
        catch (Exception $e)
        {
            $success = 0;
        }

        $this->redirect(url('/groupedit', ['top'=>$top,'group'=>$group,'success'=>$success,'search'=>$search]));
    }
}