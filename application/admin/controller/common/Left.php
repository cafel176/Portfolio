<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 18:44
 */

namespace app\admin\controller\common;

use app\common\Factory\DataFactory;

class Left
{
    private $groups = array();

    /**
     * Left constructor.
     */
    public function __construct($limits,$top)
    {
        $this->initData($limits,$top);
    }

    public function index()
    {
        $param = array('leftdata'=>$this->groups);

        return $param;
    }

    private function initData($limits,$top)
    {
        $dt = DataFactory::getDataTransaction($top.'_table');
        $where = $dt->whereArray($dt->getTable());
        $tables = $dt->select($where);

        foreach($tables as $v)
        {
            $dt = DataFactory::getDataTransaction($v['name']);
            $query = array();
            if($limits==null)
                $query = $dt->getTable();
            else
                $query['limit'] = $limits;

            $where = $dt->whereArray($query);
            $re = $dt->select($where);
            array_unshift($re,array('id'=>-1,'name'=>'Edit Groups'));
            $this->groups[$v['name']] = $re;
        }
    }
}