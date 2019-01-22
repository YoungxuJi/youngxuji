<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.5.28
 * Time: 22:50
 */
namespace model\table;
use model\SpecialDataType;
use pubfun\VC;

/**
 * Class Account
 * @package model\table
 * @method Auth getAuthByForeignKey()
 */
class Account extends \model\BaseTable
{

    CONST MODE_INSERT = 0B1; //新增模式
    CONST MODE_UPDATE = 0B10;//更新模式


    /**
     * Account constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }


    public function setRules()
    {
        /*************过滤规则**************/
        $this->vc->setAllowFields('*,-update_time,-create_time',VC::MODE_BASE);
        $this->vc->setAllowFields('username,password,nickname,auth_id',self::MODE_INSERT);
        $this->vc->setAllowFields('keyid,password,nickname,auth_id,last_session',self::MODE_UPDATE);
        /*************校验规则**************/
        //基本模式 MODE_BASE
        $this->vc->addRule('keyid',['\pubfun\VC', 'isKeyid'],[],'id格式错误!',VC::CHECK_EXISTS,VC::MODE_BASE);
        $this->vc->addRule('username',['\pubfun\VC', 'isAz09_'],[4,32],'用户名格式错误!',VC::CHECK_EXISTS,VC::MODE_BASE);
        $this->vc->addRule('password',['\pubfun\VC', 'isAz09_'],[6,16],'密码格式不规范!',VC::CHECK_EXISTS,VC::MODE_BASE);
        $this->vc->addRule('nickname',['\pubfun\VC','checkStrLen'],[0,32],'昵称不能超过32个字符!',VC::CHECK_EXISTS,VC::MODE_BASE);
        $this->vc->addRule('auth_id',['\pubfun\VC', 'isKeyid'],[],'权限id格式错误!',VC::CHECK_NOTEMPTY,VC::MODE_BASE);
        $this->vc->addRule('last_session',['\pubfun\VC', 'isAz09_'],[0,32],'last_session格式错误!',VC::CHECK_EXISTS,VC::MODE_BASE);
        //新增模式 MODE_INSERT
        $this->vc->addRule('username',['\pubfun\VC','isNotNull'],[],'用户名不能为空!',VC::CHECK_ANYTIME,self::MODE_INSERT);
        $this->vc->addRule('password',['\pubfun\VC', 'isNotNull'],[],'密码不能为空!',VC::CHECK_ANYTIME,self::MODE_INSERT);
        //更新模式 MODE_UPDATE
        $this->vc->addRule('keyid',['\pubfun\VC', 'isNotNull'],[],'更新id不能为空',VC::CHECK_ANYTIME,self::MODE_UPDATE);
    }

    /**
     * 带验证过程
     * @param array $data
     * @return false|int
     */
    public function insert(array $data)
    {
        $ver_res = $this->verify($data, self::MODE_INSERT);
        if ($ver_res['result'] !== true) {
            return false;
        }
        $data['password'] = md5($data['username'] . '!@#$%^&*()_+' . $data['password']);
        $data['username'] = NEW SpecialDataType(SpecialDataType::TYPE_UNIQUE,$data['username']);
        if (!empty($data['auth_id'])) {
            $data['auth_id']=new SpecialDataType(SpecialDataType::TYPE_FOREIGN_KEY,$data['auth_id']);
        }
        return parent::insert($data);
    }

    public function update(array $data)
    {
        $ver_res = $this->verify($data,self::MODE_UPDATE);
        if($ver_res['result']!==true){
            return false;
        }
        if(isset( $data['password'])){
            $username = $this->_quickSelects($this->tablename,['keyid'=>$data['keyid']],'username')[0]['username']??'';
            $data['password'] = md5($username.'!@#$%^&*()_+'.$data['password']);
        }

        if (!empty( $data['auth_id'])) {
            $data['auth_id']=new SpecialDataType(SpecialDataType::TYPE_FOREIGN_KEY,$data['auth_id']);
        }
        return parent::update($data);
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param String $username
     * @param String $password
     * @return bool|int
     */
    static public function isPwTrue(String$username,String$password){
        $conn = \pubfun\DataBase::get_conn();
        $stmt = $conn->prepare("select account.password,account.keyid from account where account.username = ?");
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if(!empty($result)){
            $password = md5($username.'!@#$%^&*()_+'.$password);
            if($password == $result['password']){return $result['keyid'];}
        }
        return false;

    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param $sessionid
     * @return bool
     * <br>更新最后登录信息
     */
    public function updateLoginInfo(string $sessionid){
        if(empty($this->data)){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'未选取用户,无法更新用户最后登录信息!','warning');
            return false;
        }
        $sql = "update account set 
account.last_session = ?,
account.last_activetime = current_timestamp 
where account.keyid = {$this->data['keyid']}";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s',$sessionid);
        $stmt->execute();
        if($stmt->affected_rows>=1){
            $stmt->close();
            return true;
        }else{
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"更新用户'{$this->data['keyid']}'的最后登录信息失败",'error');
            return false;
        }
    }

}