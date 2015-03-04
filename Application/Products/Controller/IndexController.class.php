<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2014-9-23
 * Time: 10:10
 */
namespace Products\Controller;
use \Think\Controller;
class IndexController extends Controller{
    public function index(){
        $cid = I('get.cd',1);

        $Pdmaininfo = M('Pdmaininfo');
        $Pdthumbnail = M('Pdthumbnail');
        $Pdcategory = M('Pdcategory');

        //输出导航栏
        $getPdcategory = $Pdcategory->field('id,pid,cname')->where('pid=0')->select();
        //dump($getPdcategory);
        $nav = '';
        for( $i = 0 ; $i < count($getPdcategory) ; $i++){
            $activeClass = $getPdcategory[$i]['id'] == $cid ? ' class="act"' : "";
            $href = $getPdcategory[$i]['id'] == $cid ? 'javascript:;' :  '/Products/Index/index.html?cd='.$getPdcategory[$i]['id'];
            $nav .= '<li'.$activeClass.'><a href="'.$href.'">'.$getPdcategory[$i]['cname'].'</a></li>';
        }
        $param['nav'] = $nav;
        //dump($nav);

        //输出产品
        $getPdmaininfo = $Pdmaininfo->field('hypy,yymc',true)->where('cid='.$cid)->select();
        for( $i = 0 ; $i < count($getPdmaininfo) ; $i ++){
            $pid = $getPdmaininfo[$i]['id'];
            $getPdmaininfo[$i]['mc'] = $getPdmaininfo[$i]['spm'] == '' ? $getPdmaininfo[$i]['tym'] : $getPdmaininfo[$i]['spm'];
            $pdinfo = $Pdthumbnail->field('about,url')->where('pid='.$pid)->select();
            $getPdmaininfo[$i]['about'] = strlen($pdinfo[0]['about']) > 20 ? mb_substr($pdinfo[0]['about'], 0, 30,'utf-8')."..." : $pdinfo[0]['about'];  //省略内容
            $getPdmaininfo[$i]['url'] = $pdinfo[0]['url'];
            $getPdmaininfo[$i]['href'] = '/Products/Index/product.html?pd='.$pid;
        }
        //dump($getPdmaininfo);

        $param['products'] = $getPdmaininfo;
        $this->assign($param);
        $this->display();
    }

    public function product(){
        $pid = I('get.pd');

        $Pdmaininfo = M('Pdmaininfo');
        $Pddesc = M('Pddesc');
        $Pdspec = M('Pdspec');
        $Pdpics = M('Pdpics');
        $Pdcategory = M('Pdcategory');


        $getPdmaininfo = $Pdmaininfo->field('hypy,yymc',true)->where('id='.$pid)->select();
        $pid = $getPdmaininfo[0]['id'];
        $param['spm'] = $getPdmaininfo[0]['spm'];
        $param['tym'] = $getPdmaininfo[0]['tym'];
        $param['mc'] = $param['spm'] == '' ? $param['tym'] : $param['spm'];
        //dump($pid);

        $getPdpics = $Pdpics->field('id,pid',true)->where('pid='.$pid)->select();
        $param['pics'] = $getPdpics[0]['url'];

        $getPddesc = $Pddesc->field("id,pid",true)->where('pid='.$pid)->select();
        $param['desc'] = $getPddesc;
        //dump($getPddesc);

        $getPdspec = $Pdspec->field('id,pid',true)->where('pid='.$pid)->select();
        $param['spec'] = $getPdspec;

        $param['cid'] = $Pdcategory->field('cname')->where("id=".$getPdmaininfo[0]['cid'])->find();
        $param['cidhref'] = '/Products/Index/index.html?cd='.$getPdmaininfo[0]['cid'];

        $this->assign($param);
        $this->display();
    }
}