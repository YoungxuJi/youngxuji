<?php
/**
 * 验证类,主要model用的
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.23
 * Time: 18:45
 */

namespace pubfun;


class VC
{
    private $_rules = [];
    private $_allow = [self::MODE_BASE=>'*'];

    CONST CHECK_ANYTIME     = 1;    //任何时候都要验证
    CONST CHECK_EXISTS      = 2;    //变量值存在就要验证,即使 ===null
    CONST CHECK_NOTEMPTY    = 3;    //变量值非{null,''}就要验证

    CONST MODE_BASE          = 0B0;  //基本模式,任何模式下都需要进行基本模式的规则验证

    /**
     * @param $field //待验证的字段名称
     * @param callable $callback //验证方法
     * @param array $callback_param //传入验证方法的额外参数
     * @param string $error_tip //验证失败的错误信息
     * @param int $when_check /什么时候验证
     * @param int $mode //验证模式
     */
    public function addRule($field,                             //待验证的字段名称
                            callable$callback,                  //验证方法
                            array $callback_param = [],               //传入验证方法的额外参数
                            $error_tip = '验证未通过!',           //验证失败的错误信息
                            $when_check = self::CHECK_EXISTS,  //什么时候验证
                            $mode = self::MODE_BASE)             //验证模式
    {
        $this->_rules[$mode][] = [
            'field'         =>$field,
            'callback'      =>$callback,
            'callback_param'=>$callback_param,
            'error_tip'     =>$error_tip,
            'when_check'    =>$when_check,
            'mode'          =>$mode,
        ];
    }

    public function setAllowFields(string $fields = '*',$mode = self::MODE_BASE)
    {
        $this->_allow[$mode] = $fields;
    }

    /**
     * @return bool
     */
    public function hasSetRule(){
        return !empty($this->_rules);
    }

    public function filter(&$data,$now_mode = self::MODE_BASE){
        foreach ($this->_allow as $mode=>$fields) {
            if(($mode&$now_mode)!==$mode){
                continue;
            }
            $field_arr = explode(',',$fields);
            $tmp_data = [];
            foreach ($field_arr as $field){
                if($field === '*'){
                    $tmp_data = $data;
                }elseif($field[0]==='-'){
                    unset($tmp_data[substr($field,1)]);
                }elseif(array_key_exists($field,$data)){
                    $tmp_data[$field] = $data[$field];
                }
            }
            $data = $tmp_data;
        }
        return $data;
    }

    /**
     * @param $data
     * @param int $now_mode
     * @param bool $filter
     * @return array <pre>['result'=>true] or
     * [
     *   'result'=>false,
     *   'error_field'=>$rule['field'],
     *   'tip'=>$rule['error_tip'],
     * ]</pre>
     */
    public function verify(&$data, $now_mode = self::MODE_BASE, $filter = true)
    {
        if($filter){
            $this->filter($data,$now_mode);
        }
        foreach ($this->_rules as $mode=> $mode_rules){//遍历每一个模式
            if(($mode&$now_mode)!==$mode){
                continue;
            }
            foreach ($mode_rules as $rule){//遍历模式的每一个规则
                /*************根据when_check确定是否需要进行校验判断**************/
                if($rule['when_check']===self::CHECK_NOTEMPTY and
                    !isset($data[$rule['field']])||$data[$rule['field']]===''){
                    continue;
                }elseif($rule['when_check']===self::CHECK_EXISTS and
                    !array_key_exists($rule['field'],$data)){
                    continue;
                } /** @noinspection PhpStatementHasEmptyBodyInspection */
                elseif($rule['when_check']===self::CHECK_ANYTIME){
                    //do nothing;
                }
                /*************进行校验判断,失败跳出循环返回,成功继续下一个循环判断**************/
                array_unshift($rule['callback_param'], $data[$rule['field']]??null);
                if (!call_user_func($rule['callback'], ...(array)$rule['callback_param'])) {
                    goto fault_verify;
                }
            }//mode_rules foreach end;
        }//rules foreach end;
        return ['result'=>true];
fault_verify://校验失败时返回
        return[
            'result'=>false,
            'error_field'=>$rule['field'],
            'tip'=>$rule['error_tip'],
        ];
    }

    /**
     * @param $value
     * @param int $min 最小长度
     * @param int $max 最大长度,-1表示不限制
     * @return bool
     * 判断字符串长度是否符合规范
     */
    public static function checkStrLen($value,$min = 0, $max = -1){
        if(!is_string($value)){
            return false;
        }
        $len = strlen($value);
        if($len<$min){
            return false;
        }
        if($max>=0 and $len>$max){
            return false;
        }
        return true;
    }

    /**
     * @param $value
     * @param int $min 最小长度
     * @param int $max 最大长度,-1表示不限制
     * @return bool
     */
    public static function isAz09_($value, $min = 0, $max = -1){
        if(!is_string($value)){
            return false;
        }
        $len = strlen($value);
        if($len<$min){
            return false;
        }
        if($max>=0 and $len>$max){
            return false;
        }
        if(preg_match('/^[a-zA-Z0-9_]*$/',$value)<1){
            return false;
        }
        return true;
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param int|string $value
     * @return bool 判断是否keyid格式,长度限制11位
     */
    public static function isKeyid($value){
        if(!is_numeric($value)){
            return false;
        }
        if(preg_match('/^[1-9][0-9]{0,10}$/',$value)<1){
            return false;
        }
        return true;
    }

    public static function isNotNull($value){
        return !is_null($value);
    }
}