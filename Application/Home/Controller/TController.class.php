<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2014/10/29
 * Time: 15:25
 */
namespace Home\Controller;
use Think\Controller;
class TController extends Controller{
    public function index(){
        $this->display();
    }

    public function show(){
        $Temperature = M('Temperature');
        $label = $Temperature->field('time')->order('id desc')->limit(30)->select();
        for( $i = 0 ; $i < count($label) ; $i ++){
            $label[$i]['time'] = date("Ymd",$label[$i]['time']);
        }
        //dump($label);
        foreach ($label as $v)
        {
            $v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
            $temp[] = $v;
        }
        $temp = array_reverse($temp);
        $labelOut = implode(',',$temp);
        //echo $labelOut;
        $data = $Temperature->field('temp')->order('id desc')->limit(30)->select();
        foreach ($data as $v)
        {
            $v = join(",",$v); //可以用implode将一维数组转换为用逗号连接的字符串
            $temp2[] = $v;
        }
        //dump($temp2);
        $temp2 = array_reverse($temp2);
        //dump($temp2);
        $dataOut = implode(',',$temp2);
        //echo $dataOut;

        $param['data']   = $dataOut;//"36.5,36.6,36.4,36.8,36.9,37.0,36.9,36.5,36.6,36.4,36.8,36.9,37.0,36.9";
        $param['label']  = $labelOut;//'"January","February","March","April","May","June","July","January","February","March","April","May","June","July"';

        $this->assign('line',$param);
        $this->display();
    }

    public function recordTemp(){
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
}