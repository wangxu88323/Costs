<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/2/3
 * Time: 10:01
 */
namespace Printers\Controller;
use Think\Controller;
class CostsController extends Controller{
    private $thisYear;
    private $thisMonth;
    private $lastYear;
    private $lastMonth;
    private $isLastMonthCostPay;
    private $toDate;

    function __construct(){
        parent::__construct();
        $today = getdate();
        $this->thisMonth = $today['mon'];
        $this->thisYear = $today['year'];
        $this->lastMonth = ( $this->thisMonth == 1 ) ? 12 : ($today['mon']-1);
        $this->lastYear = ( $this->thisMonth == 1 ) ? ($this->thisYear-1) : $this->thisYear;
        $isMonthCostPay = M('Ismonthcostpay');
        $isPay = $isMonthCostPay->field('id',true)->where('year='.$this->lastYear.' AND month='.$this->lastMonth)->getField('ispay');
        $this->isLastMonthCostPay = ( $isPay == 1 ) ? true : false;
        $Cost = M('Cost');
        $getToDate = $Cost->field('time')->order('time desc')->limit(1)->getField('time');
        $this->toDate = date('Y年m月d日', $getToDate);
    }

    public function index(){
        $param['thisMonth'] = $this->thisYear ."年" .$this->thisMonth . " 月";
        $param['lastMonth'] = $this->lastYear ."年" .$this->lastMonth . " 月";

        $deptCostsThisMonth = $this->getThisMonthCosts();
        $param['tbody_thisMonth'] = '';
        while($dc = each($deptCostsThisMonth)){
            $param['tbody_thisMonth'] .= "<tr><td class='width-50'>".$dc['key']."</td><td class='width-50'>￥".$dc['value']."</td></tr>";
        }

        $deptCostsLastMonth = $this->getLastMonthCosts();
        $param['tbody_lastMonth'] = '';
        if($deptCostsLastMonth){
            arsort($deptCostsLastMonth);
            while($dc = each($deptCostsLastMonth)){
                $param['tbody_lastMonth'] .= "<tr><td class='width-50'>".$dc['key']."</td><td class='width-50'>￥".$dc['value']."</td></tr>";
            }
        } else {

        }


        $param['isPay'] = ( $this->isLastMonthCostPay ) ? "" : " style='background-color: lightpink;'";
        $param['isPayLabel'] = ( $this->isLastMonthCostPay ) ? '<span class="label label-green">已结算</span>' : '<span class="label label-red">未结算</span>';
        $param['toDateLabel'] = '<span class="label label-blue">截至日期：'.$this->toDate.'</span>';
        $param['weekNum'] = (int)date('W');
        $param['date'] = date('Y年m月d日');
        $this->assign($param);
        $this->display();
    }

    private function getThisMonthCosts(){
        $today = getdate();
        $thisMonth = $today['mon'];
        $thisYear = $today['year'];
        $Costs = M('Cost');
        $ArrayCosts = $Costs->field('id,bid,sn,model',true)->where('year=' . $thisYear . ' AND month=' . $thisMonth)->select();
        $dept = array();
        for($i = 0 ; $i < count($ArrayCosts) ; $i ++){
            if($i == 0){
                array_push($dept, $ArrayCosts[$i]['dept']);
            } else {
                if( !in_array($ArrayCosts[$i]['dept'], $dept, false) ){
                    array_push($dept, $ArrayCosts[$i]['dept']);
                }
            }
        }
        $deptCost = array();
        for( $i = 0 ; $i < count($dept) ; $i ++ ){
            $deptCost[$dept[$i]] = 0;
        }
        for( $i = 0 ; $i < count($ArrayCosts) ; $i ++ ){
            $deptCost[$ArrayCosts[$i]['dept']] += $ArrayCosts[$i]['total'];
        }
        arsort($deptCost);
        return $deptCost;
    }

    private function getLastMonthCosts(){
        //判断上个月是否已经结算
        $isMonthCostPay = M('Ismonthcostpay');
        $isPay = $isMonthCostPay->field('id',true)->where('year='.$this->lastYear.' AND month='.$this->lastMonth)->getField('ispay');
        $isPay = ( $isPay == 1 ) ? true : false;
        if($isPay){
            $MonthCost = M('Monthcost');
            $ArrayMonthCost = $MonthCost->field('dept,cost')->where('year='.$this->lastYear.' AND month='.$this->lastMonth)->select();
            $deptCost = array();
            for( $i = 0 ; $i < count($ArrayMonthCost) ; $i ++ ){
                $deptCost[$ArrayMonthCost[$i]['dept']] = $ArrayMonthCost[$i]['cost'];
            }
            return $deptCost;
        } else {
            return false;
        }
    }

    public function bills(){
        $Cost = M('Cost');
        $bills = $Cost->field('id,price,total,year,month,isPay',true)->order('time desc')->select();
        for( $i = 0 ; $i < count($bills) ; $i ++ ){
            $bills[$i]['time'] = date('Y年m月d日',$bills[$i]['time']);
        }
        $cp = I('get.p',1);
        $Pag = new \Org\Util\MyPageV2($bills, $cp, "/Printers/Costs/bills",10);
        $output = $Pag->getOutPut();
        if($output[0]){
            $param['content'] = $output[1];
            $param['ul'] = $output[2];
        } else {
            $this->error("页面未找到！","/Printers/Costs/bills",3);
        }
        $this->assign($param);
        $this->display();
    }

