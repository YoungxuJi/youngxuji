<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.8
 * Time: 18:59
 */
require __DIR__.'/../../myfolder/core/require.php';

$ch = curl_init('http://www.google.com/');
$res = curl_exec($ch);
var_dump($res);
curl_close($ch);
