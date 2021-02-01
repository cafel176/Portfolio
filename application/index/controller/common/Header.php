<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 6:44
 */

namespace app\index\controller\common;

use think\Controller;

class Header extends Controller
{
    private $data;

    function _initialize()
    {
        $this->data = DataFactory::getDataTransaction('header');
    }

    public function index()
    {
        $data = array('left'=>$this->data->getItem(StructEnum::$left),
            'right'=>$this->data->getItem(StructEnum::$right),
            'logo'=>$this->data->getLogo(),
            'title'=>$this->data->getTitle());
        return $this->fetch('header',[
            'data'=>$data
        ]);
    }

    private function initData()
    {

    }
}