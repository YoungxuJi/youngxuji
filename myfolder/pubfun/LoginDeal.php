<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.7
 * Time: 10:31
 */
namespace pubfun;
class LoginDeal
{
    private $_account_id;
    private $_account;
//    private $login_stats;//判断登陆状态 1:已经登陆

    static private $loginDeal;

    private function __construct()
    {
        if(isset($_COOKIE[ini_get('session.name')])) {
            session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
            session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
            session_start();
            if (isset($_SESSION['account_id'])) {//提取session里的用户信息
                $this->_account_id = $_SESSION['account_id'];
            }
            session_write_close();//立即关闭session文件
        }
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @return LoginDeal
     * 单例模式
     */
    public static function getLoginDeal()
    {
        if(!isset(self::$loginDeal)){
            self::$loginDeal = new LoginDeal();
        }
        return self::$loginDeal;
    }


    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @return String|null
     */
    public function get_account_id(){
        return $this->_account_id;
    }

    /**
     * 已登录则返回对应的account类,未登录则返回空account类
     * @author 季煦阳 YoungxuJi@qq.com
     * @return \model\table\Account
     */
    public function get_account(){
        if(empty($this->_account_id)){
            \DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'用户未登录,无法获取用户信息','warning');
            return new \model\table\Account();
        }elseif(!isset($this->_account)){//如果account空并且account_id非空,
            /*************加载account信息**************/
            $this->_account = new \model\table\Account();
            $this->_account->select($this->_account_id);

            if($this->_account->errno!==0){
                \DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'无法查找到当前登录用户信息!'.$this->_account->error['msg'],'warning');
                $this->logout();
                return new \model\table\Account();
            }
        }
        return $this->_account;
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param $username
     * @param $password
     */
    public function login($username,$password){
        if($account_id = \model\table\Account::isPwTrue($username,$password)){//验证成功

            session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
            session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
            session_start();
            $session_id = session_id();
            $_SESSION['account_id'] = $account_id;
            session_write_close();//立即关闭session文件
            $this->_account_id = $account_id;
            $this->get_account()->updateLoginInfo($session_id);//更新最后登陆信息;
            recordAccess(\model\table\Access_log::ACTION_LOGIN);
        }
    }

    public function logout(){
        session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
        session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
        session_start();
        unset($_SESSION['account_id']);
        session_write_close();
        recordAccess(\model\table\Access_log::ACTION_LOGOUT);
        $this->_account_id = null;
        $this->_account = null;
    }

//    /**
//     * @return mixed
//     */
//    public function getLoginStats()
//    {
//        if(!empty($this->account_id) and !isset($this->login_stats)){
//            if($this->get_account()==null){
//                $this->account_id = null;
//                session_cache_expire(\GLOBAL_CONFIG\SESSION_EXPIRE);//设置session有效期
//                session_set_cookie_params(\GLOBAL_CONFIG\SESSION_COOKIE);//设置session的cookie有效期
//                session_start();
//                unset($_SESSION['account_id']);
//                session_write_close();//立即关闭session文件
//            }else{
//                $this->login_stats = 1;
//            }
//        }
//        return $this->login_stats;
//    }
}