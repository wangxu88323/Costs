<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 14-8-26
 * Time: 上午8:57
 */
namespace Org\Util;

class MyPage
{
    public $is404;
    public $currentPage;
    public $isFirst;
    public $isLast;
    public $lastPage;
    public $nextPage;
    public $totalPages;
    public $content;
    public $url;
    public $fclass;
    public $lclass;

    public function __construct($t, $cp, $u, $r = 12)
    {
        $this->content = array_slice($t, ($cp - 1) * $r, $r);
        $this->currentPage = $cp;
        $this->url = $u . "?p=";
        $this->totalPages = ceil(count($t) / $r);
        $this->isFirst = $cp == 1 ? true : false;
        $this->isLast = $cp == $this->totalPages ? true : false;
        $this->lastPage = ($cp - 1) > 0 ? $this->url . ($cp - 1) : "javascript:;";
        $this->nextPage = ($cp + 1) <= $this->totalPages ? $this->url . ($cp + 1) : "javascript:;";
        $this->fclass = ($this->isFirst == true) ? " class='disabled'" : "";
        $this->lclass = ($this->isLast == true) ? " class='disabled'" : "";
        if ($this->currentPage <= 0 || $this->currentPage > $this->totalPages) {
            $this->is404 = true;
        } else {
            $this->is404 = false;
        }
    }

    public function getContents()
    {
        return $this->content;
    }

    public function getPagination()
    {
        $liStr = "";
        for ($i = 0; $i < $this->totalPages; $i++) {
            $activeClass = (($i + 1) == $this->currentPage) ? " class='active'" : "";
            $url = (($i + 1) == $this->currentPage) ? "javascript:;" : $this->url . ($i + 1);
            $liStr .= '<li' . $activeClass . '><a href="' . $url . '">' . ($i + 1) . '</a></li>' . "\n";
        }

        $ul = "<ul class='pagination'><li" . $this->fclass . "><a href='" . $this->lastPage . "'>&laquo;</a></li>" . $liStr . "<li" . $this->lclass . "><a href='" . $this->nextPage . "'>&raquo;</a></li></ul>";
        return $ul;
    }

    public function getOutPut()
    {
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