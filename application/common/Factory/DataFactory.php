<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/24
 * Time: 0:02
 */

namespace app\common\Factory;

use app\common\DataBase\DataTransaction;

abstract class DataFactory
{
    public static function getDataTransaction($value)
    {
        return new DataTransaction($value);
    }

}