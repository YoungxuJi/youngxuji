<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.5.28
 * Time: 18:02
 */
namespace pubfun;
class DataBase
{
    static private $conn;


    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @return \mysqli
     * 单例模式
     */
    static public function get_conn(){
        if(self::$conn==null) {
            self::$conn = new \mysqli(\GLOBAL_CONFIG\DATABASE\HOST,
            \GLOBAL_CONFIG\DATABASE\USERNAME,
            \GLOBAL_CONFIG\DATABASE\PASSWORD,
            \GLOBAL_CONFIG\DATABASE\DBNAME);
            if (self::$conn->connect_error) {
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'服务器连接失败','error');
                define('_ERROR_STATUS_CODE',500);
                die('服务数据库故障,请通知管理员.故障代码:不告诉你,跟你说你也不懂');
            }
            self::$conn->query("set names utf8");//解决中文乱码问题
        }

        return DataBase::$conn;
    }
}
