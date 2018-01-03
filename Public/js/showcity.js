/**
 * Created by fukai3 on 2017-2-8.
 */
$(document).ready(function() {

    $(function() {
        $(".m-selectcity").hover(function() {
            $(".area_school").show(200);
        }, function() {
            $(".area_school").hide(200  );
        });
    });

    $(function() {
        $(".bangz").hover(function() {
            $(".head_r .bangz .blank").show();
            $(".head_r .bangz .d_line").show();
            $(".head_r .bangz .list").show();
            $(".bangz em").addClass('arrow');
        }, function() {
            $(".head_r .bangz .blank").hide();
            $(".head_r .bangz .d_line").hide();
            $(".head_r .bangz .list").hide();
            $(".bangz em").removeClass('arrow');
        });
    });

    $(function(){
        $('.input_txt').focus(function(){
            if($(this).val()=='请输入…'){
                $(this).val('');
            }
        }).blur(function(){
            if($(this).val().trim()==''){
                $(this).val('请输入…');
            }
        });
    });

    $(function() {
        var H = $(".topdiv").height();
        $(window).scroll(function(event) {
            var num = $(document).scrollTop();
            if(num >= H){
                $("#head_logo").css({"position":"fixed","top":0,"width":"100%","background-color":"#05a89a"});
                $("#head_logo .fbBtn").css({"background-color":"#fff","color":"#05a89a"});
                $("#head_logo .input_btn").css({"border":"1px solid #fff"});
            }else{
                $("#head_logo").css({"position":"static","background-color":"#fff"});
                $("#head_logo .fbBtn").css({"background-color":"#05a89a","color":"#fff"});
                $("#head_logo .input_btn").css({"border":"0"});
            }
        });
    });

});