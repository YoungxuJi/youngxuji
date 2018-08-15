<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.7
 * Time: 11:27
 */
namespace model\table;


use pubfun\VC;

class Access_log extends \model\BaseTable
{
    CONST ACTION_ACCESS     = 'access';     //浏览
    CONST ACTION_LOGIN      = 'login';      //登录
    CONST ACTION_LOGOUT     = 'logout';     //登出

    CONST MODE_INSERT       = 0B1;          //新增模式

    public function __construct()
    {
        parent::__construct();
    }
    public function setRules()
    {
        $this->vc->setAllowFields('sessionid,ip,url,account_id,action');

        $this->vc->addRule('sessionid', ['\pubfun\VC', 'isAz09_'], [0, 32], 'sessionid格式错误!', VC::CHECK_NOTEMPTY, VC::MODE_BASE);
        $this->vc->addRule('ip', ['\pubfun\VC', 'checkStrLen'], [0, 32], 'ip格式错误!', VC::CHECK_NOTEMPTY, VC::MODE_BASE);
        $this->vc->addRule('url', ['\pubfun\VC', 'checkStrLen'], [0, 1024], 'url格式错误', VC::CHECK_NOTEMPTY, VC::MODE_BASE);
        $this->vc->addRule('account_id', ['\pubfun\VC', 'isKeyid'], [], 'account_id格式错误!', VC::CHECK_NOTEMPTY, VC::MODE_BASE);
        $this->vc->addRule('action', ['\pubfun\VC', 'checkStrLen'], [0, 32], 'action格式错误!', VC::CHECK_NOTEMPTY, VC::MODE_BASE);
    }

    public function insert(array $data)
    {
        $ver_res = $this->verify($data, self::MODE_INSERT);
        if ($ver_res['result'] !== true) {
            return false;
        }
        return parent::insert($data);
    }
}