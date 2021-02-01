<?php
/**
 * Created by PhpStorm.
 * User: LENOVO
 * Date: 2021/1/30
 * Time: 21:56
 */

namespace app\admin\controller;

use app\common\Factory\DataFactory;
use think\Exception;
use think\Image;
use think\facade\Env;
use app\admin\controller\common\Basic;

class Upload extends Basic
{
    protected function getTemplateName()
    {
        return 'upload';
    }

    protected function getTitle()
    {
        return lang('system_title_upload');
    }

    //定义一个方法名upload_img，和view/TestImage文件夹下面的upload_img同名，提交信息时匹配文件
    public function upload_file()
    {
        //判断是否是post 方法提交的
        if(request()->isPost())
        {
            //
            $path = Env::get('ROOT_PATH').'public/static/uploads';
            $waterimg = Env::get('ROOT_PATH').'public/static/images/waterimg.png';

            $query = array();
            $data=input('post.');

            $query['name']=$data['name'];
            $query['desc']=$data['desc'];
            if(array_key_exists('limit',$data))
                $query['limit']=$data['limit'];
            if(array_key_exists('canedit',$data))
                $query['canedit']=1;
            else
                $query['canedit']=0;

            //处理图片上传
            //提交时在浏览器存储的临时文件名称
            if($_FILES['file']['tmp_name'])
            {
                // 获取表单上传的文件，例如上传了一张图片
                $file = request()->file('file');

                $filename = $this->upload($file,$path);

                try
                {
                    if(array_key_exists('water',$data))
                    {
                        $image = Image::open($path.'/'.$filename);
                        if(is_string($image))
                        {
                            throw new Exception($image);
                        }
                        //加水印
                        $this->waterTileImg($image,$waterimg,100,100);
                        $image->save($path.'/'.$filename);
                    }
                }
                catch (Exception $e)
                {
                    //echo $e->getMessage();
                }

                $query['fileName'] = $filename;
                $query['who'] = $this->getSession('admin_username');

                $dt = DataFactory::getDataTransaction($this->getSession('file').'_table');
                $add = $dt->insert($query);
                $success = lang('upload_hint_dbfail');
                if($add)
                {
                    $success = lang('upload_hint_dbsuccess');
                }
                $this->success(lang('upload_hint_success').$success,url('/upload'));
            }

            $this->error(lang('upload_hint_failed'),url('/upload'));
        }
        return view();
    }

    //上传文件函数
    private function upload($file,$path)
    {
        if($file)
        {
            $info = $file->move($path);
            if($info)
            {
                return $info->getSaveName();
            }
            else
            {
                // 上传失败获取错误信息
                return $file->getError();
            }
        }
    }

    private function waterTileImg($image,$logo,$xoffset,$yoffset,$transparent=10)
    {
        try{
            $image->tilewater($logo,$xoffset,$yoffset,$transparent);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    private function waterImg($image,$logo,$transparent=10)
    {
        try{
            $image->water($logo,Image::WATER_CENTER,$transparent);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }

    private function waterText($image,$text,$size=50,$color='#000000',$font='simhei.ttf')
    {
        try{
            $path = Env::get('ROOT_PATH').'/public/static/font/'.$font;
            $image->text($text,$path,$size,$color,Image::WATER_SOUTHEAST);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }
}