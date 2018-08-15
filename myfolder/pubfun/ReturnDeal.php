<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.18
 * Time: 8:43
 */
namespace pubfun;
/**
 * Class ReturnDeal
 * @package pubfun
 * <pre>
 * 约定:
 *  返回的code为字符串0000时表示一切OK
 *  返回的code为负数时表示业务错误代码,前端根据具体的业务做处理.不同的业务可以有相同的业务错误代码
 *  返回的code为正数时表示全局错误代码,最好是600-999之间的整数(区分http状态码),前端根据错误代码在不同的业务通常做相同的处理(例如:用户未登录,需要跳转到登陆页面的情况)
 *  'error'为通用错误代码
 * </pre>
 */
class ReturnDeal
{
    static function returnOK($message = 'OK',$data = null){
        die(json_encode([
            'code'=>'0000',
            'message'=>$message,
            'data'=>$data
        ],JSON_UNESCAPED_UNICODE));
    }

    static function returnError($message = '未知错误',$code = 'error', $data = null){
        die(json_encode([
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ],JSON_UNESCAPED_UNICODE));
    }

    static function returnData($data ,$message = 'OK',$code = '0000'){
        die(json_encode([
            'code'=>$code,
            'message'=>$message,
            'data'=>$data
        ],JSON_UNESCAPED_UNICODE));
    }
}