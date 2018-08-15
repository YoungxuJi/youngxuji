<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.18
 * Time: 10:21
 */
require_once __DIR__.'/../../myfolder/core/require.php';

$ld = \pubfun\LoginDeal::getLoginDeal();
$ld->logout();
\pubfun\ReturnDeal::returnOK();