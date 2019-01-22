<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.7
 * Time: 20:04
 */
namespace model;

use pubfun\VC;

abstract class BaseTable
{
    protected $conn;    //数据库连接类 mysqli
    protected $tablename;  //表名
    protected $vc;//校验类
    protected $ver_data;    //待校验数据

    public $data;    //一行数据
    public $errno = 0;//正数表示mysql错误代码,负数表示自定义错误代码,0表示没有错误
    public $error =[/*NOT NULL*/'msg'=>'',/*NULL*/'error_field'=>''];  //错误提示

    /*************错误代码**************/
    //系统错误(出现此类错误一般需要修改代码)
    Const UNKNOW_ERROR      = -1;   //未知错误
    CONST WRONG_FIELD       = -3;   //错误的字段名称
    CONST WRONG_VALUE       = -4;   //错误的属性值//一般来说是系统错误,也有可能是前台传进来二进制数据等未知格式未经校验直接插入,
                                                //暂时也归为系统错误吧
    //业务错误
    CONST WRONG_FOREIGN_KEY = -5;   //错误的外键值
    const FALSE_SELECT      = -2;   //查询失败,没有找到记录
    CONST NOT_UNIQUE        = -6;   //字段唯一校验未通过
    CONST NO_KEYID          = -7;   //更新数据缺少主键
    CONST NO_UPDATE_DATA    = -8;   //更新时缺少数据字段
    CONST WRONG_KEYID       = -9;   //更新不存在的数据(keyid值不存在)
    CONST VERIFY_ERROR      = -10;  //通用验证错误,此时error=['result'=>bool,'error_field'=>string,'tip'=>string]
//
//    /*************通用CURD操作属性配置**************/
//    CONST TYPE_FOREIGN_KEY  = 0b1;      //关联外键
//    CONST TYPE_UNIQUE       = 0B10;     //唯一索引
//    CONST TYPE_FUNCTION     = 0b100;    //调用mysql方法
//    const TYPE_TREE         = 0B1000;   //数结构


    /**
     * BaseTable constructor.
     * <br>连接数据库
     * <br>获取默认表名(通过类名获取)
     */
    function __construct()
    {
        $this->conn = \pubfun\DataBase::get_conn();
        if($this->tablename==null){
            $this->tablename = strtolower(preg_replace ('/^(?:.+\\\\)?(\w+)$/','$1',get_class($this)));
//\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'tablename = '.$this->tablename,'debug');
        }

        $this->vc = new VC();
    }

    /**
     *
     */
    abstract function setRules();

