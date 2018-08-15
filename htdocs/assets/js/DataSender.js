function DataSender(data,url,okfun=function (returndata) {
    console.log(returndata);
},elsefun = function (returndata) {
    alert('操作失败,错误代码:"'+returndata['code']+'",错误提示:"'+returndata['message']+'"');
    console.log(returndata['data']);
}) {
    this.ajax = {
        type:'POST',
        url:url,
        data:data,
        dataType:'json',
        success:function (returndata) {
            if(returndata['code']==='0000'){
                okfun(returndata);
            }else {
                elsefun(returndata);
            }
        },
        error:function (jqXHR) {
            let html=jqXHR['responseText'];
            let open = window.open("about:blank", "_blank");
            open.document.write(html);
            console.log(jqXHR['responseText']);
        }
    };
    this.sent = function () {
        $.ajax(this.ajax);
    }
}