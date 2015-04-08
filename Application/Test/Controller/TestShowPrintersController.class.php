<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/4/8
 * Time: 10:17
 */
namespace Test\Controller;
use Think\Controller;
class TestShowPrintersController extends Controller{
    public function index(){
        $this->display();
    }
}