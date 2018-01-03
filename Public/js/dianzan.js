(function ($) {

    $.fn.praise = function (options) {

        var defaults = {

            obj: null, //jq对象，针对哪个对象使用这个tipsBox函数
            str: "+1", //字符串，要显示的内容;也可以传一段html，如: "<b style='font-family:Microsoft YaHei;'>哈哈</b>"
            startSize: "10px", //动画开始的文字大小
            endSize: "30px", //动画结束的文字大小
            interval: 600, //文字动画时间间隔
            color: "red", //文字颜色
            callback: function () {
            } //回调函数
        };

        var opt = $.extend(defaults, options); //合并参数
        $("body").append("<span class='num'>" + opt.str + "</span>");

        var box = $(".num");
        var left = opt.obj.offset().left + opt.obj.width() / 2; //span btn左侧距离加上自身宽度的一半
        var top = opt.obj.offset().top - opt.obj.height();//顶部距离减去自身的高度

        box.css({

            "position": "absolute",
            "left": left + "px",
            "top": top + "px",
            "z-index": 9999,
            "font-size": opt.startSize,
            "line-height": opt.endSize,
            "color": opt.color

        });

        box.animate({

            "font-size": opt.endSize,
            "opacity": "0",
            "top": top - parseInt(opt.endSize) + "px"

        }, opt.interval, function () {

            box.remove();
            opt.callback();

        });

    }

})(jQuery);

//点赞图标恢复原样
function niceIn(prop) {

    prop.find('.praisenum').addClass('niceIn').css("color", "red");
    setTimeout(function () {
        prop.find('.praisenum').css("color", "#45BCF9").removeClass('niceIn');

    }, 1000);

};

// 用法：在需要用到点赞插件的页面中引入jquery.js、
// 以及这个插件.js，在$（function(){}）中给"[object Object]"注册click事件即可。


//点赞特效+Ajax统计点赞数量

pariseShow: function () {

    //使用自定义的点赞特效插件,在zynblog.js前要先引入这个插件

    //jquery给暂未生成的标签绑定事件要用on('事件','对象','事件句柄')

    jQuery(document).on("click", ".praisebtn", function (e) {

        e.preventDefault();

        //获取被点赞文章的id praise-flag:0没攒过，1：赞过了

        //页面刚生成时，可以从库中确定该用户是否点赞，并为praise-flag属性赋初值

        //这里没必要那么严谨，所以初值均为1，(顶多是再在cookie中给个标记)

        var praiseFlag = jQuery(this).children('a').attr('praise-flag');

        var praiseArtId = jQuery(this).children('a').attr('data-id');


        //1. 如果没赞过

        if (praiseFlag == 0) {

            var curPraise = jQuery(this).children('a');

            curPraise.attr('praise-flag', "1");//先把点赞标识的属性值设为1

            jQuery(this).praise({

                obj: jQuery(this),

                str: "+1",

                callback: function () {

                    jQuery.post("/Archives/PraiseStatic", {"artId": praiseArtId}, function (data) {

                        if (data.Status == 1) {
                            var praisecount = parseInt(curPraise.text().match(/\d+/));
                            curPraise.text(curPraise.text().replace(praisecount, praisecount + 1));
                        } else if (data.Status == 2) {
                            alert(data.Message);
                        } else if (data.Status == 0) {
                            alert(data.Message);
                        }

                    });

                }

            });

            niceIn(jQuery(this));

        } else if (praiseFlag == 1) {

            //2. 如果已经已赞
            jQuery("body").append("<span class='praisetip'>您已赞过~</span>");

            var tipbox = jQuery(".praisetip");
            var left = jQuery(this).offset().left;
            var top = jQuery(this).offset().top + jQuery(this).height();

            tipbox.css({
                "position": "absolute",
                "left": left + "px",
                "top": top + "px",
                "z-index": 9999,
                "font-size": "12px",
                "line-height": "13px",
                "color": "red"
            });

            tipbox.animate({
                "opacity": "0"
            }, 1200, function () {
                tipbox.remove();
            });

        }

    });

}