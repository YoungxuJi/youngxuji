<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.5.28
 * Time: 22:50
 */
namespace model\table;

class Auth extends \model\BaseTable
{
//    private $keyid;
//    private $role_name;
//    private $role_comment;
//    private $del_talk;
//
//
//
//    public $has_error = 0;
//    public $error_msg;
//
//    CONST KEYID = 'keyid';
//    CONST ROLE_NAME = 'role_name';
//    CONST ROLE_COMMENT = 'role_comment';
//    CONST DEL_TALK = 'del_talk';

    public function setRules()
    {

    }

    /**
     * Auth constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param string $value
     * @param string $fieldname
     * @return bool
     * <br>查询一条记录,返回成功或者失败
     */
//    public function select($value,$fieldname = 'keyid'){
//        switch ($fieldname){
//            case 'keyid':
//                $sql = "select * from auth where $fieldname = ?";
//            $stmt = $this->conn->prepare($sql);     //sql预处理
//            $stmt->bind_param('i',$value);                                          //绑定参数
//            $stmt->execute();                                                                   //执行sql
//            $result = $stmt->get_result();
//            $stmt->close();
//                if($result_arr = $result->fetch_assoc()){
//                    $this->keyid = $result_arr['keyid'];
//                    $this->role_name = $result_arr['role_name'];
//                    $this->role_comment = $result_arr['role_comment'];
//                    $this->del_talk = $result_arr['del_talk'];
//                    $result->free_result();
//                    return true;
//                }else{
//                    $this->has_error++;
//                    $this->error_msg = '没有找到该条记录!';
//                    return false;
//                }
//            default:
//                \DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"禁止的查询操作:select * from account where $fieldname = ?",'error');
//                $this->has_error++;
//                $this->error_msg = '禁止的查询操作!';
//                return false;
//        }
//    }

//    /**
//     * @return mixed
//     */
//    public function getKeyid()
//    {
//        return $this->keyid;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getRoleName()
//    {
//        return $this->role_name;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getRoleComment()
//    {
//        return $this->role_comment;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getDelTalk()
//    {
//        return $this->del_talk;
//    }


}