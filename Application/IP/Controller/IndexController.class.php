<?php
namespace IP\Controller;
use Think\Controller;
class IndexController extends Controller{
    public function index(){
        $ip = get_client_ip();
        $this->assign("ip", $ip);
        $this->display();
    }
}
