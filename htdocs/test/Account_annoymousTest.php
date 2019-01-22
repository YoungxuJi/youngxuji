<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.7
 * Time: 22:10
 */

use model\table\Account_annoymous;

require __DIR__.'/../../myfolder/core/require.php';

$tab = new Account_annoymous();

//$data = [
//    'username'=>'985s334',
//    'keyid'=>99.0,
//    'password'=>'s33333',
//    'nickname'=>'',
//    'auth_id'=>'1',
//    'last_session'=>'35634567373465',
//    'last_activetime'=>'',
//    'update_time'=>'',
//    'create_time'=>'',
//    'other'=>'',
//];
//$res = $tab->update($data);
//
//$tab_simple = [$tab->errno,$tab->error];

$r = $tab->update(['keyid'=>7,'cookie_id'=>'test_5',/*'last_activetime'=>new \model\SpecialDataType(\model\SpecialDataType::TYPE_FUNCTION,'current_timestamp'),*/'last_ip'=>'test_ip']);
//$r = $tab->insert(['cookie_id'=>'test_2','last_activetime'=>null,'']);

echo /** @lang text */
"<pre>";
    var_dump($r,$tab);
echo "</pre>";