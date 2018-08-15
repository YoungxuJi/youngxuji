<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.16
 * Time: 23:43
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
    <title>登录</title>
    <?php
    include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_link.inc.php';
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
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/header.inc.php';
?>
<h1 class="login-h">登录YoungxuJi</h1>
<div class="log">
    <div class="content1">
        <h2>Some to tell you</h2>
        <p> But I don't know what to say</p>
    </div>
    <div class="content2">
        <h2>登录</h2>
        <form id="login_form" onsubmit="return false">
            <input type="text" name="username" title="用户名" placeholder="请输入用户名" >
            <input type="password" name="password" title="密码" placeholder="请输入密码" >
            <img id="vcimg" style="width: 160px;height:40px; display: block; margin-left: 15%; margin-top: 5%" src="../ajax/get_vercode_pic.php" onclick="reflushImg(this)">
            <input type="text" name="vercode" title="验证码" placeholder="请输入验证码" >
            <button type="submit" class="button-row" >登录</button>
        </form>
    </div>
    <div class="clear"></div>
</div>
<!--<div class="footer">-->
<!--    <p>Copyright &copy; 2016.Company name All rights reserved.More Templates <a href="http://www.cssmoban.com/" target="_blank" title="模板之家">模板之家</a> - Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a></p>-->
<!--</div>-->
<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/footer.inc.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/top_but.inc.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_script.inc.php';
?>
<script>
    function reflushImg(img) {
        $(img).attr('src','../get_vercode_pic.ajax?a='+Math.random());
    }
    form_e = $('#login_form');
    form_e[0].addEventListener('submit',login);
    function login() {
        let data = $(form_e).serializeArray();
/*        $.ajax({
            type:'POST',
            url:'../login.ajax',
            data:data,
            dataType:'json',
            success:function (data) {
                if(data['code']==='0000'){
                    history.back();
                }else {
                    alert('登录失败');
                }
            }
        })*/
        let dataSender =new DataSender(data,'../login.ajax',function () {
            if(document.referrer === ''){
                window.location.href = '<?php echo \GLOBAL_CONFIG\URL_ROOTPATH?>';
            }else {
                history.back();
            }
        },function(returndata){
            alert('操作失败,错误代码:"'+returndata['code']+'",错误提示:"'+returndata['message']+'"');
            console.log(returndata['data']);
            if(returndata['code']!==-1)reflushImg($('#vcimg'));
        });
        dataSender.sent();
    }


</script>
</body>
</html>
