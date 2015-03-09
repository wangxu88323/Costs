<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/1/29
 * Time: 8:53
 */
namespace Test\Controller;
use Think\Controller;
use Think\Model;

class TestCostController extends Controller{
    function __construct() {
        $IsMonthCostPay = D('Ismonthcostpay');
        $today = getdate();
        $thisMonth = $today['mon'];
        $thisYear = $today['year'];
        $isPayExist = $IsMonthCostPay->field('id',true)->where('month=' . $thisMonth . " AND year=" . $thisYear)->getField('ispay');
        //echo "isPayExist = ".$isPayExist;
        if( $isPayExist == ''){
            $data['month'] = $thisMonth;
            $data['year'] = $thisYear;
            $data['ispay'] = 0;
            $IsMonthCostPay->add($data);
        }
    }

    public function index(){
        $cost = D('Cost');
        $today = getdate();
        $thisMonth = $today['mon'];
        $thisYear = $today['year'];
        $ArrayCost = $cost->field("id,sn,model",true)->where('year=' . $thisYear . ' AND month=' . $thisMonth)->select();
        $dept = array();
        //dump($ArrayCost);
        for($i = 0 ; $i < count($ArrayCost) ; $i ++){
            if($i == 0){
                array_push($dept, $ArrayCost[$i]['dept']);
            } else {
                if( !in_array($ArrayCost[$i]['dept'], $dept, false) ){
                    array_push($dept, $ArrayCost[$i]['dept']);
                }
            }
        }
        //初始化deptCost数组 部门=>费用
        $deptCost = array();
        for( $i = 0 ; $i < count($dept) ; $i ++ ){
            $deptCost[$dept[$i]] = 0;
        }
        //dump($deptCost);
        for( $i = 0 ; $i < count($ArrayCost) ; $i ++ ){
            $deptCost[$ArrayCost[$i]['dept']] += $ArrayCost[$i]['total'];
        }
        //dump($deptCost);
        ksort($deptCost);
        //dump($deptCost);
        //echo count($deptCost);
        echo "<h1>" . $thisYear . " 年 " . $thisMonth . " 月</h1>";
        echo "<table>";
        while( $dc = each($deptCost) ) {
            echo "<tr><td>".$dc['key']."</td><td>".$dc['value']."</td></tr>";
        }
        //echo "<tr><td>".$dc[$i]['key']."</td><td>".$dc[$i]['value']."</td><tr/>";
        echo "</table>";
    }

    public function setTime(){
        $Cost = D('Cost');
        $ArrayCost = $Cost->field('id,date,time,year,month')->where('time=0')->select();
        /*for( $i = 0 ; $i < count($ArrayCost) ; $i ++ ){
            //echo date("Y/m/d", $ArrayCost[$i]['time']) . "<br />";
            echo $ArrayCost[$i]['year'] . "<br />";
        }*/
        //dump($ArrayCost);
        for( $i = 0 ; $i < count($ArrayCost) ; $i ++ ){
            $isTime = $ArrayCost[$i]['time'];
            $str_array = explode('/',$ArrayCost[$i]['date']);
            //dump($str_array);
            $time = mktime(0, 0, 0, $str_array[1], $str_array[2], $str_array[0]);
            if( $isTime == 0 ){
                $Cost->where('id='.$ArrayCost[$i]['id'])->setField('time',$time);
                echo "ID: " . $ArrayCost[$i]['id'] . " Set Time Done<br />";
                //echo $Cost->getLastSql() . "<br />";
            }
            if( $ArrayCost[$i]['year'] == 0){
                $Cost->where('id='.$ArrayCost[$i]['id'])->setField('year',$str_array[0]);
                $Cost->where('id='.$ArrayCost[$i]['id'])->setField('month',$str_array[1]);
                echo "ID: " . $ArrayCost[$i]['id'] . " Set Year&Month Done<br />";
            }
        }
    }

