<?php
/**
 * Created by PhpStorm.
 * User: 35906
 * Date: 18.6.15
 * Time: 14:02
 */
require_once __DIR__.'/../../myfolder/core/require.php';
//set_time_limit(0);//设置PHP超时时间
//$imagesURLArray = [
//'https://images-na.ssl-images-amazon.com/images/I/61YCBlMEePL._SX569_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/610O2C1tqUL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71u2tRl5jOL._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71bg7U28FCL._SY355_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81TnMcGIr6L._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71g7DeMM0SL._UY606_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71uGPPhz%2BJL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71gxF-3nuDL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81jDH9Lqi8L._SX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/41jkLesxDFL._UX425_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81DjPi8C3GL._UX569_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71tNBJfc1yL._UY879_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/41cgmZiR8uL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81ht3kc8QAL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81yUPlYrijL._SY355_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/512yOnnBugL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81kBcPv4zpL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71jRXqjNcdL._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71i1AI4n9vL._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/51kOzGjIonL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/614WOB7MhDL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81JG5XHWaeL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61sC7IYnS9L._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61NGHfRF27L._UY445_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71vFXl1Yc4L._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71onKHLYuKL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81Tv5TLbD3L._SY879_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81cj4fzDlkL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81E4oKPgNBL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81ijv1dZvxL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81gZ2pUUNJL._SX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/619hPPH-FzL._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/714EwdoP35L._UY741_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/617Ow-KGwkL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71uY8o1oa6L._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81kyuOrOQTL._SX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91EXgnspYvL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71h3%2BjSCkWL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71scKJACfSL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/718IJl8lSOL._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/611RzBr2GmL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61VwbTNz7mL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71MQQHnA4EL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/612kdEnAuTL._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81JG5XHWaeL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61OOqJtr52L._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61xhP%2B8iGdL._SX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81%2BjAGkeX9L._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61hKxW1iUtL._UY741_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81wvAOXjWAL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61ydCPr5GyL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61tARv3UzOL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91jknQKWPrL._UY606_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81ieckEAtDL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91uVpZasHxL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91KypnT0c4L._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91aZGaWkGRL._UY741_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/711nKMCbXSL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81NA%2BxfvWVL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71l-J4Q1xML._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81dPKvgyc4L._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61PVKy6O9pL._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/91Q8ueEqmjL._UX425_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/6155AZ2jomL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71YSg2qEkGL._UX569_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61ngovKTjbL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71AjoA2tWdL._UY445_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/612WnJfn67L._SX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81EYG8JBHZL._UY879_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81cVAgq7CBL._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/710%2BpJ3QIZL._UY606_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71gbpjoaTxL._UY445_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61gr0OdM4nL._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61wh7yjmkdL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71hdAG3nSzL._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/819H-7r4OSL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71m4nHlC1GL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/51OKldqfOBL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81HmXO8b2iL._UY550_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81af7fpZCrL._SX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71DXxUtaSDL._UX569_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71b%2B0Wrsw1L._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71jRV%2BUzFPL._UY606_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61ugFSjCdIL._UY445_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81raxfZKiNL._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81HtxVu6-%2BL._UX569_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71o4WPpLrGL._UY606_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71HGcgrterL._UX342_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71PPU-E0BaL._UX425_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61EaIMTHqkL._SX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/615cNXx0OLL._UX679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71ouOJwHGuL._UX385_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/81RcP192a3L._SY879_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71wnicHZFDL._SY450_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/71yGX2UTYAL._UX522_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61pIGaPDM-L._UY679_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/51517YH1CIL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/818In2kEeNL._UX466_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/61nSX0oz3hL._UX425_.jpg',
//'https://images-na.ssl-images-amazon.com/images/I/7158MT75jRL._UX522_.jpg',
//];
//$imagesURLArray = array_unique($imagesURLArray );
//
//foreach($imagesURLArray as $imagesURL) {
//    echo $imagesURL;
//    echo "
//";
//    file_put_contents(basename($imagesURL), file_get_contents($imagesURL));
//}
?>
<!DOCTYPE HTML>
<html>
<head>
    <script src="<?php echo \GLOBAL_CONFIG\JQUERY_ONLINE_PATH?>"></script>
    <script >window.jQuery || document.write('<script type="text/javascript" src="../assets/js/jquery-latest.js"><\/script>')</script>
</head>
<body>
<textarea title="url地址" id="input" ></textarea>
<button id="button" onclick="clickBut()">点击</button>
<div id="div"></div>
<script>
    div = $('#div');
    input = $('#input');
    function clickBut(){
        let url_list = $(input).val();
        let url_arr = url_list.split(/[\n,]/g);
        for(url in url_arr){
            if(!url_arr.hasOwnProperty(url)){
                return
            }
            node = $('<div></div>').html('<img src="'+url_arr[url]+'"><p>'+url_arr[url]+'</p>');
             $(node).appendTo(div);
            // node1 = $('<iframe src="'+url_arr[url]+'" width="100%" height="500px"></iframe>');
            // node2 = $('<p>'+url_arr[url]+'</p>');
            // $(node1).appendTo(div);
            // $(node2).appendTo(div);
        }
    }
</script>
</body>
</html>
