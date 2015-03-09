<?php
/**
 * Created by PhpStorm.
 * User: WANG Xu
 * Date: 2014/10/17
 * Time: 14:18
 */
namespace Org\Util;

class URLlog{
    static function log($url){
        $iplog = M('iplog');
        $data['ip'] = get_client_ip();
        $data['url'] = $url;
        $data['logtime'] = time();
        $iplog->data($data)->add();
    }
}