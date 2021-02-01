<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 14:19
 */

namespace app\common\DataBase;

use think\Db;

class DataTransaction
{
    private $name;
    private $sql;

    public function __construct($name)
    {
        $this->name = $name;
        $this->sql = new SQLTransaction($name);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->sql = new SQLTransaction($name);
    }

    public function whereText($query)
    {
        return $this->sql->whereText($query);
    }

    public function whereArray($arr)
    {
        return $this->sql->whereArray($arr);
    }

    public static function query($query)
    {
        return SQLTransaction::query($query);
    }

    public static function execute($query)
    {
        return SQLTransaction::execute($query);
    }

    public static function initDB($top,$limit,$admininfo,$file)
    {
        return SQLTransaction::initDB($top,$limit,$admininfo,$file);
    }

    public function delete($where)
    {
        return $this->sql->delete($where);
    }

    public function getTable()
    {
        return $this->sql->getTable();
    }

    public static function hasTable($name)
    {
        return SQLTransaction::hasTable($name);
    }

    public function getTableStruct()
    {
        return SQLTransaction::query('select COLUMN_NAME,IS_NULLABLE,COLUMN_DEFAULT from information_schema.COLUMNS where table_name = "'.$this->name.'"');
    }

    public static function renameTable($old,$new)
    {
        SQLTransaction::RenameTable($old,$new);
    }

    public static function deleteTable($name)
    {
        SQLTransaction::deleteTable($name);
    }

    public static function createGroupTables($name)
    {
        SQLTransaction::createGroupTables($name);
    }

    public static function getAllTableNames($contains = '')
    {
        $arr = SQLTransaction::getAllTableNames();
        $re = array();
        if($contains != '')
        {
            foreach($arr as $key=>$val)
            {
                $value = array_shift($val);
                if(strpos($value,$contains))
                {
                    array_push($re,$value);
                }
            }
        }
        else
            $re = $arr;

        return $re;
    }

    public function select($where)
    {
        return $this->sql->select($where);
    }

    public function update($where, $data)
    {
        return $this->sql->update($where, $data);
    }

    public function insertGetNum($data)
    {
        return $this->sql->insertGetNum($data);
    }

    public function insertGetId($data)
    {
        return $this->sql->insertGetId($data);
    }

    public function insert($data)
    {
        return $this->sql->insert($data);
    }

    public function value($where, $query)
    {
        return $this->sql->value($where,$query);
    }

    public function columnArray($where, $query)
    {
        $str = implode('，',$query);
        return $this->columnText($where,$str);
    }

    public function columnText($where, $query)
    {
        return $this->sql->column($where,$query);
    }
}