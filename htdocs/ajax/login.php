<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.17
 * Time: 20:19
 */
require_once __DIR__.'/../../myfolder/core/require.php';
use \pubfun\ReturnDeal;
use \pubfun\LoginDeal;
use \pubfun\VerificationCode;

Const RETURN_CAPTCHA_WRONG = -1;//验证码错误
const RETURN_CAPTCHA_OUTDATE = -2;//验证码过期

if(!isset($_POST['vercode'])){
    ReturnDeal::returnError('缺少验证码参数');
}elseif (!is_string($_POST['vercode'])){
    ReturnDeal::returnError('验证码不符合规范');
}

$loginDeal = LoginDeal::getLoginDeal();
if(!empty($loginDeal->get_account_id())){
    ReturnDeal::returnError('当前您已登录,请勿重复登录','hasLogin');
}
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
/*************登录**************/
$loginDeal->login($_POST['username'],$_POST['password']);
if($loginDeal->get_account_id()==null){
    ReturnDeal::returnError('用户名或密码错误');
}else{
    ReturnDeal::returnOK();
}