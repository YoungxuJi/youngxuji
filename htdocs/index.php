<?php
require_once __DIR__.'/../myfolder/core/require.php';
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>YoungxuJi</title>
    <?php
    include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_link.php';
    ?>
</head>
<body>


<!--加载动画-->
<!--<div class="probootstrap-loader"></div>-->

<?php
define('HEADER_ACTIVE',0);
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/header.php';
?>
<section class="probootstrap-slider flexslider">
    <div class="probootstrap-text-intro">
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="probootstrap-slider-text text-center" style="margin-top: 180px">
                        <h1 class="probootstrap-heading probootstrap-animate mb20" style="line-height:140px;" data-animate-effect="fadeIn">What should I write here?</h1>
                        <div class="probootstrap-animate probootstrap-sub-wrap mb30" data-animate-effect="fadeIn">首页随便弄弄先,我也不知道要放什么</div>
                        <p class="probootstrap-animate" data-animate-effect="fadeIn"><a href="#"
                                                                                        class="btn btn-ghost btn-ghost-white">Get
                                This</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <ul class="slides">
        <li style="background-image: url(assets/img/slider_1.jpg);"></li>
        <li style="background-image: url(assets/img/slider_2.jpg);"></li>
    </ul>
</section>

<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'msg_board/msg_board.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/footer.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/top_but.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_script.php';

if (!empty($g_after_script)) {
    foreach ($g_after_script as $script){
        echo $script;
    }
}
?>

</body>
</html>