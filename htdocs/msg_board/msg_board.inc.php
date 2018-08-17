<?php require_once __DIR__ . '/../../myfolder/core/require.php';
 $lg = \pubfun\LoginDeal::getLoginDeal();
?>
<section class="probootstrap-section">
    <div class="container">
        <div class="row">
            <div id="left_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div id="mid_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div id="right_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        </div>
        <div class="row">
                <ul class="posts-nav">
                    <li class="previous"><a href="#" >←</a></li>
                    <li class="current" ><a contenteditable id="page_board" title="请输入非0整数页码,如输入负数表示倒数第几页." href="#" >3</a></li>
                    <li class="next"><a href="#">→</a></li>
                </ul>
        </div>
        <div class="row">
            <form class="probootstrap-form mb60" id="form_board" onsubmit="return false;">
                <div class="form-group">
                    <label for="message">发表留言</label>
                    <textarea cols="30" rows="10" class="form-control" id="message" name="message"></textarea>
                </div>
                <input type="checkbox" value="on" <?php if(empty($lg->get_account_id())){ ?>checked disabled <?php } ?> title="匿名发送">
                <label>匿名发送</label>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" id="submit_anonymous" name="submit" value="匿名发送">

                </div>

            </form>
        </div>
    </div>
</section>
<?php
ob_start();
?>
<script>
    /*************留言板**************/
    let board_list = [$('#left_list'),$('#mid_list'),$('#right_list')];
    let boardDataSenter = new DataSender('', '<?php echo \GLOBAL_CONFIG\URL_ROOTPATH?>msg_board/board_data.json',
        function (returnData) {
            //清空留言板
            $(board_list[0]).html("");
            $(board_list[1]).html("");
            $(board_list[2]).html("");
            let data = returnData['data'];
            let board_arr = [[],[],[]];
            let height=[0,0,0];
            for (let j = 0, len = data.length; j < len; j++) {
                let author = $('<h2 class="heading"></h2>');
                let content = $('<p></p>');
                let datetime = $('<time></time>');
                let board_0 = $('<div ></div>');
                let board_1 = $('<div class="probootstrap-animate service">');
                $(author).text(data[j].author);
                $(content).text(data[j].content);
                $(datetime).text(data[j].datetime);
                $(board_1).append(datetime);
                $(board_1).append(author);
                $(board_1).append(content);
                $(board_0).append(board_1);
                if(j<len/3) {
                    $(board_list[0]).append(board_0);
                    height[0]+=board_1[0].offsetHeight;
                    board_arr[0].push([board_0,board_1[0].offsetHeight]);
                }else if(j<len*2/3){
                    $(board_list[1]).append(board_0);
                    height[1]+=board_1[0].offsetHeight;
                    board_arr[1].push([board_0,board_1[0].offsetHeight]);
                }else {
                    $(board_list[2]).append(board_0);
                    height[2]+=board_1[0].offsetHeight;
                    board_arr[2].push([board_0,board_1[0].offsetHeight]);
                }
            }
            // console.log(height,board_arr);
            let getMax = function (height_arr) {
                let max = 0;
                if(height_arr[1]>height_arr[max]){max=1}
                if(height_arr[2]>height_arr[max]){max=2}
                return max;
            };
            let getMin = function (height_arr) {
                let min = 0;
                if(height_arr[1]<height_arr[min]){min=1}
                if(height_arr[2]<height_arr[min]){min=2}
                return min;
            };

            while (true){
                let max = getMax(height);
                let max_list_last_board = board_arr[max].pop();
                height[max]-=max_list_last_board[1];
                let min = getMin(height);
                if(max===min){break;}
                board_arr[min].push(max_list_last_board);
                height[min]+=max_list_last_board[1];
                $(max_list_last_board[0]).remove();
                $(board_list[min]).append(max_list_last_board[0]);
            }
            // console.log(board_arr);
            // aaa = board_arr;
            contentWayPoint();
        });
    boardDataSenter.sent();

    /**分页控件**/
    let page_board = $('#page_board');
    let contents = $(page_board).html();
    $(page_board).focus(function () {
        window.document.onkeydown = function (evt){
            evt = (evt) ? evt : window.event;
            if (evt.keyCode) {
                if(evt.keyCode ===13){
                    $(page_board).blur();
                }
            }
        };

    });
    $(page_board).blur(function() {
        if ((contents!==$(this).html())) {// 如果内容有修改
            if (/^-[1-9][0-9]*$/.test($(this).html())) {//满足页面要求
                console.log('输入：' + $(this).html());
                contents = $(this).html();
                //todo something
            } else {
                alert('请输入正确的页面格式!');
                $(this).html(contents);
            }
        }
    });

    /*************留言板提交**************/
    let form_e = $('#form_board');
    form_e[0].addEventListener('submit',sent_msg_board);
    let count_board = 0;
    function sent_msg_board() {
        if(++count_board<=2){
            alert('对不起,网络连接错误,请重试!');
        }else if(count_board<=3){
            alert('对不起,网络连...算了,我实话和你说吧,这功能我还没做,根本发不了的');
        }else {
            alert('别试了,按多少次都没用.要不你给我打点钱赞助下我马上能做好!')
        }
    }
</script>
<?php
$g_after_script[] = ob_get_contents();
ob_end_clean();