    public function pay(){
        $token = I('get.token');
        $month = I('get.month');
        $year = I('get.year');
        $today = getdate();
        $thisMonth = $today['mon'];
        $thisYear = $today['year'];
        $monthError = ( $month < $thisMonth && $year <= $thisYear ) ? true : false;
        $IsMonthCostPay = D('Ismonthcostpay');
        $isPay = $IsMonthCostPay->field('id',true)->where('month=' . $month . " AND year=" . $year)->getField('ispay');
        $isNotPay = ( $isPay == 0 ) ? true : false;
        $isToken = ($token == '18b0c1b9f0adfabe58f90a3680714807') ? true : false;
        if( $isToken && $isNotPay && $monthError ){
            ob_end_clean();
            echo "正在结算 <b>". $month ."</b> 月费用...<br />";
            flush();
            $Cost = D('Cost');
            $ArrayCost = $Cost->field('dept,total')->where('month=' . $month . " AND year=" . $year)->select();
            $dept = array();
            //dump($ArrayCost);
            for($i = 0 ; $i < count($ArrayCost) ; $i ++){
                if($i == 0){
                    array_push($dept, $ArrayCost[$i]['dept']);
                } else {
                    if( !in_array($ArrayCost[$i]['dept'], $dept, false) ){
                        array_push($dept, $ArrayCost[$i]['dept']);
                    }
                }
            }
            //初始化deptCost数组 部门=>费用
            $deptCost = array();
            for( $i = 0 ; $i < count($dept) ; $i ++ ){
                $deptCost[$dept[$i]] = 0;
            }
            //dump($deptCost);
            for( $i = 0 ; $i < count($ArrayCost) ; $i ++ ){
                $deptCost[$ArrayCost[$i]['dept']] += $ArrayCost[$i]['total'];
            }
            //dump($deptCost);
            ksort($deptCost);
            //dump($deptCost);
            //echo count($deptCost);
            $MonthCost = D('Monthcost');
            while( $dc = each($deptCost) ) {
                //echo "<tr><td>".$dc['key']."</td><td>".$dc['value']."</td></tr>";
                $data['dept'] = $dc['key'];
                $data['cost'] = $dc['value'];
                $data['month'] = $month;
                $data['year'] = $year;
                if($MonthCost->add($data)){
                    echo $data['dept'] . " 已结算成功！<br />";
                    flush();
                }
            }
            $data['ispay'] = 1;
            $IsMonthCostPay->where('month=' . $month . " AND year=" . $year)->setField('ispay', $data['ispay']);
        } else {
            echo "<b style='color: red;'>无法结算！</b><br />";
            if( !$monthError ){
                echo "<b>还未到 ".$year." 年 ".$month ." 月结算日期</b>";
            } elseif( !$isNotPay ){
                echo "<b>" . $year." 年 " .$month. "<b/> 月已结算！";
            } else {
                echo "TOKEN错误！";
            }
        }
    }

    public function showSupplies(){
        $Printers = D('Printers');
        $ModelSupply = $Printers->field('model,supplies')->select();
        //dump($ModelSupply);
        $ArrayModelSupply = array();
        for( $i = 0 ; $i < count($ModelSupply) ; $i ++ ){
            $ArrayModelSupply[$ModelSupply[$i]['supplies']] = $ModelSupply[$i]['model'];
            //$ArrayModelSupply[$i] = $i;
        }
        //dump($ArrayModelSupply);
        $ArrayOutPut = array();
        while($AMS = each($ArrayModelSupply)){
            $temp = explode('/',$AMS['key']);
            //dump($temp);
            for( $i = 0 ; $i < count($temp) ; $i ++ ){
                $ArrayOutPut[$temp[$i]] = $AMS['value'];
            }
        }
        echo '<table>';
        //dump($ArrayOutPut);
        while($AOP = each($ArrayOutPut)){
            echo '<tr><td>' . $AOP['key'] . '</td><td>' . $AOP['value'] . '</td></tr>';
        }
        echo '</table>';
    }
}