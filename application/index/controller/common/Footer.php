<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/22
 * Time: 6:44
 */

namespace app\index\controller\common;

use think\Controller;

class Footer extends Controller
{
    private $data;

    function _initialize()
    {
        $this->data = DataFactory::getStructData(FactoryEnum::$footer);
    }

    public function index()
    {
        $data = array('left'=>$this->data->getItem(StructEnum::$left),
            'right'=>$this->data->getItem(StructEnum::$right),
            'text'=>$this->data->getText());
        return $this->fetch('footer',[
            'data'=>$data
        ]);
    }

    private function initData()
    {

    }
}