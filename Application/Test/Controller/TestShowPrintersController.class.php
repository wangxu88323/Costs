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
        rsort($depts);
        //dump($depts);
        //dump($Depts);
        for( $i = 0 ; $i < count($depts) ; $i ++ ){
            $depts[$i] = "'" . $depts[$i] . "'";
        }
        $yAxis = implode(',',$depts);
        //$yAxis = "'" . "打印机总数" . "'," . $yAxis;
        $param['yAxis'] = $yAxis;

        /*$data = array();
        for( $i = 0 ; $i < count($depts) ; $i ++ ){
            $data[] = rand(50,60);
        }
        $xAxis = implode(',',$data);
        $param['xAxis'] = $xAxis;*/
        //TODO
        $data = array();
        //$total = 0;
        for( $i = 0 ; $i < count($depts) ; $i ++ ){
            $count = $Printers->where("dept=" . $depts[$i])->count();
            //$total += $count;
            $data[] = $count;
        }
        $xAxis = implode(',',$data);
        //$xAxis = $total . ',' . $xAxis;
        $param['xAxis'] = $xAxis;


        $this->assign($param);
        $this->display();
    }

    public function getDeptPrinters(){
        $dept = I('post.dept');
        $data['dept'] = $dept;
        $Printers = D('Printers');
        $deptPrinters = $Printers->field('sn,model,supplies')->where("dept='" . $dept . "'")->select();
        /*for( $i = 0 ; $i < 10 ; $i ++ ){
            $data['printers'][] = rand(20,30);
        }*/
        $data['sql'] = $Printers->getLastSql();
        $data['printers'] = $deptPrinters;
        $this->ajaxReturn($data);
    }
}