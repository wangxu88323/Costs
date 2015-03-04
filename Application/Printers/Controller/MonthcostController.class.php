<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/2/15
 * Time: 8:38
 */
namespace Printers\Controller;
use Think\Controller;
class MonthcostController extends Controller{
    public function index(){
        redirect('/Printers/Costs');
    }
    public function details(){
        $month = I('get.month');
        $year = I('get.year');
        $Ismonthcostpay = M('Ismonthcostpay');
        $result = $Ismonthcostpay->field('ispay')->where('year=' . $year . ' AND month=' . $month)->getField('ispay');
        //dump($result);
        if($result != 1){
            $this->error("参数错误","/Printers/Costs/monthcost",3);
        } else {
            $param['title'] = $year . " 年 " . $month . ' 月耗材费用';
            $param['subtext'] = "'" . $param['title'] . "'";
            $Monthcost = M('Monthcost');
            $ArrayMonthCost = $Monthcost->field('id,month,year',true)->where('year=' . $year . ' AND month=' . $month)->order('cost desc')->select();
            //dump($ArrayMonthCost);
            $MostCostDept = $ArrayMonthCost[0]['dept'];
            //echo $MostCostDept;
            $ArrayCost = array();
            for( $i = 0 ; $i < count($ArrayMonthCost) ; $i ++ ){
                $ArrayCost[$ArrayMonthCost[$i]['dept']] = $ArrayMonthCost[$i]['cost'];
            }
            //arsort($ArrayCost);
            //dump($ArrayCost);
            $param['tbody_thisMonth'] = '';
            $DataOut = array();
            $LegendOut = array();
            $total = 0;
            while($dc = each($ArrayCost)){
                $total += $dc['value'];
                $param['tbody_thisMonth'] .= "<tr><td class='width-50'>".$dc['key']."</td><td class='width-50'>￥".$dc['value']."</td></tr>";
                array_push($DataOut, "{value:".$dc['value'].", name:'".$dc['key']."'}");
                $dc['key'] = "'" . $dc['key'] . "'";
                array_push($LegendOut, $dc['key']);
            }
            $param['tbody_thisMonth'] .= "<tr><td class='width-50' style='color: red;font-weight: bolder;font-size: 2em;'>总费用</td><td class='width-50' style='color: red;font-weight: bolder;font-size: 2em;'>￥".$total."</td></tr>";
            $data = implode(',',$DataOut);
            $legend = implode(',',$LegendOut);

            //dump($data);
            //dump($legend);
            $param['data'] = $data;
            $param['legend'] = $legend;
        }

        $this->assign($param);
        $this->display();
    }
}