    /**
     * @param $data
     * @param int $mode
     * @return array
     * 设置规则,过滤并验证字段.和VC类同名方法不同的是此方法把过滤后的数据保存在类变量里,因此配合已实例化对象的方法可以联合字段检查
     */
    public function verify(&$data,$mode=VC::MODE_BASE)
    {
        if(!$this->vc->hasSetRule()){
            $this->setRules();
        }
        $this->ver_data = $this->vc->filter($data,$mode);
        $res = $this->vc->verify($data,$mode,false);
        if($res['result']!==true){
            $this->errno = self::VERIFY_ERROR;
            $this->error = ['msg'=>$res['tip'],'error_field'=>$res['error_field']];
        }
        return $res;
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param $value
     * @return null|array
     * <br>快速获取指定keyid的一行数据,失败返回null
     */
    public function __invoke($value)
    {
        if($this->select($value)){
            return $this->data;
        }else{
            return null;
        }
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param string $name
     * @return null|mixed
     */
    public function __get(string $name)
    {
        if(isset($this->$name)){
            return$this->$name;
        }else{
            return null;
        }
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param string $value
     * @param string $fieldname
     * @return bool
     * <br>只获取一行数据,所以要求field为索引字段(如果不是,会获取第一条符合条件的第一行数据)
     * <br>
     */
    public function select(string $value,string $fieldname = 'keyid'){
        $sql = "select * from $this->tablename where $fieldname = ?";
        $stmt = $this->conn->prepare($sql);     //sql预处理
        if(!$stmt){
            $this->errno = self::WRONG_FIELD;
            $this->error['msg'] = '查询失败,可能没有对应的字段名';
            return false;
        }
        $stmt->bind_param('s',$value);      //绑定参数
        $stmt->execute();                              //执行sql
        $result = $stmt->get_result();          //获取结果集
        $stmt->close();
        if($result_arr = $result->fetch_assoc()){
            $this->data = $result_arr;
            return true;
        }else{
            $this->data = null;
            $this->errno = self::FALSE_SELECT;
            $this->error['msg'] = '没有找到该条记录!';
            return false;
        }
    }

    /**
     * @param string $tablename
     * @param array $where_arr
     * @param string $field
     * @return bool|mixed
     */
    protected function _quickSelects(string $tablename, array $where_arr, string $field='*')
    {
        $where_sql = '';
        $type_mark = '';
        foreach ($where_arr as $key=>$value){
            $where_sql.=",$key = ?";
            $type_mark.='s';
        }
        $where_sql = substr($where_sql,1);
        $sql = "select $field from $tablename where $where_sql";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt){
            return false;
        }
        $data_else = $where_arr;
        $data_1 = reset($data_else);
        unset($data_else[key($data_else)]);
        $data_else = array_values($data_else);
        $stmt->bind_param($type_mark,$data_1,...$data_else);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param array $data
     * @return false|int
     * <br>插入一行数据,成功返回主键值,失败返回false
     *
     *
     *
     */
    protected function insert(array $data){
        $insert_data = $data;
        $type_of_val = '';//预处理格式字符串
        $q_marks = '';  //预处理问号标记字符串
        $this->conn->autocommit(false);
        foreach ($insert_data as $key=> $val){
            if(is_string($val)){
                $type_of_val .='s';
                $q_marks .= ',?';
            }elseif(is_int($val)){
                $type_of_val .='s';
                $q_marks .= ',?';
            }elseif (is_float($val)){
                $type_of_val .='s';
                $q_marks .= ',?';
            }elseif (is_null($val)){
//                $type_of_val .='s';
                $q_marks .= ',NULL';
                unset($insert_data[$key]);
            }elseif (is_object($val) and get_class($val)==='model\SpecialDataType'){
                switch ($val->type){
                    case SpecialDataType::TYPE_FOREIGN_KEY://外键约束条件:
                        /*************稳定实现功能就好,求求你别优化啦**************/
                        /*************因代码要被复制到其他地方,现在我要开始优化了 Time:18.7.22 15:22 **************/
                        /*************优化成功了(吧) Time:18.7.22 15:36 **************/
                        $foreign_id = $val->option;
                        $foreign_table = $val->extra??substr($key,0,-3);
                        $res = $this->_quickSelects($foreign_table,['keyid'=>$foreign_id]);
                        if($res === false){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"未知错误,可能表名 $foreign_table 不存在");
                            $this->errno = self::UNKNOW_ERROR;  //暂时未归类,如果执行到这一行肯定代码有问题
                            $this->error['msg'] = '未知错误';
                            $return = false;
                            goto quit_function;
                        }
                        if(empty($res)){
                            $this->errno = self::WRONG_FOREIGN_KEY;
                            $this->error['msg'] = '不存在的外键值!';
                            $return = false;
                            goto quit_function;
                        }

                        /*$sql = "select $foreign_table.keyid from $foreign_table where $foreign_table.keyid=?";
                        $stmt = $this->conn->prepare($sql);     //sql预处理
                        $stmt->bind_param('s',$foreign_id);      //绑定参数
                        if(!$stmt){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'插入数据配置外键错误,没有对应表名:'.var_export($val,true),'error');
                            $this->errno = self::WRONG_VALUE;
                            $this->error['msg' = '新增失败,可能没有对应的外键表名';
                            $return = false;
                            goto quit_function;
                        }
                        $stmt->execute();                              //执行sql
                        $res = $stmt->get_result();
                        if($res->num_rows<=0) {
                            $this->errno = self::WRONG_FOREIGN_KEY;
                            $this->error['msg' = '查询失败,找不到对应外键';
                            $return = false;
                            goto quit_function;
                        }*/
                        /*************外键校验通过,进行正常数据模式**************/
                        $insert_data[$key] = $val->option;
                        $type_of_val .='s';
                        $q_marks .= ',?';
                        break;
                    case SpecialDataType::TYPE_UNIQUE:
                        $unique_value = $val->option;
                        $res = $this->_quickSelects($this->tablename,["$key"=>$unique_value]);
                        if($res===false){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"插入唯一字段出错. \$key=$key,\$val=$val->option",'error');
                            $this->errno = self::UNKNOW_ERROR;  //暂时未归类,如果执行到这一行肯定代码有问题
                            $this->error['msg'] = '未知错误';
                            $return = false;
                            goto quit_function;
                        }
                        if(!empty($res)){
                            $this->errno = self::NOT_UNIQUE;
                            $this->error = ['msg'=>"$key 已存在,无法新增",'error_field'=>$key];
                            $return = false;
                            goto quit_function;
                        }
                        $insert_data[$key] = $val->option;
                        $type_of_val .='s';
                        $q_marks .= ',?';
                        break;
                    case SpecialDataType::TYPE_FUNCTION:
                        $function_name = $val->option;
                        $q_marks .= ",$function_name";
                        unset($insert_data[$key]);
                        break;
                    default:
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'插入数据配置type错误:'.var_export($val,true),'error');
                        $this->errno = self::WRONG_VALUE;
                        $this->error['msg'] = "新增数据'$key'字段自定义类型配置错误";
                        $return = false;
                        goto quit_function;
                }
            }else{
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'插入数据有未知数据类型:'.var_export($insert_data,true),'error');
                $this->errno = self::WRONG_VALUE;
                $this->error['msg'] = "新增数据'$key'字段格式错误";
                $return = false;
                goto quit_function;
            }
        }
        $q_marks = substr($q_marks,1);//去掉问号标记字符串的第一个','
        $fields = implode(',',array_keys($data));//插入字段这里要用原始数据的字段,因为$insert_data的null值被unset了
        /** @noinspection SqlResolve */
        /** @noinspection SqlInsertValues */
        $sql = "insert into $this->tablename ($fields,create_time) value ($q_marks,null)";
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'$sql = '.$sql,'debug');
        $stmt = $this->conn->prepare($sql);     //sql预处理
        if(!$stmt){
            $this->errno = self::WRONG_FIELD;
            $this->error['msg'] = '插入sql失败,没有对应的字段名或方法名';
            $return = false;
            goto quit_function;
        }
        $data_1 = reset($insert_data);
        unset($insert_data[key($insert_data)]);
