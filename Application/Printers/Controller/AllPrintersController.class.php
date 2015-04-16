<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/4/16
 * Time: 10:06
 */
namespace Printers\Controller;
use Think\Controller;
class AllPrintersController extends Controller{
    public function index(){
        $Printers = M('Printers');
        $Depts = $Printers->field('dept')->select();
        $depts = array();
        for( $i = 0 ; $i < count($Depts) ; $i ++ ){
            $depts[] = $Depts[$i]['dept'];
        }
        $depts = array_unique($depts);
        rsort($depts);
        for( $i = 0 ; $i < count($depts) ; $i ++ ){
            $depts[$i] = "'" . $depts[$i] . "'";
        }
        $yAxis = implode(',',$depts);
        $param['yAxis'] = $yAxis;
        $data = array();
        for( $i = 0 ; $i < count($depts) ; $i ++ ){
            $count = $Printers->where("dept=" . $depts[$i])->count();
            $data[] = $count;
        }
        $xAxis = implode(',',$data);
        $param['xAxis'] = $xAxis;
        $this->assign($param);
        $this->display();
    }
    public function getDeptPrinters(){
        $dept = I('post.dept');
        $data['dept'] = $dept;
        $Printers = M('Printers');
        $deptPrinters = $Printers->field('sn,model,supplies')->where("dept='" . $dept . "'")->select();
        /*for( $i = 0 ; $i < 10 ; $i ++ ){
            $data['printers'][] = rand(20,30);
        }*/
        $data['sql'] = $Printers->getLastSql();
        $data['printers'] = $deptPrinters;
        $this->ajaxReturn($data);
    }
}