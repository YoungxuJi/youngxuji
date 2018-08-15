<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.7
 * Time: 22:10
 */



require __DIR__.'/../../myfolder/core/require.php';

$res = \pubfun\VC::isKeyid(9999999999);


echo /** @lang text */
"<pre>";
    var_dump($res);
echo "</pre>";