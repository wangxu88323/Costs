<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 14-8-18
 * Time: 下午12:29
 */
namespace Test\Controller;
use Think\Controller;
class IndexController extends Controller{
    public function index(){
        $pagenum = $_GET['page'] == "" ? 1 : $_GET['page'];
        $rollPage = 12;

        $Pagination = M('Pagination');
        $pag = $Pagination->select();
        $count = ceil(count($pag)/$rollPage);
        $first = $pagenum == 1 ? 'class="disabled"' : '';
        $last = $pagenum == $count ? 'class="disabled"' : '';
        //echo count($pag) . "<br />";
        $content = array_slice($pag,($pagenum-1)*$rollPage,$rollPage);

        $param['content'] = $content;
        $param['pagenum'] = $pagenum;
        $param['count'] = $count;
        $param['first'] = $first;
        $param['last'] = $last;
        $param['url'] = U(Index/index);
        $param['prevUrl'] = ( $pagenum-1 ) > 0 ? U(Index/index) . "?page=" . ($pagenum-1) : "javascript:;";
        $param['nextUrl'] = ( $pagenum ) < $count ? U(Index/index) . "?page=" . ($pagenum+1) : "javascript:;";

        $this->assign($param);
        $this->display();
        //dump($pag);
    }

    public function jQlab(){
        $this->display();
    }

    public function testMyPage(){
        $Pag = M('Pagination');
        $content = $Pag->select();
        $cp = I('get.p',1);
        //dump($content);
        $MyPage = new \Org\Util\MyPage($content, $cp, "/Test/Index/testMyPage");
        $output = $MyPage->getOutPut();
        //dump($output);
        if($output[0]){
            $param['content'] = $output[1];
            $param['ul'] = $output[2];
            $this->assign($param);
            $this->display();
        } else {
            $this->error("页面未找到！","/Test/Index/testMyPage",3);
        }
        //dump($output[0]);
    }

    public function testTabs(){
        $this->display();
    }

    public function testMyPageV2(){
        $Pag = M('Iplog');
        $content = $Pag->select();
        $cp = I('get.p',1);
        //dump($content);
        $MyPage = new \Org\Util\MyPageV2($content, $cp, "/Test/Index/testMyPageV2");
        $output = $MyPage->getOutPut();
        dump($output);
        if($output[0]){
            $param['content'] = $output[1];
            $param['ul'] = $output[2];
            $this->assign($param);
            $this->display();
        } else {
            $this->error("页面未找到！","/Test/Index/testMyPageV2",3);
        }
    }

    public function testChartLine(){
        $Temperature = M('Temperature');
        $label = $Temperature->field('time')->order('id')->select();
        for( $i = 0 ; $i < count($label) ; $i ++){
            $label[$i]['time'] = date("Ymd",$label[$i]['time']);
        }
        //dump($label);
        foreach ($label as $v)
        {
            $v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
            $temp[] = $v;
        }
        $labelOut = implode(',',$temp);
        //echo $labelOut;
        $data = $Temperature->field('temp')->order('id')->select();
        foreach ($data as $v)
        {
            $v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
            $temp2[] = $v;
        }
        //dump($data);
        $dataOut = implode(',',$temp2);
        //echo $dataOut;

        $param['data']   = $dataOut;//"36.5,36.6,36.4,36.8,36.9,37.0,36.9,36.5,36.6,36.4,36.8,36.9,37.0,36.9";
        $param['label']  = $labelOut;//'"January","February","March","April","May","June","July","January","February","March","April","May","June","July"';

        $this->assign('line',$param);
        $this->display();
    }

    public function testTemp(){
        $this->display();
    }

    public function testRecordTemp(){
        $data['temp'] = I('post.temp');
        $data['time'] = time();

        $Temperature = M('Temperature');
        $result = $Temperature->data($data)->add();
        if($result){
            $mesg = $result;
            $this->ajaxReturn($mesg);
        } else {
            $mesg = 'error';
            $this->ajaxReturn($mesg);
        }
    }

    public function testBlue(){
        $Ssq = M('Ssq');
        $blue = $Ssq->field('date, blue')->order('date desc')->select();
        //echo count($blue);
        $tbody = array();

        for( $i = 0 ; $i < count($blue) ; $i ++ ){
            for( $j = 1 ; $j <= 16 ; $j ++ ){
                if($blue[$i]['blue'] == $j){
                    //$tbody[$i] .= ( '<td style="background-color: lightblue;text-align: center;">' . $blue[$i]['blue'] . '</td>' );
                    $tbody[$i] .= ( '<div style="background-color: blue;width: 50px;height: 30px;float: left;"></div>' );
                } else {
                    //$tbody[$i] .= '<td></td>';
                    $tbody[$i] .= '<div style="background-color: #CCCCCC;width: 50px;height: 30px;float: left;"></div>';
                }
            }
            //$tbody[$i] = '<tr>' . $tbody[$i] . '</tr>';
            $tbody[$i] = '<div style="width: 800px;clear: both;">' . $tbody[$i] . '</div>';
        }
        //dump($tbody);
        $table = '';
        for( $i = 0 ; $i < count($tbody) ; $i ++){
            $table .= $tbody[$i];
        }
        //$table = '<div class="container-fluid"><table class="table">' . $table . '</table></div>';
        //echo $Ssq->getLastSql();
        $arr = array();
        for( $i = 0; $i < count($blue) ; $i ++ ){
            $arr[$blue[$i]['blue']]++;
        }
        for( $i = 1 ; $i <= count($arr) ; $i ++ ){
            $arr[$i] = ( $arr[$i] / 1721 * 100 ) . "%";
            //echo $i . "-->" . $arr[$i] . "<br />";
        }
        arsort($arr);
        //dump($arr);
        //dump($blue);
        $total = 0;
        for( $i = 1 ; $i <= count($arr) ; $i ++ ){
            $total += $arr[$i];
        }
        //echo $total;
        $this->assign('table',$table);
        $this->display();
    }

    public function testPing(){
        //header('Content-type: text/html; charset=gbk');
        ob_end_clean();
        ini_set("max_execution_time", 0);
        $baseIP = "10.107.211.";
        echo "<table><tbody>";
        for ($i = 6, $j = 1; $i <= 253; $i++, $j++) {
            $ip = $baseIP . $i;
            $exec = exec("ping -w 1 -n 2 -l 64 " . $ip);
            /* $ifOn = str_split(end(explode(" ", $exec )), 1);
             $isON = ( $ifOn[count($ifOn)-1] == 's' ) ? "green" : "red";
             $isWeight = ( $isON == 'green') ? "bolder" : 'normal';
             echo "<td><span class='badge badge-".$isON."' style='font-weight: ".$isWeight."'>".$i."</span></td>";
             if ($j % 10 == 0){
                 echo "</tr><tr>";
             }*/

            $ifOn = str_split(end(explode(" ", $exec)), 1);
            $isON = ($ifOn[count($ifOn) - 1] == 's') ? "已占用" : "未占用";
            echo "<tr><td>" . $ip . "</td><td>" . $isON . "</td></tr>";
            flush();
        }
        echo "</tbody></table>";
        $this->display();
    }
}