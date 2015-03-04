<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 14-8-18
 * Time: 下午12:29
 */
namespace OA\Controller;
use Think\Controller;
class IndexController extends Controller{
    public function index(){
        $this->display();
    }

    public function show(){
        $this->display();
    }
}