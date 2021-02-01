<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 7:21
 */

namespace app\common\DataBase;

use think\Db;
use think\Exception;

require_once(dirname(__FILE__) . '/../../common/DataBase/SQLDefine.php');

class SQLTransaction
{
    private $table;

    public function __construct($name)
    {
        $this->setTable($name);
    }

    /**
     *  指定表名，获取该表
     * @param $name
     */
    public function setTable($name)
    {
        $this->table = Db::table($name);
    }

    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    public static function getAllTableNames()
    {
        return self::query('SHOW TABLES');
    }

    public static function renameTable($old,$new)
    {
        return self::execute('alter table '.$old.' rename to '.$new);
    }

    public static function deleteTable($name)
    {
        return self::execute('DROP TABLE '.$name);
    }

    public static function hasTable($name)
    {
        return self::query('SHOW TABLES LIKE \''.$name.'\'')!=null;
    }

    public static function createGroupTables($name)
    {
        self::execute(initGroup($name));
        self::execute(initSubGroup($name));
        self::execute(initTable($name));
    }

    public function whereText($query)
    {
        $re = $this->table->where($query);
        if($re==null)
        {
            echo '找不到 '.$query;
        }

        return $re;
    }

    public function whereArray($arr)
    {

        $re = $this->table->where($arr);
        if($re==null)
        {
            echo '找不到 '.implode(',',$arr);
        }

        return $re;
    }

    public function select($where)
    {
        return $where->select();
    }

    public function order($where,$order)
    {
        return $where->order($order);
    }

    public function limit($where,$limit)
    {
        return $where->limit($limit);
    }

    public function insertGetNum($data)
    {
        try
        {
            return $this->table->insertAll($data);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public function insertGetId($data)
    {
        try
        {
            return $this->table->insertGetId($data);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public function insert($data)
    {
        try
        {
            return $this->table->insert($data);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public function update($where, $data)
    {
        try
        {
            return $where->update($data);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public function delete($where)
    {
        try
        {
            return $where->delete();
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public function value($where, $query)
    {
        return $where->value($query);
    }

    public function column($where, $query)
    {
        return $where->column($query);
    }

    public static function query($sql)
    {
        try
        {
            return Db::query($sql);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public static function execute($sql)
    {
        try
        {
            return Db::execute($sql);
        }
        catch (Exception $e)
        {
            self::dealException($e);
        }
    }

    public static function initDB($top,$limit,$admininfo,$file)
    {
        self::execute(initTop($top));
        self::execute(initLimitInfo($limit));
        self::execute(initAdminInfo($admininfo));
        self::execute(initFiles($file));
    }

    private static function dealException(Exception $e)
    {
        //throw $e;
    }
}