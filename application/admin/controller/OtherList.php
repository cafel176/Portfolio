<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/29
 * Time: 21:12
 */

namespace app\admin\controller;

use app\admin\controller\common\Basic;
use app\common\DataBase\DataTransaction;
use app\common\Factory\DataFactory;

//只有有all的最高权限才能修改这种类型的表
class OtherList extends Basic
{
    protected function getTemplateName()
    {
        return 'list';
    }

    protected function getTitle()
    {
        return lang('system_title_otherlist');
    }

    protected function getParams()
    {
        $arr = parent::getParams();

        $arr["filter_type"] = input("filter_type");
        $arr["filter_text"] = input("filter_text");
        $arr["success"] = input("success");
        $arr["listtable"] = input("listtable");

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

        $this->assign('listtable',$arr["listtable"]);
        $this->assign('success',$arr["success"]);

        $dt = DataFactory::getDataTransaction($this->getSession($arr['listtable']).'_table');
        $struct = $dt->getTableStruct();
        $this->assign('structtype',$struct);

        $limits = $this->checkLimits($arr['limits']);
        $query = array();
        if($limits!=null)
            $query['limit'] = $limits;

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

        if($arr['listtable']!='file')
        {
            $a = array();
            foreach($struct as $k=>$val)
            {
                $col_name = $val['COLUMN_NAME'];
                $can_null = $val['IS_NULLABLE'];
                $default = $val['COLUMN_DEFAULT'];
                if($col_name=='id')
                    $a['id'] = 0;
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
        $id = input("id");
        $listtable = input("listtable");
        $top = input("top");
        $group = input("group");

        $query = array();

        $dt = DataFactory::getDataTransaction($this->getSession($listtable).'_table');
        $changetype = '';
        foreach($params as $k=>$val)
        {
            if($k=='changetype')
                $changetype = $val;
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
                $where = $dt->whereArray(array('id'=>$id));
                $item = $dt->select($where);
                $name = current($item)['name'];
                $success = $dt->update($where,$query);

                if($listtable == 'top' && $success > 0)
                {
                    DataTransaction::RenameTable($name,$query['name']);
                    DataTransaction::RenameTable($name.'_subgroup',$query['name'].'_subgroup');
                    DataTransaction::RenameTable($name.'_table',$query['name'].'_table');
                }
            }
            else if($changetype == 'delete')
            {
                $where = $dt->whereArray(array('id'=>$id));
                $item = $dt->select($where);
                $name = current($item)['name'];
                $success = $dt->delete($where);

                if($listtable == 'top' && $success > 0)
                {
                    DataTransaction::deleteTable($name);
                    DataTransaction::deleteTable($name.'_subgroup');
                    DataTransaction::deleteTable($name.'_table');
                }
            }
            else if($changetype == 'insert')
            {
                if($listtable!='file')
                {
                    $success = $dt->insert($query);
                    $name = $query['name'];
                    if($listtable == 'top' && $success > 0)
                    {
                        DataTransaction::createGroupTables($name);
                    }
                }
            }
        }
        catch (Exception $e)
        {
            $success = 0;
        }


        $this->redirect(url('/otherlist', ['top'=>$top,'group'=>$group,'success'=>$success,'listtable'=>$listtable]));
    }

}