<?php require_once __DIR__ . '/../../myfolder/core/require.php'; ?>
<section class="probootstrap-section">
    <div class="container">
        <div class="row">
            <div id="left_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div id="mid_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            <div id="right_list" class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
        </div>
    </div>
</section>
<?php
ob_start();
?>
<script>
    let list = [$('#left_list'),$('#mid_list'),$('#right_list')];

    let dataSenter = new DataSender('', '<?php echo \GLOBAL_CONFIG\URL_ROOTPATH?>msg_board/board_data.json',
        function (returnData) {
            // console.log(returnData);
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
                    $(list[0]).append(board_0);
                    height[0]+=board_1[0].offsetHeight;
                    board_arr[0].push([board_0,board_1[0].offsetHeight]);
                }else if(j<len*2/3){
                    $(list[1]).append(board_0);
                    height[1]+=board_1[0].offsetHeight;
                    board_arr[1].push([board_0,board_1[0].offsetHeight]);
                }else {
                    $(list[2]).append(board_0);
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
                $(list[min]).append(max_list_last_board[0]);
            }
            // console.log(board_arr);
            // aaa = board_arr;
            contentWayPoint();
        });

    dataSenter.sent();

</script>
<?php
$g_after_script[] = ob_get_contents();
ob_end_clean();
