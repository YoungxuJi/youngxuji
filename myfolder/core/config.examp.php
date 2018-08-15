<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.5.28
 * Time: 15:01
 */
namespace GLOBAL_CONFIG;
const CACHE_MAXAGE = 86400;  //浏览器缓存时间/s /默认1天
CONST SESSION_EXPIRE = 10080;//session有效期/min /默认7天
CONST SESSION_COOKIE = 604800;//session的cookie有效期 /默认7天

CONST JQUERY_ONLINE_PATH = 'http://code.jquery.com/jquery-latest.js'; //jq库cdn地址
CONST PROJ_ROOTPATH = __DIR__.'/../';                                 //项目文件地址
CONST WEB_ROOTPATH = __DIR__.'/../../htdocs/';                        //公共访问文件地址
CONST URL_ROOTPATH = '/';                                           //网址跟目录

namespace GLOBAL_CONFIG\DATABASE;
const HOST = '';
const USERNAME = '';
CONST PASSWORD = '';
CONST DBNAME = '';
