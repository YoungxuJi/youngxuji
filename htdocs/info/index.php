<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.15
 * Time: 1:16
 */
require_once __DIR__.'/../../myfolder/core/require.php';
$title = 'YoungxuJi';
$text = '这里啥都没有';
$des = '我也不知道为什么会这样';
if(isset($_GET['type'])){
    switch ($_GET['type']){
        case 'twitter':
            $title = '推特账号 - '.$title;
            $text = '我没有Twitter账号.';
            $des = '抱歉!';
            break;
        case 'facebook':
            $title = '脸书账号 - '.$title;
            $text = '我也没有Facebook账号.';
            $des = '真的抱歉!';
            break;
        case 'instagram':
            $title = 'ins - '.$title;
            $text = '我不知道这个图标是什么社交账号.';
            $des = '其实我知道,但我不知道它中文名是什么.';
            break;
        case 'aboutus':
            $title = '关于我们 - '.$title;
            $text = '我还没想好说点什么.';
            $des = '以后再说吧.';
            break;
        default:
    }
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title><?php echo $title;?></title>
    <?php
    include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_link.php';
    ?>

</head>
<body>
<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/header.php';
?>
<section class="probootstrap-section probootstrap-bg" style="background-image: url(../assets/img/slider_2.jpg);">
    <div class="container text-center">
        <h2 class="heading probootstrap-animate" data-animate-effect="fadeIn"><?php echo $text;?></h2>
        <p class="sub-heading probootstrap-animate" data-animate-effect="fadeIn"><?php echo $des;?></p>
    </div>
</section>
<?php
include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/footer.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/top_but.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'inc/pub_script.php';
?>
</body>
</html>
