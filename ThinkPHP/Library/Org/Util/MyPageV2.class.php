<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2014/10/23
 * Time: 8:58
 * MyPage类 版本V2.0
 */

namespace Org\Util;

class MyPageV2
{
    public $totalPages;     //总页数
    public $currentPage;    //当前页
    public $lastPage;       //前一页的URL
    public $nextPage;       //下一页的URL
    public $firstClass;     //第一页的时候，前一页的CSS CLASS
    public $lastClass;      //最后一页的时候，下一页的CSS CLASS
    public $url;            //URL
    public $content;        //内容
    public $isFirst;        //是否是第一页
    public $isHead;         //是否输出head‘...’
    public $isEnd;          //是否输出end‘...’
    public $is404;          //是否存在当前页

    public function __construct($array, $currentPage, $url, $everyPage = 12)
    {
        $this->totalPages = ceil( count($array) / $everyPage );
        $this->currentPage = $currentPage;
        $this->url = $url . '?p=';
        $this->lastPage = ( $this->currentPage - 1 ) > 0 ? $this->url . ( $this->currentPage - 1 ) : "javascript:;";
        $this->nextPage = ( $this->currentPage + 1 ) <= $this->totalPages ?  $this->url  . ( $this->currentPage + 1 ) : "javascript:;";
        $this->isFirst = $this->currentPage == 1 ? true : false ;
        $this->firstClass = ( $this->isFirst == true ) ? " class='disabled'" : "";
        $this->lastClass = ( $this->currentPage == $this->totalPages ?  " class='disabled'" : "" );
        $this->content = array_slice( $array, ($this->currentPage-1)*$everyPage, $everyPage );
        $this->isHead = ( $this->currentPage - 4 ) > 0 ? true : false;
        $this->isEnd = ( $this->totalPages - $this->currentPage ) >= 4 ? true : false;
        if( $this->currentPage <= 0 || $this->currentPage > $this->totalPages ){ $this->is404 = true;} else { $this->is404 = false;}
    }

    public function getHead(){
        if( $this->isHead ){
            return '<li'.$this->firstClass.'><a href="'.$this->lastPage.'">&laquo;</a></li>'.'<li><a href="'. $this->url.'1">1</a></li>'.'<li><a class="slh" href="javascript:;">...</a></li>';
        } else {
            if( $this->isFirst ){
                return '<li class="disabled"><a href="javascript:;">&laquo;</a></li>';
            } else {
                if( ( $this->currentPage - 4 ) == 0){
                    return '<li><a href="'.$this->lastPage.'">&laquo;</a></li>'.'<li><a href="'. $this->url.'1">1</a></li>';
                } else {
                    return '<li><a href="'.$this->lastPage.'">&laquo;</a></li>';
                }
            }
        }
    }

    public function getBody(){
        $li1 = ( $this->currentPage - 2 ) > 0 ? '<li><a href="'. $this->url. ( $this->currentPage - 2 ) .'">'.( $this->currentPage - 2 ) .'</a></li>' : '';
        $li2 = ( $this->currentPage - 1 ) > 0 ? '<li><a href="'. $this->url. ( $this->currentPage - 1 ) .'">'.( $this->currentPage - 1 ) .'</a></li>' : '';
        $li3 = '<li class="active"><a href="'. $this->url. ( $this->currentPage ) .'">'.( $this->currentPage ) .'</a></li>';
        $li4 = ( $this->currentPage + 1 ) <= $this->totalPages ? '<li><a href="'. $this->url. ( $this->currentPage + 1 ) .'">'.( $this->currentPage + 1 ) .'</a></li>' : '';
        $li5 = ( $this->currentPage + 2 ) <= $this->totalPages ? '<li><a href="'. $this->url. ( $this->currentPage + 2 ) .'">'.( $this->currentPage + 2 ) .'</a></li>' : '';

        return $li1 . $li2 .$li3 .$li4 . $li5;
    }

    public function getEnd(){
        if( $this->isEnd ){
            return '<li><a class="slh" href="javascript:;">...</a></li>'.'<li><a href="'. $this->url . $this->totalPages .'">'.$this->totalPages.'</a></li>'.'<li'.$this->lastClass.'><a href="'.$this->nextPage.'">&raquo;</a></li>';
        } else {
            if(( $this->totalPages - $this->currentPage ) == 3){
                return '<li><a href="'. $this->url . $this->totalPages .'">'.$this->totalPages.'</a></li>'.'<li'.$this->lastClass.'><a href="'.$this->nextPage.'">&raquo;</a></li>';
            } else {
                return '<li'.$this->lastClass.'><a href="'.$this->nextPage.'">&raquo;</a></li>';
            }
        }
    }

    public function getPagination(){
        $head = $this->getHead();
        $body = $this->getBody();
        $end = $this->getEnd();

        return "<ul class='pagination'>" .$head . $body . $end ."</ul>";
    }

    public function getContents(){
        return $this->content;
    }

    public function getOutPut(){
        if ($this->is404) {
            return $array[0] = false;
        } else {
            $array[0] = true;
            $array[1] = $this->getContents();
            $array[2] = $this->getPagination();
        }
        return $array;
    }
}