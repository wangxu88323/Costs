<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/2/6
 * Time: 9:37
 */
namespace Printers\Controller;
use Think\Controller;
class DetailsController extends Controller{
    public function index(){
        $suppliesCate = $this->getSuppliesCate();
        //dump($suppliesCate);
        $param['suppliesCateNumLabel'] = '<p>耗材种类为 ： <span class="label label-green">' . count($suppliesCate) . " 种</span></p>";
        $param['sCNtbody'] = '';
        for( $i = 0 ; $i < count($suppliesCate) ; $i ++ ){
            $param['sCNtbody'] .= '<tr><td>'. ($i+1) .'</td><td>'. $suppliesCate[$i] .'</td></tr>';
        }

        $printersCate = $this->getPrintersCate();
        //dump($printersCate);
        $param['printersCateNumLabel'] = '<p>打印机种类为 ： <span class="label label-green">' . count($printersCate) . " 种</span></p>";
        $param['pCNtbody'] = '';
        for( $i = 0 ; $i < count($printersCate) ; $i ++ ){
            $param['pCNtbody'] .= '<tr><td>'. ($i+1) .'</td><td>'. $printersCate[$i] .'</td></tr>';
        }

        $printers = $this->getPrinters();
        //dump($printers);
        $param['printersNumLabel'] = '<p>打印机总数为 ： <span class="label label-green">' . count($printers) . " 台</span></p>";
        $param['pNtbody'] = '';
        for( $i = 0 ; $i < count($printers) ;$i ++ ){
            $param['pNtbody']  .= '<tr><td>'. ($i+1) .'</td><td>'. $printers[$i]['sn'] .'</td><td>'. $printers[$i]['dept'] .'</td><td>'. $printers[$i]['model'] .'</td></tr>';
        }

        $this->assign($param);
        $this->display();
    }

    private function getSuppliesCate(){
        $Printers = M('Printers');
        $allSupplies = $Printers->field('supplies')->select();
        $stringAllSupplies = $allSupplies[0]['supplies'];
        for( $i = 1 ; $i < count($allSupplies) ; $i ++ ){
            $stringAllSupplies .= ( '/' . $allSupplies[$i]['supplies'] );
        }
        $arraySupplies = explode('/',$stringAllSupplies);
        $arraySupplies = array_values(array_unique($arraySupplies));
        //dump($arraySupplies);
        //echo $arraySupplies[48];
        return $arraySupplies;
    }

    private function getPrintersCate(){
        $Printers = M('Printers');
        $allModels = $Printers->field('model')->select();
        $stringAllModels = $allModels[0]['model'];
        for( $i = 1 ; $i < count($allModels) ; $i ++ ){
            $stringAllModels .= ( '/' . $allModels[$i]['model'] );
        }
        $arrayModels = explode('/',$stringAllModels);
        $arrayModels = array_values(array_unique($arrayModels));
        //dump($arraySupplies);
        //echo $arraySupplies[48];
        return $arrayModels;
    }

    private function getPrinters(){
        $Printers = M('Printers');
        $allPrinters = $Printers->field('sn,dept,model')->select();
        return $allPrinters;
    }
}