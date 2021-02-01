<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 18:44
 */

namespace app\admin\controller\common;


class Header
{
    private $groups = array();

    /**
     * Left constructor.
     */
    public function __construct()
    {
        $this->initData();
    }

    public function index()
    {
        $param = array('headerdata'=>$this->groups);

        return $param;
    }

    private function initData()
    {

    }
}