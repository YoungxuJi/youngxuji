<?php
require_once __DIR__ . '/../../myfolder/core/require.php';
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8">
    <title>YoungxuJi</title>
    <?php
    include \GLOBAL_CONFIG\WEB_ROOTPATH . 'pub_link.php';
    ?>
</head>
<body>


<!--加载动画-->
<!--<div class="probootstrap-loader"></div>-->

<?php
define('HEADER_ACTIVE',0);
//include \GLOBAL_CONFIG\WEB_ROOTPATH . 'header.php';
?>


<?php
//include \GLOBAL_CONFIG\WEB_ROOTPATH . 'footer.php';

//include \GLOBAL_CONFIG\WEB_ROOTPATH . 'top_but.php';

include \GLOBAL_CONFIG\WEB_ROOTPATH . 'pub_script.php';
?>
<script>
    dataSenter = new DataSender('','comTest.php',function (returnData) {
        alert('略略略');
    });
    dataSenter.sent();
</script>
</body>
</html>