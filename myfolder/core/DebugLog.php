<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 17.12.1
 * Time: 11:54
 */

class DebugLog
{
    const LOG_PATH = __DIR__.'/../log/';
    public $default_logfile_name;//默认日志文件名
    const ALL_LOG_NAME = 'all_log';//全局文件名(所以日志都会在此日志文件写入调试信息)

    static private $global_log;

    /**
     * DebugLog constructor.
     * @param string $default_log_name
     * 初始化日志类,设置默认日志文件名$this->default_logfile_name;
     */
    public function __construct($default_log_name='')
    {
//        ini_set('date.timezone','Asia/Shanghai');
        if(!file_exists(DebugLog::LOG_PATH)){//创建目录
            mkdir(DebugLog::LOG_PATH,0777);
        }
        if(!(empty($default_log_name)||($default_log_name===self::ALL_LOG_NAME))){
            $this->default_logfile_name = $default_log_name;
        }
        $this->dbg(__FILE__,__LINE__,'
        /*********************new Debuglog(\''.$default_log_name.'\')**********************/');
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param string $default_log_name
     * @return DebugLog
     * 获取单例模式的对象
     */
    static function get_global_log($default_log_name='')
    {
        if(empty(self::$global_log)){//未初始化单例对象
            self::$global_log = new DebugLog($default_log_name);
        }elseif(!empty($default_log_name)){//初始化全局对象并且$default_log_name非空
            if(empty(self::$global_log->default_logfile_name)){
                self::$global_log = new DebugLog($default_log_name);
            }else{
                $log =  DebugLog::get_global_log();
                $log->dbg(__FILE__,__LINE__,'当前全局日志文件"'.$log->default_logfile_name.'"已经设置,无法再设置"'.$default_log_name.'"!');
            }
        }
        return DebugLog::$global_log;
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param string $file_name
     * @return bool|resource
     * 要求在linux服务器中预先创建log文件夹并事先把权限设置好777.
     * log文件夹里的文件不能手动创建或者上传,必须是自动生成的(由wwwrun创建),否则会权限报错
     */
    private function safeOpenFile($file_name){
        $file_name = DebugLog::LOG_PATH.$file_name;
        $files = glob($file_name.'_??????????.log');
        $files_count = count($files);
        if(empty($files)||filesize($files[$files_count-1])>=1024*1024*50){
            $file_name .= '_'.date('ymdHi').'.log';
        }else{
            $file_name = $files[$files_count-1];
        }
        $fp = fopen($file_name,"a");
//            fclose($fp);
        chmod($file_name,0777);
        return $fp;
    }

    /**
     * @author 季煦阳 YoungxuJi@qq.com
     * @param $varfile
     * @param $varline
     * @param string|integer $word
     * @param string $file_name
     * 任何dbg都会在all_log.log里写入调试信息
     * 如果默认日志文件名$this->default_logfile_name存在,则在此日志文件写入调试信息
     * 如果入参$file_name非空,则在此日志文件写入调试信息
     *
     */
    public function dbg($varfile,$varline,$word='',$file_name = '') {
        $fp = $this->safeOpenFile(DebugLog::ALL_LOG_NAME);
        fwrite($fp,'

FILE:'.$varfile." LINE:".$varline." time：".strftime("%Y-%m-%d %H:%M:%S",time())." message:
".$word." ");
        fflush($fp);
        fclose($fp);
        if(!empty($this->default_logfile_name)){
            $fp = $this->safeOpenFile($this->default_logfile_name);
            fwrite($fp,'

FILE:'.$varfile." LINE:".$varline." time：".strftime("%Y-%m-%d %H:%M:%S",time())." message:
".$word." ");
            fflush($fp);
            fclose($fp);
        }
        if(!empty($file_name)){
            $fp = $this->safeOpenFile($file_name);
            fwrite($fp,'

FILE:'.$varfile." LINE:".$varline." time：".strftime("%Y-%m-%d %H:%M:%S",time())." message:
".$word." ");
            fflush($fp);
            fclose($fp);
        }

    }

}