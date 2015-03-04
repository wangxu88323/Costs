<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2014/11/6
 * Time: 9:47
 */
namespace Test\Controller;
use Think\Controller;
class BlueController extends Controller{
    public function index(){
        $Ssq = M('Ssq');
        $blue = $Ssq->field('date, blue')->order('date desc')->select();

        $arr = array();
        for( $i = 0; $i < count($blue) ; $i ++ ){
            $arr[$blue[$i]['blue']]++;
        }
        //dump($arr);
        ksort($arr);
        //dump($arr);

        $div = array();
        for( $i = 1 ; $i <= count($arr) ; $i ++ ){
            $content = '';
            $content .= '<div style="width: 20px;height: 20px;background-color: skyblue;color: #ffffff;border-radius: 20px;text-align: center;margin-top: 5px;margin-left: 5px;">'.$i.'</div><div style="height: 100px;margin: 5px 0 5px;text-align: center;line-height: 100px;font-size: 80px;">'.$arr[$i].'</div>';
            $div[$i] = '<div><div style="width: 220px;height: 150px;margin: 0 10px 10px;border: 5px solid skyblue;border-radius: 20px;float: left;">'.$content.'</div></div>';
        }
        $output = '';
        for( $i = 1 ; $i <= count($div) ; $i ++ ){
            $output .= $div[$i];
        }

        $this->assign('div',$output);
        $this->display();
    }
}