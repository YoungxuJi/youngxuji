<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.13
 * Time: 17:27
 */
require_once __DIR__.'/config.php';
require_once __DIR__.'/DebugLog.php';
require_once __DIR__ . '/recordAccess.php';

spl_autoload_register('autoload' );
//正式环境关掉错误日志输出
ini_set('display_errors', true);
error_reporting(E_ALL);
//注册死亡程序
register_shutdown_function('die_code');
ob_start();//死亡程序中有设置设置状态码语句,因此要保证中间程序没有输出(如果中间程序执意要强行输出缓存那也没办法)
record();
/**
 * @author 季煦阳 YoungxuJi@qq.com
 * <br> 记录用户页面和请求浏览
 */
function record()
{
    if(empty($_COOKIE['has_record'])){
        recordAccess(\model\table\Access_log::ACTION_ACCESS);
        setcookie('has_record',1,0,GLOBAL_CONFIG\URL_ROOTPATH);
    }
}

/**
 * @author 季煦阳 YoungxuJi@qq.com
 * @param $class
 * 自动注册
 */
function autoload($class){
    $class = str_replace('\\','/',$class);
    $path = GLOBAL_CONFIG\PROJ_ROOTPATH.$class.'.php';
    if(!file_exists($path)){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"调用了不存在的类:$class");
    }else{
        require $path;
    }
}

function die_code()
{
    if(defined('_ERROR_STATUS_CODE')){
DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'程序异常退出,退出码:'._ERROR_STATUS_CODE.' 退出内容:
'.ob_get_contents());
        http_response_code(_ERROR_STATUS_CODE);
//        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])&&strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])==='xmlhttprequest'){
//            $content = ob_get_contents();
//            ob_end_clean();
//            http_response_code(_ERROR_STATUS_CODE);
//            \pubfun\ReturnDeal::returnError($content,_ERROR_STATUS_CODE);
//        }
    }
}