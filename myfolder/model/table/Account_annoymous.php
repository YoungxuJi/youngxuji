<?php
/**
 * 匿名账户表 model类
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.8.19
 * Time: 21:58
 */

namespace model\table;


use model\BaseTable;
use pubfun\VC;

class Account_annoymous extends BaseTable
{

    //todo 实现匿名账户表
    CONST MODE_INSERT = 0B1; //新建匿名用户
    CONST MODE_UPDATE = 0B10;//更新匿名用户
    /**
     *
     */
    function setRules()
    {
        // TODO: Implement setRules() method.
        /*************过滤规则**************/
        $this->vc->setAllowFields('*,-update_time,-create_time',VC::MODE_BASE);
        $this->vc->setAllowFields('cookie_id,last_ip,last_activetime',self::MODE_INSERT);
        $this->vc->setAllowFields('keyid,last_ip,last_activetime',self::MODE_UPDATE);
        /*************校验规则**************/
        //基本模式 MODE_BASE
        $this->vc->addRule('keyid',['\pubfun\VC', 'isKeyid'],[],'id格式错误!',VC::CHECK_EXISTS,VC::MODE_BASE);
        $this->vc->addRule('ip', ['\pubfun\VC', 'checkStrLen'], [0, 32], 'ip格式错误!', VC::CHECK_NOTEMPTY, VC::MODE_BASE);

        //新建匿名用户 MODE_INSERT
        $this->vc->addRule('cookie_id',['\pubfun\VC','isNotNull'],[],'cookie_id不能为空!',VC::CHECK_ANYTIME,self::MODE_INSERT);
    }


    public function insert(array $data)
    {
        $ver_res = $this->verify($data, self::MODE_INSERT);
        if ($ver_res['result'] !== true) {
            return false;
        }

        return parent::insert($data);
    }
    public function update(array $data)
    {
        $ver_res = $this->verify($data,self::MODE_UPDATE);
        if($ver_res['result']!==true){
            return false;
        }
        return parent::update($data);
    }
}