//\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,$type_of_val.' '.$data_1.' '.var_export(array_values($data),true),'debug');
        $stmt->bind_param($type_of_val,$data_1,...array_values($insert_data));      //绑定参数
        $stmt->execute();                              //执行sql
        if($stmt->affected_rows<=0){    //执行插入sql失败
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'数据库插入失败:'.$sql."msg:$stmt->error",'error');
            $this->errno = $stmt->errno;
            $this->error['msg'] = $stmt->error;
            $stmt->close();
            $return = false;
            goto quit_function;
        }else{//成功
            $keyid = $stmt->insert_id;
            $stmt->close();
            $return = $keyid;
            goto quit_function;
        }
/***************************/
quit_function://统一退出方法处理
        if($this->errno){
            $this->conn->rollback();
        }else{
            $this->conn->commit();
        }
        $this->conn->autocommit(true);
        return $return??false;
    }

    /**
     * @param array $data
     * @return bool|mixed
     */
    protected function update(array $data){
        $this->conn->autocommit(false);
        if(!isset($data['keyid'])){
            $this->errno = self::NO_KEYID;
            $this->error['msg'] = '缺少主键...';
            $return = false;
            goto quit_function;
        }elseif(count($data)<=1){
            $this->errno = self::NO_UPDATE_DATA;
            $this->error['msg'] = '缺少更新数据!';
            $return = false;
            goto quit_function;
        }
        $update_data = $data;
        $keyid = $data['keyid'];
        unset($update_data['keyid']);
        $res = $this->_quickSelects($this->tablename,['keyid'=>$keyid],'keyid');
        if($res===false or empty($res)){
            $this->errno = self::WRONG_KEYID;
            $this->error['msg'] = '更新不存在的数据!';
            $return = false;
            goto quit_function;
        }

        $kv_sql = '';
        $type_of_val = '';
        foreach ($update_data as $key=>$val) {
            if (is_string($val)) {
                $type_of_val .='s';
                $kv_sql .= ",$key = ?";
            } elseif (is_int($val)) {
                $type_of_val .='s';
                $kv_sql .= ",$key = ?";
            } elseif (is_float($val)) {
                $type_of_val .='s';
                $kv_sql .= ",$key = ?";
            } elseif (is_null($val)) {
                $kv_sql .= ",$key = null";
                unset($update_data[$key]);
            } elseif (is_object($val) and get_class($val)==='model\SpecialDataType'){
                switch ($val->type){
                    case SpecialDataType::TYPE_FOREIGN_KEY:
                        $foreign_id = $val->option;
                        $foreign_table = $val->extra??substr($key,0,-3);
                        $res = $this->_quickSelects($foreign_table,['keyid'=>$foreign_id]);
                        if($res === false){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"未知错误,可能表名 $foreign_table 不存在");
                            $this->errno = self::UNKNOW_ERROR;  //暂时未归类,如果执行到这一行肯定代码有问题
                            $this->error['msg'] = '未知错误';
                            $return = false;
                            goto quit_function;
                        }
                        if(empty($res)){
                            $this->errno = self::WRONG_FOREIGN_KEY;
                            $this->error['msg'] = '不存在的外键值!';
                            $return = false;
                            goto quit_function;
                        }
                        $update_data[$key] = $val->option;
                        $type_of_val .='s';
                        $kv_sql .= ",$key = ?";
                        break;
                    case SpecialDataType::TYPE_UNIQUE:
                        $unique_value = $val->option;
                        $res = $this->_quickSelects($this->tablename, ["$key" => $unique_value],'keyid');
                        if ($res === false) {
                            \DebugLog::get_global_log()->dbg(__FILE__, __LINE__, "更新唯一字段出错. \$key=$key,\$val=$val->option", 'error');
                            $this->errno = self::UNKNOW_ERROR;  //暂时未归类,如果执行到这一行肯定代码有问题
                            $this->error['msg'] = '未知错误';
                            $return = false;
                            goto quit_function;
                        }
                        if (!empty($res) and $res[0]['keyid']!=$keyid) {
                            $this->errno = self::NOT_UNIQUE;
                            $this->error = ['msg'=>"$key 已存在,无法修改",'error_field'=>$key];
                            $return = false;
                            goto quit_function;
                        }
                        $update_data[$key] = $val->option;
                        $type_of_val .= 's';
                        $kv_sql .= ",$key = ?";
                        break;
                    case SpecialDataType::TYPE_FUNCTION:
                        $function_name = $val->option;
                        $kv_sql .= ",$key = $function_name";
                        unset($update_data[$key]);
                        break;

                    default:
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,'更新数据配置type错误:'.var_export($val,true),'error');
                        $this->errno = self::WRONG_VALUE;
                        $this->error['msg'] = "更新数据'$key'字段自定义类型配置错误";
                        $return = false;
                        goto quit_function;
                }
            }
        }
        $kv_sql = substr($kv_sql,1);
        $type_of_val .='s';
        /** @noinspection SqlResolve */
        $sql = "update $this->tablename set $kv_sql where keyid=?";
        $stmt = $this->conn->prepare($sql);
        if(!$stmt){
            $this->errno = self::WRONG_FIELD;
            $this->error['msg'] = '更新sql失败,没有对应的字段名或方法名';
            $return = false;
            goto quit_function;
        }
        $data_1 = reset($update_data);
        unset($update_data[key($update_data)]);
        $update_data[]=$keyid;
        $stmt->bind_param($type_of_val,$data_1,...array_values($update_data));
        $stmt->execute();
        if($stmt->affected_rows<=0 and $stmt->errno!==0){
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"数据库更新失败:$sql msg: $stmt->error",'error');
            $this->errno = $stmt->errno;
            $this->error['msg'] = $stmt->error;
            $stmt->close();
            $return = false;
            goto quit_function;
        } else {
            $stmt->close();
            $return = (int)$keyid;
            goto quit_function;

        }