    public function admin(){
        $ip = get_client_ip();
        if($ip == '127.0.0.1' || $ip == '10.107.211.123'){
            /*
             * 取得未设置time的清单
             * */
            $Cost = M('Cost');
            $ArrayCost = $Cost->field('id,date,time,year,month')->where('time=0')->select();
            $time0_count = count($ArrayCost);
            if($time0_count == 0){

            } else {
                $param['setTime'] = '<p></p><div class="unit-50 unit-centered"><div class="tools-alert tools-alert-red">共有 '.$time0_count.' 条未设置日期 <a href="/Test/TestCost/setTime" target="blank">设置？</a></div></div>';
            }

            /*
             * 取得未结算的月份
             * */
            $Ismonthcostpay = M('Ismonthcostpay');
            $ArrayNotPay = $Ismonthcostpay->field('month')->where('year=' . $this->thisYear . ' AND ispay=0 AND month!=' . $this->thisMonth)->select();
            //dump($ArrayNotPay);
            $NotPayNum = count($ArrayNotPay);
            if($NotPayNum == 0){

            } else {
                for($i = 0; $i < $NotPayNum ; $i ++){
                    $param['notPay'] .= '<p></p><div class="unit-50 unit-centered"><div class="tools-alert tools-alert-red"> '. $this->thisYear .' 年 '.$ArrayNotPay[$i]['month']
                        .' 月未结算 <a href="/Test/TestCost/pay?year='.$this->thisYear.'&month='.$ArrayNotPay[$i]['month'].'&token=18b0c1b9f0adfabe58f90a3680714807" target="blank">结算？</a></div></div>';
                }
            }

            $this->assign($param);
            $this->display();
        } else {
            $this->error('访问被拒绝！','/Printers/Costs',2);
        }
    }

    public function monthcost(){
        $param['weekNum'] = (int)date('W');
        $param['date'] = date('Y年m月d日');
        $Ismonthcostpay = M('Ismonthcostpay');
        $ArrayMonth = $Ismonthcostpay->field('month,ispay')->where('year=' . $this->thisYear . ' AND month!=' . $this->thisMonth)->select();
        $MonthPay = array();
        for( $i = 0 ; $i < count($ArrayMonth) ; $i ++){
            $MonthPay[$ArrayMonth[$i]['month']] = $ArrayMonth[$i]['ispay'];
        }
        //dump($ArrayMonth);
        //dump($MonthPay);
        $ArrayOutPut = array();
        for ($i = 1; $i < $this->thisMonth; $i++) {
            if ($MonthPay[$i] == 0) {
                $ArrayOutPut[$i] = "0";
            } else {
                $ArrayOutPut[$i] = "1";
            }
        }
        for( $i = $this->thisMonth ; $i < 13 ; $i ++ ){
            $ArrayOutPut[$i] = '2';
        }
        //dump($ArrayOutPut);
        for( $i = 1 ; $i < 13 ; $i ++ ){
            if($ArrayOutPut[$i] == '1'){
                $monthcost = $this->getMonthCost($i);
                $ArrayOutPut[$i] = '<div class="unit-25">
            <div style="height: 150px;box-sizing: border-box;">
                <div><a href="/Printers/Monthcost/details?year='.$this->thisYear.'&month='.$i.'""><label class="label label-green">'.$i.'月</label></a></div>
                <div class="cost">￥'.$monthcost.'</div>
            </div>
        </div>';
            } elseif($ArrayOutPut[$i] == 0){
                $ArrayOutPut[$i] = '<div class="unit-25">
            <div style="height: 150px;box-sizing: border-box;">
                <div><a href="javascript:;"><label class="label label-red">'.$i.'月</label></a></div>
                <div class="cost">未结算</div>
            </div>
        </div>';
            } else {
                $ArrayOutPut[$i] = '<div class="unit-25">
            <div style="height: 150px;box-sizing: border-box;">
                <div><a href="javascript:;"><label class="label label-gray">'.$i.'月</label></a></div>
                <div class="cost">未到</div>
            </div>
        </div>';
            }
        }
        $param['output'] = '';
        for( $i = 1 ; $i < 13 ; $i ++ ){
            $param['output'] .= $ArrayOutPut[$i];
            if($i == 1){
                $param['output'] = '<div class="units-row">' . $param['output'];
            } elseif($i == 12){
                $param['output'] = $param['output'] . '</div>';
            } elseif($i % 4 == 0){
                $param['output'] = $param['output'] . '</div><div class="units-row">';
            }
            /*if($i % 4 == 1){
                $param['output'] = '<div class="units-row">' . $param['output'];
            } elseif($i % 4 == 0){
                $param['output'] = $param['output'] . '</div>';
            }*/
        }
        //dump($ArrayOutPut);
        $this->assign($param);
        $this->display();
    }

    private function getMonthCost($month){
        $Monthcost = M('Monthcost');
        $AllCost = $Monthcost->field('cost')->where('year=' . $this->thisYear . ' AND month=' . $month)->select();
        $OutPut = 0;
        for( $i = 0 ; $i < count($AllCost) ; $i ++ ){
            $OutPut += $AllCost[$i]['cost'];
        }
        return $OutPut;
    }
}