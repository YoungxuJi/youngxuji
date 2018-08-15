<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.20
 * Time: 0:08
 */
require_once __DIR__.'/../../myfolder/core/require.php';
use \pubfun\LoginDeal;
use \pubfun\ReturnDeal;
use \pubfun\VerificationCode;

Const RETURN_CAPTCHA_WRONG = -1;//验证码错误
const RETURN_CAPTCHA_OUTDATE = -2;//验证码过期

$ld = LoginDeal::getLoginDeal();
if(!empty($ld->get_account_id())){//当前用户已登录
    ReturnDeal::returnError('已登录,请退出登录再注册');
}
/*************校验入参**************/
//if(!isset($_POST['username'])){
//    ReturnDeal::returnError('缺少参数!');
//}elseif(!BaseTable::checkAz09_($_POST['username'],1)){
//    ReturnDeal::returnError('username不符合规范');
//}else{
//    $data['username'] = $_POST['username'];
//}
//if(!isset($_POST['password'])){
//    ReturnDeal::returnError('缺少参数');
//}elseif(!is_string($_POST['password'])){
//    ReturnDeal::returnError('password不符合规范');
//}else{
//    $data['password'] = $_POST['password'];
//}
if(!isset($_POST['vercode'])){
    ReturnDeal::returnError('缺少验证码参数');
}elseif (!is_string($_POST['vercode'])){
    ReturnDeal::returnError('验证码不符合规范');
}
//if(!isset($_POST['nickname'])){
//
//}elseif(!is_string($_POST['nickname'])){
//    ReturnDeal::returnError('昵称格式错误');
//}elseif(strlen($_POST['nickname'])>32 or $_POST['nickname']===''){
//    ReturnDeal::returnError('昵称长度不规范');
//}elseif(preg_match('/^[^\s]+$/',$_POST['nickname'])<1){
//    ReturnDeal::returnError('昵称不能包含包含空白字符');
//}else{
//    $data['nickname'] = $_POST['nickname'];
//}
/*************验证码校验**************/
$vc = new VerificationCode();

switch ($vc->checkCode($_POST['vercode'])){
    case VerificationCode::CODE_FALSE:
        ReturnDeal::returnError('验证码错误,请重新输入',RETURN_CAPTCHA_WRONG);
        break;
    case VerificationCode::CODE_ERROR:
        ReturnDeal::returnError('验证码过期,请重新刷新验证码',RETURN_CAPTCHA_OUTDATE);
        break;
}
/*************注册**************/
$account = new \model\table\Account();
//$has_same = $account->select($data['username'],'username');
//if($has_same){
//    ReturnDeal::returnError('用户名已存在,请更换用户名!');
//}
$post_data = $_POST;
$account_id = $account->insert($post_data);
if($account_id===false){
    switch ($account->errno){
        case \model\BaseTable::VERIFY_ERROR://字段校验错误
            ReturnDeal::returnError($account->error['msg'],$account->errno,$account->error);
            break;
        /** @noinspection PhpMissingBreakStatementInspection */
        case \model\BaseTable::NOT_UNIQUE://唯一性错误
            if($account->error['error_field']=='username') {
                ReturnDeal::returnError('改用户名已注册,换一个吧');
            }
        default: ReturnDeal::returnError('注册账号失败,请联系管理员!',$account->errno,$account->error);
    }
}else{
    $ld->login($_POST['username'],$_POST['password']);
    ReturnDeal::returnOK();
}