<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.7
 * Time: 22:10
 */

use model\table\Access_log;

require __DIR__.'/../../myfolder/core/require.php';

$tab = new Access_log();

$data = [
    'sessionid'=>'123123',
    'ip'=>'123123',
    'url'=>'124354',
    'account_id'=>'22',
    'action'=>'224',
];
$res = $tab->insert($data);
echo /** @lang text */
"<pre>";
    var_dump($res,$tab);
echo "</pre>";