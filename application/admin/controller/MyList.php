<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/23
 * Time: 0:00
 */

namespace app\admin\controller;

use app\admin\controller\common\Basic;
use app\common\Factory\DataFactory;

class MyList extends Basic
{
    protected function getTemplateName()
    {
        return 'list';
    }

    protected function getTitle()
    {
        return lang('system_title_list');
    }

    protected function getParams()
    {
        $arr = parent::getParams();

        $arr["filter_type"] = input("filter_type");
        $arr["filter_text"] = input("filter_text");
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

        $this->assign('success',$arr["success"]);
        $this->assign('search',$arr["search"]);

        $dt = DataFactory::getDataTransaction($arr['top'].'_subgroup');
        $struct = $dt->getTableStruct();
        $this->assign('subgroupstruct',$struct);

        $limits = $this->checkLimits($arr['limits']);
        $query = array('group'=>$arr['group']);
        if($limits!=null)
            $query['limit'] = $limits;

        $where = $dt->whereArray($query);
        $subgroups = $dt->select($where);
        $this->assign('subgroups',$subgroups);

        $dt = DataFactory::getDataTransaction($arr['top'].'_table');
        $struct = $dt->getTableStruct();
        $this->assign('structtype',$struct);

        $filter_type = '';
        $filter_text = '';
        if(array_key_exists("filter_type",$arr))
            $filter_type = $arr["filter_type"];
        if(array_key_exists("filter_text",$arr))
            $filter_text = $arr["filter_text"];
        if(!empty($filter_type) && !empty($filter_text))
        {
            $query[$filter_type]=$filter_text;
        }

        $where = $dt->whereArray($query);
        $re = $dt->select($where);

        foreach($subgroups as $key=>$v)
        {
            $a = array();
            foreach($struct as $k=>$val)
            {
                $col_name = $val['COLUMN_NAME'];
                $can_null = $val['IS_NULLABLE'];
                $default = $val['COLUMN_DEFAULT'];

                if($col_name=='id')
                    $a['id'] = 0;
                else if($col_name=='subgroup')
                    $a['subgroup'] = $v['name'];
                else if($can_null=='NO')
                    $a[$col_name] = $default;
                else
                    $a[$col_name] = '';
            }
            array_unshift($re,$a);
        }
        $this->assign('list',$re);
    }

    public function change()
    {
        $params = input("post.");

        $table = input("table");
        $top = input("top");
        $group = input("group");
        $search = input("search");

        $id = input("id");
        $subgroup = input("subgroup");

        $query = array('group'=>$group,'canedit'=>0);
        if($table=='_table')
            $query['subgroup'] = $subgroup;

        $dt = DataFactory::getDataTransaction($top.$table);
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
                $where = null;
                if($table=='_subgroup')
                    $where = $dt->whereArray(array('name'=>$searchName));
                else
                    $where = $dt->whereArray(array('id'=>$id));
                $success = $dt->update($where,$query);

                if($table == '_subgroup' && $success > 0)
                {
                    $dt2 = DataFactory::getDataTransaction($top.'_table');
                    $where = $dt2->whereArray(array('subgroup'=>$searchName));
                    $dt2->update($where,array('subgroup'=>$query['name']));
                }
            }
            else if($changetype == 'delete')
            {
                $where = null;
                if($table=='_subgroup')
                    $where = $dt->whereArray(array('name'=>$searchName));
                else
                    $where = $dt->whereArray(array('id'=>$id));

                $success = $dt->delete($where);

                if($table == '_subgroup' && $success > 0)
                {
                    $dt2 = DataFactory::getDataTransaction($top.'_table');
                    $where = $dt2->whereArray(array('subgroup'=>$searchName));
                    $dt2->delete($where);
                }
            }
            else if($changetype == 'insert')
            {
                $query['group'] = $group;
                $success = $dt->insert($query);
            }
        }
        catch (Exception $e)
        {
            $success = 0;
        }

        $this->redirect(url('/adminlist', ['top'=>$top,'group'=>$group,'success'=>$success,'search'=>$search]));
    }

}