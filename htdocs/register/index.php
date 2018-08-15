<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.18
 * Time: 12:01
 */
require_once __DIR__.'/../../myfolder/core/require.php';
use \pubfun\LoginDeal;
$ld = LoginDeal::getLoginDeal();
if(!empty($ld->get_account_id())){//当前用户已登录
    $url = $_SERVER['HTTP_REFERER']??\GLOBAL_CONFIG\URL_ROOTPATH;
    header("Location: ".$url);
    exit;
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>注册</title>
    <?php
    include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_link.php';
    ?>
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,300,700' rel='stylesheet' type='text/css'>
    <!-- /font files  -->
    <!-- css files -->
    <link href="../assets/css/login.css" rel='stylesheet' type='text/css' media="all" />
    <!-- /css files -->
</head>
<body>
<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/header.php';
?>
<h1 class="login-h">注册YoungxuJi</h1>
<div class="log">
    <div class="content1">
        <h2>Some to tell you</h2>
        <p> But I don't know what to say</p>
    </div>
    <div class="content2">
        <h2>注册</h2>
        <form id="register_form" onsubmit="return false">
            <div>
                <label for="username">*用户名:</label>
                <input type="text" id="username" name="username" maxlength="32" required pattern="[0-9a-zA-Z_]{4,32}" title="字母和数字和'_'组成,4到32个字符"  placeholder="请输入用户名" >
            </div>
            <div>
                <label for="password">*密码:</label>
                <input type="password" id="password" name="password" onblur="checkPwAgain()" title="字母和数字和'_'组成,6到16个字符" maxlength="16" required pattern="[0-9a-zA-Z_]{6,16}" placeholder="请输入密码" >
            </div>
            <div>
                <label for="password_confirm">*确认密码:</label>
                <input type="password" id="password_confirm" onblur="checkPwAgain()" onkeyup="checkPwAgain()" required title="请再次输入密码" placeholder="请再次输入密码" >
            </div>
            <div>
                <label for="nickname">昵称</label>
                <input type="text" id="nickname" name="nickname" title="昵称" maxlength="32" onkeyup="this.value=this.value.replace(/\s+/g,'')" placeholder="请输入昵称">
            </div>
            <img id="vcimg" style="height:40px; display: block; margin-left: 30%; margin-top: 5%" src="../ajax/get_vercode_pic.php" onclick="reflushImg(this)">
            <div>
                <label for="vercode">*验证码</label>
                <input type="text" id="vercode" name="vercode" title="验证码" required placeholder="请输入验证码" >
            </div>
            <button type="submit" class="button-row">注册</button>
        </form>
    </div>
    <div class="clear"></div>
</div>
<!--<div class="footer">-->
<!--    <p>Copyright &copy; 2016.Company name All rights reserved.More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></p>-->
<!--</div>-->
<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/footer.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/top_but.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_script.php';
?>
<script>
    function reflushImg(img) {
        $(img).attr('src','../get_vercode_pic.ajax?a='+Math.random());
    }

    form_e = $('#register_form');
    form_e[0].addEventListener('submit',regitser);

    function regitser() {
        if(!checkPwAgain()){
            return;
        }
        let data = $(form_e).serializeArray();
        let dataSender = new DataSender(data,'../register.ajax',function () {
            alert('恭喜你,成功注册了一个没啥卵用的账号!');
            history.back();
            window.location.href = '../';
        },function(returndata){
            alert('操作失败,错误代码:"'+returndata['code']+'",错误提示:"'+returndata['message']+'"');
            console.log(returndata['data']);
            if(returndata['code']!==-1)reflushImg($('#vcimg'));
        });
        dataSender.sent();
    }

    function checkPwAgain(){
        let password = $('#password');
        let pw_con = $('#password_confirm');
        if($(password).val()!==$(pw_con).val()){
            pw_con[0].setCustomValidity('两次密码输入不一致');
            return false;
        }else {
            pw_con[0].setCustomValidity('');
            return true;
        }
    }

</script>
</body>
</html>
