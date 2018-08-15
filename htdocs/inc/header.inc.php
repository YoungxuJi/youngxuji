<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.13
 * Time: 18:46
 */
require_once __DIR__ . '/../../myfolder/core/require.php';
/*************右上角用户信息显示**************/
$loginDeal = \pubfun\LoginDeal::getLoginDeal();
$login_url = \GLOBAL_CONFIG\URL_ROOTPATH.'login';
$reg_url = \GLOBAL_CONFIG\URL_ROOTPATH.'register';
if(empty($loginDeal->get_account_id())){
    $login_html = <<<eof
        <div class="header-login">
            <a href="$login_url">登录</a>
            <div class="menu-chilrden">
                <div class="menu-child">
                    <a href="$reg_url">快速注册</a>
                </div>
            </div>
        </div>
eof;

}else{
    $account = $loginDeal->get_account();
    $nickName = htmlspecialchars($account->data['nickname']);
    $login_html = <<<eof
        <div class="header-login">
            <a href="$login_url">{$nickName}</a>
            <div class="menu-chilrden">
                <div class="menu-child">
                    <a href="javascript:logout()">退出登录</a>
                </div>
            </div>
        </div>
eof;

}
/*************激活状态**************/
if(!defined('HEADER_ACTIVE')){
    define('HEADER_ACTIVE',-1);
}
$header_list = [
    [
        'herf' => '/',
        'text' => '首页',
    ],
    [
        'herf' => '#',
        'text' => '第二页',
        'children' => [
            [
                'herf' => '#',
                'text' => '第二页子内容'
            ],
            [
                'herf' => '#',
                'text' => '随便写的'
            ],
        ],
    ],
    [
        'herf' => '#',
        'text' => '第三页',
        'children' => [
            [
                'herf' => '#',
                'text' => '第二页子内容'
            ],
            [
                'herf' => '#',
                'text' => '随便写的'
            ],
        ],
    ],
    [
        'herf' => '#',
        'text' => '第四页',
    ],
];
$li_html = '';
foreach ($header_list as $index=>$info_arr){
    $class_html = $index===HEADER_ACTIVE?' class="active" ':'';
    $children_html = '';
    if(!empty($info_arr['children'])) {
        $children_html .= /** @lang text */
            '<div class= "menu-chilrden">';
        foreach ($info_arr['children'] as $children_info) {
            $children_html .= <<<eof
<div class="menu-child"><a href="{$children_info['herf']}">{$children_info['text']}</a></div>
eof;
        }
        $children_html .= '</div>';
    }
    $li_html .= <<<eof
<li{$class_html}><a href="{$info_arr['herf']}">{$info_arr['text']}</a>$children_html</li>

eof;

}
?>
<!-- START: header -->
<header role="banner" tabindex="1" class="menu-header  probootstrap-header">
    <div class="container containner-nav">
        <a href="/" class="probootstrap-logo">Youngxuji<span>.</span></a>
        <nav role="navigation" class="probootstrap-nav menu-nav">
            <ul class="probootstrap-main-nav">
                <?php
                echo $li_html;
                ?>
            </ul>
        </nav>
        <?php
        echo $login_html;
        ?>
    </div>
</header>
<!--suppress JSUnusedLocalSymbols -->
<script>
    function logout() {
        $.ajax({
            url:'<?php echo \GLOBAL_CONFIG\URL_ROOTPATH?>'+'logout.ajax',
            success:function () {
                location.reload();
            }
        })
    }
</script>
<!-- END: header -->