<?php
/**
 * Created by PhpStorm.
 * User: youngxuji
 * Date: 18.7.26
 * Time: 11:56
 */

namespace model;

/**
 * Class SpecialDataType
 * @package model
 * <pre>
 * $stdClass->type (string)
 * $stdClass->option (mixed) 根据type确定数据类型//一般情况下是数据的值
 * $stdClass->extra (mixed) 额外配置项,使用待定
 *
 * type:TYPE_FOREIGN_KEY 外键
 * ->>option:string 外键值
 * [->>extra:string 关联数据表名,如果key以 $tablename+_id 格式命名可以不用配置此option]
 * For example:
 *  $data['auth_id'] = (object)[
 *      'type'=>TYPE_FOREIGN_KEY,
 *      'option'=>1
 *  ]
 *
 * type:TYPE_UNIQUE 唯一
 * ->>option:string 值
 * For example:
 * $data['username'] = (object)[
 *      'type'=>TYPE_UNIQUE,
 *      'option'=>'myusername'
 * ]
 * 如果重复,则
 * $this->error['error_field']=重复值的字段名称
 *
 * 调用函数,树
 * </pre>
 */
class SpecialDataType
{
    public $type;
    public $option;
    public $extra;


    /*************通用CURD操作属性配置**************/
    CONST TYPE_FOREIGN_KEY  = 0b1;      //关联外键
    CONST TYPE_UNIQUE       = 0B10;     //唯一索引
    CONST TYPE_FUNCTION     = 0b100;    //调用mysql方法
    const TYPE_TREE         = 0B1000;   //数结构

    public function __construct($type,$option=null,$extra=null)
    {
        $this->type = $type;
        $this->option = $option;
        $this->extra = $extra;
    }
}