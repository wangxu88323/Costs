<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/2/4
 * Time: 15:52
 */
namespace Test\Controller;
use Think\Controller;
class TestEChartsController extends Controller{
    public function index(){
        $Monthcost = D('Monthcost');
        $ArrayMonthCost = $Monthcost->field('id',true)->select();
        $ArrayCost = array();
        for( $i = 0 ; $i < count($ArrayMonthCost) ; $i ++ ){
            $ArrayCost[$ArrayMonthCost[$i]['dept']] = $ArrayMonthCost[$i]['cost'];
        }
        arsort($ArrayCost);
        //dump($ArrayCost);
        $DataOut = array();
        $LegendOut = array();
        while ($ac = each($ArrayCost)){
            array_push($DataOut, "{value:".$ac['value'].", name:'".$ac['key']."'}");
            $ac['key'] = "'" . $ac['key'] . "'";
            array_push($LegendOut, $ac['key']);
        }
        $data = implode(',',$DataOut);
        $legend = implode(',',$LegendOut);
        //dump($data);
        //dump($legend);
        $param['data'] = $data;
        $param['legend'] = $legend;

        $this->assign($param);
        $this->display();
    }
}