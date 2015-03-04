<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2015/1/29
 * Time: 9:28
 */
namespace Test\Model;

use Think\Model;

class CostModel extends Model
{
    protected $connection = array(
        'db_type' => 'mysql',
        'db_user' => 'root',
        'db_pwd' => 'rootwang',
        'db_host' => 'localhost',
        'db_port' => '3306',
        'db_name' => 'wx',
        );
}