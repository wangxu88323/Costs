<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 14-9-15
 * Time: 上午8:45
 */
namespace Printers\Controller;
use \Think\Controller;
class IndexController extends Controller{
    public function index(){
        $Printers = M('Printers');
        $allPrinters = $Printers->field('id',true)->order('id asc')->select();
        //dump($allPrinters);
        $param['allPrinters'] = $allPrinters;

        $this->assign($param);
        $this->display();
    }

    public function count(){
        $Printers = M('Printers');
        $allS = $Printers->field('id',true)->order('supplies asc')->select();
        //dump($allS);
        $allM = $Printers->field('id',true)->order('model asc')->select();
        //dump($allM);

        $total = array();
        $total['total'] = count($allS);  //打印机总数
        if ( $total['total'] > 0 ){
            $total['cate_num'] = 1;                                 //打印机种类数量
            $total['cate'] = $allS[0]['model'];                     //打印机种类清单
            $total['spl_num'] = 1;                                  //打印机耗材种类数量
            $total['spl'] = $allS[0]['supplies'];                   //打印机耗材种类清单
            $getSpl = $this->countSpl($total['total'], $total['spl_num'], $total['spl'], $allS);
            $total['spl_num'] = $getSpl['spl_num'];
            $total['spl'] = $getSpl['spl'];
            $total['all_spl'] = explode('/',$total['spl']);
            //echo count($total['all_spl']);
            $getCate = $this->countCate($total['total'], $total['cate_num'], $total['cate'], $allM);
            $total['cate_num'] = $getCate['cate_num'];
            $total['cate'] = $getCate['cate'];
            $total['all_cate'] = explode('/',$total['cate']);
            //echo count($total['all_cate']);
        } else {
            $total['cate_num'] = 0;         //打印机种类数量
            $total['cate'] = '';            //打印机种类清单
            $total['spl_num'] = 0;          //打印机耗材种类数量
            $total['spl'] = '';             //打印机耗材种类清单
        }
        //dump($total);
        $this->assign('p',$total);
        $this->display();
    }

    private function countCate($t, $cate_num, $cate, $allM){
        $total['total'] = $t;
        $total['cate_num'] = $cate_num;         //打印机种类数量
        $total['cate'] = $cate;                 //打印机种类清单

        for( $i = 0 ; $i < $total['total'] ; $i++ ){
            $last = ( $i - 1 ) < 0 ? 0 : ( $i - 1 );
            if( $allM[$i]['model'] != $allM[$last]['model'] ){
                $total['cate_num'] ++;
                $total['cate'] .= '/'.$allM[$i]['model'];
            }
        }
        return $total;
    }

    private function countSpl($t, $spl_num, $spl, $allS){
        $total['total'] = $t;
        $total['spl_num'] = $spl_num;           //打印机耗材种类数量
        $total['spl'] = $spl;                   //打印机耗材种类清单

        for( $i = 0 ; $i < $total['total'] ; $i++ ){
            $last = ( $i - 1 ) < 0 ? 0 : ( $i - 1 );
            if( $allS[$i]['supplies'] != $allS[$last]['supplies'] ){
                $total['spl_num'] ++;
                $total['spl'] .= '/'.$allS[$i]['supplies'];
            }
        }
        return $total;
    }
}