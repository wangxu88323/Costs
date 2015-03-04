<?php
namespace Home\Controller;
use Org\Util\URLlog;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function test2(){
        URLlog::log(__ACTION__);
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->savePath  =      '/Public/Uploads/'; // 设置附件上传目录
        // 上传文件
        $info = $upload->upload();
        if (!$info) { // 上传错误提示错误信息
            //$this->error($upload->getError());
        } else { // 上传成功
            foreach ($info as $file) {
                echo $file['savepath'] . $file['savename'] . "<br />";
                echo "<img src='/Uploads". $file['savepath'] . $file['savename']."' />";
            }
        }
    }

    public function test(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function testTem(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function testIndexTem(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function testProIndexTem(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function testProDescTem(){
        URLlog::log(__ACTION__);
        $this->display();
    }

    public function testPagination(){
        URLlog::log(__ACTION__);
        $test = M('Pagination');
        $pag = $test->db(2,"DB_CONFIG2")->find();
        dump($pag);
    }
    public function showIpLog(){
        $iplog = M('iplog');
        $iplogAll = $iplog->field('id',true)->order('logtime desc')->select();
        for( $i = 0 ; $i < count($iplogAll) ; $i ++  ){
            $iplogAll[$i]['logtime'] = date("Y-m-d H:i:s", $iplogAll[$i]['logtime']);
        }
        //dump($iplogAll);
        $cp = I('get.p',1);
        $Pag = new \Org\Util\MyPageV2($iplogAll, $cp, "/Home/Index/showIpLog", 15);
        $output = $Pag->getOutPut();
        if($output[0]){
            $param['content'] = $output[1];
            $param['ul'] = $output[2];
        } else {
            $this->error("页面未找到！","/Home/Index/showIpLog",3);
        }

        //$param['iplogAll'] = $iplogAll;
        $this->assign($param);
        $this->display();
    }
}