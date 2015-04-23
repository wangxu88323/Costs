<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/4/23
 * Time: 8:27
 */
namespace IP\Controller;
use Think\Controller;

class RegController extends Controller{
    public function index(){
        $Dept = M('Dept');
        $dept = $Dept->field('dept')->where('location!="1"')->select();
        //echo $Dept->getLastSql();
        //dump($dept);
        $options = '';
        for( $i = 0 ; $i < count($dept) ; $i ++ ){
            $options .= "<option value='".$dept[$i]['dept']."'>".$dept[$i]['dept']."</option>";
        }
        //dump($options);
        $param['options'] = $options;
        $this->assign($param);
        $this->display();
    }

    public function reg(){
        $ip = get_client_ip();
        $dept = I('post.dept');
        $name = I('post.name');

        $IP = M('Ip');
        //$ifIPExisted = $IP->where('ip="'.$ip.'"')->getField('id');
        $ifNameExisted = $IP->where('name="'.$name.'"')->getField('id');
        if(false){
            $msg['flag'] = 0;
            $msg['msg'] = "此IP已登记";
        } elseif($ifNameExisted){
            $msg['flag'] = 0;
            $msg['msg'] = "您已登记过IP";
        } else {
            $data['ip'] = $ip;
            $data['name'] = $name;
            $data['dept'] = $dept;
            $data['time'] = mktime();
            $result = $IP->add($data);
            if($result){
                $msg['flag'] = 1;
                $msg['msg'] = '登记成功！多谢！';
            } else {
                $msg['flag'] = 0;
                $msg['msg'] = "登记失败,请稍候重试";
            }
        }



        $this->ajaxReturn($msg);
    }
}