<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 14-8-25
 * Time: 上午9:05
 */
namespace Emarks\Controller;
use Think\Controller;
class IndexController extends Controller{
    public function index(){
        $param['date'] = date("Y-m-d");

        $Emarks = M('Emarks');
        $arrEmarks = $Emarks->order('id desc')->select();
        $cp = I('get.p',1);
        $Pag = new \Org\Util\MyPage($arrEmarks, $cp, "/Emarks/Index/index", 3);
        $output = $Pag->getOutPut();
        //dump($output);
        if($output[0]){
            for($i = 0 ; $i < count($output[1]) ; $i ++ ){
                $output[1][$i]['addtime'] = date("Y年m月d日",$output[1][$i]['addtime']);
            }
            $param['content'] = $output[1];
            $param['ul'] = $output[2];
        } else {
            $this->error("页面未找到！","/Emarks/Index/index",3);
        }

        $this->assign($param);
        $this->display();
    }

    public function addMark(){
        $data['bookname'] = I('post.bookname');
        $data['pagenumber'] = I('post.pagenumber');
        $data['summary'] = I('post.summary','','htmlspecialchars');
        $data['addtime'] = time();

        $Emarks = M('Emarks');
        $result = $Emarks->data($data)->add();
        if($result){
            $mesg = $result;
            $this->ajaxReturn($mesg);
        } else {
            $mesg = 'error';
            $this->ajaxReturn($mesg);
        }
    }
}
