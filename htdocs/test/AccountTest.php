<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.7.7
 * Time: 22:10
 */

use model\table\Account;

require __DIR__.'/../../myfolder/core/require.php';

$tab = new Account();

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

//$tab->select('90');
//$tab->updateLoginInfo(session_id());

$r = $tab->update([
    'keyid'=>90,
    'last_session'=>'test_04',
    'last_activetime'=>new \model\SpecialDataType(\model\SpecialDataType::TYPE_FUNCTION,'current_timestamp'),
    ]);
echo /** @lang text */
"<pre>";
    var_dump($r,$tab);
echo "</pre>";