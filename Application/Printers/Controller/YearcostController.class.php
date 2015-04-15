<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/4/15
 * Time: 9:53
 */
namespace Printers\Controller;
use Think\Controller;
class YearcostController extends Controller{
    public function index(){
        $today = getdate();
        $param['year'] = $today['year'];

        $this->assign($param);
        $this->display();
    }
}