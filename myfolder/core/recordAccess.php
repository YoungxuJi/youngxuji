<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.9
 * Time: 19:26
 */
use pubfun\LoginDeal;

/**
 * @author 季煦阳 YoungxuJi@qq.com
 * @param String $action
 * @return bool|int
 */
function recordAccess(String$action = '')
{

    $data = [];
    if(isset($_COOKIE[ini_get('session.name')])) {
        $data['sessionid'] = $_COOKIE[ini_get('session.name')];
    }
    if(isset($_SERVER['REMOTE_ADDR'])){
        $data['ip'] = $_SERVER['REMOTE_ADDR'];
    }
    if(isset($_SERVER['REQUEST_URI'])){
        $data['url'] = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }
    $loginDeal = LoginDeal::getLoginDeal();
    $account_id = $loginDeal->get_account_id();
    if(!empty($account_id)){
        $data['account_id'] = $account_id;
    }
    if(!empty($action)){
        $data['action'] = $action;
    }

    $access_log = new \model\table\Access_log();
    return $access_log->insert($data);
}