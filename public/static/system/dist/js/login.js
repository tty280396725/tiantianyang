/**
 * login
 */
$(function(){
    //登陆
    $('body').off('click', '.login');
    $('body').on("click", '.login', function(event){
        var _this = $(this);
        _this.button('loading');
        var form = _this.closest('form');
        if(form.length){
            var ajax_option={
                dataType:'json',
                success:function(data){
                    if(data.err == '0'){
                        $.amaran({'message':data.msg});
                        var url = data.url;
                        setTimeout("window.location.href='"+url+"'",1000);
                        // window.location.href=url;
                    }else{
                        $.amaran({ 'message':data.msg, 'clearAll':true });
                        $('#code').click();
                        _this.button('reset');
                    }
                }
            }
            form.ajaxSubmit(ajax_option);
        }
    });
})