quit_function:
        if($this->errno){
            $this->conn->rollback();
        }else{
            $this->conn->commit();
        }
        $this->conn->autocommit(true);
        return $return??false;
    }

//    /**
//     * @author 季煦阳 YoungxuJi@qq.com
//     * @param $value
//     * @param int $min
//     * @param int $max
//     * @return bool
//     */
//    public static function checkAz09_($value, $min = 0, $max = -1){
//        if(!is_string($value)){
//            return false;
//        }
//        $len = strlen($value);
//        if($len<$min){
//            return false;
//        }
//        if($max>=0 and $len>$max){
//            return false;
//        }
//        if(preg_match('/^[a-zA-Z0-9_]*$/',$value)<1){
//            return false;
//        }
//        return true;
//    }
//
//    /**
//     * @author 季煦阳 YoungxuJi@qq.com
//     * @param $value
//     * @return bool
//     */
//    public static function checkKeyid($value){
//        if(!is_numeric($value)){
//            return false;
//        }
//        if(preg_match('/^[1-9][0-9]{0,10}$/',$value)<1){
//            return false;
//        }
//        return true;
//    }

    public function __call($name, $arguments)
    {
        if(1==preg_match('/^get[A-Z]+[a-z0-9_]*ByForeignKey$/',$name)){
            $foreign_tablename = strtolower(substr($name,3,-12));
            $foreign_classname = '\model\table\\'.substr($name,3,-12);
            if(!class_exists($foreign_classname)){
                goto boom;
            }
            $foreign_keyname = $arguments[0]??$foreign_tablename.'_id';
            if(empty($this->data[$foreign_keyname])){
                return new $foreign_classname();
            }elseif($this->$foreign_tablename===null) {
                $this->$foreign_tablename = new $foreign_classname();
                $this->$foreign_tablename->select($this->data[$foreign_keyname]);
            }
            return $this->$foreign_tablename;
        }else {
            goto boom;
        }
boom://自爆程序启动!
\DebugLog::get_global_log()->dbg(__FILE__,__LINE__,"如果你看到了这行字,表示代码运行到这里已经不知所措该干嘛了!一定是有煞笔在哪里错误的调用了此方法: $name !",'error');
        echo '如果你看到了这行字,表示代码运行到这里已经不知所措该干嘛了!一定是有煞笔在哪里错误的调用了此方法!';
        define('_ERROR_STATUS_CODE',500);
        die();
    }
}