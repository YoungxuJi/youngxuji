<?php
/**
 * Created by PhpStorm.
 * User: youngxuji
 * Date: 18.7.26
 * Time: 11:56
 */

namespace model;


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