<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/4/8
 * Time: 10:17
 */
namespace Test\Controller;
use Think\Controller;
class TestShowPrintersController extends Controller{
    public function index(){
        $Printers = D('Printers');
        $Depts = $Printers->field('dept')->select();
        $depts = array();
        for( $i = 0 ; $i < count($Depts) ; $i ++ ){
            $depts[] = $Depts[$i]['dept'];
        }
        $depts = array_unique($depts);
        sort($depts);
        dump($depts);
        //dump($Depts);
        $this->display();
    }
}