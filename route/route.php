<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::rule('/', 'index/Index/index');


Route::rule('/admin', 'admin/LogIn/index');
Route::post('/admin/login', 'admin/LogIn/login');
Route::rule('/logout', 'admin/LogIn/logout');
Route::get('/admin/lang', 'admin/LogIn/lang');

Route::rule('/adminindex', 'admin/Index/index');
Route::get('/adminindex/lang', 'admin/Index/lang');

Route::rule('/adminlist', 'admin/MyList/index');
Route::post('/adminlist/change', 'admin/MyList/change');

Route::rule('/groupedit', 'admin/OperateGroup/index');
Route::post('/groupedit/change', 'admin/OperateGroup/change');

Route::rule('/otherlist', 'admin/OtherList/index');
Route::post('/otherlist/change', 'admin/OtherList/change');

Route::rule('/adminabout', 'admin/About/index');

Route::rule('/upload', 'admin/Upload/index');
Route::post('/upload/do', 'admin/Upload/upload_file');

return [

];
