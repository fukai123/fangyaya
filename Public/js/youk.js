/*大小导效果*/
$(document).ready(function() {
    $(".nav1 li").mouseover(function() {
        $(".nav1 li").eq($(this).index()).addClass("kl").siblings().removeClass("kl");
        $(".warp-pl-1").hide().eq($(this).index()).show();
    })
});
$(document).ready(function() {
    $(".sss").click(function() {
        $(".sss").eq($(this).index()).addClass("kl").siblings().removeClass("kl");
        $(".warp-pl-1").hide().eq($(this).index()).show();
    })
}); // JavaScript Document



$(".dropdown-menu2 li").mouseleave(function() {
    $(this).find("a").removeClass("maintainHover");

})
$(".dropdown-menu2 li").mouseenter(function() {
    $(this).find("a").addClass("maintainHover");

})

/*大导浮*/
$(function() {
    $(window).scroll(function() {
        if ($(window).scrollTop() >= 180) {
            $(".boxp-2 .dropdown-menu2").addClass("loi");
        } else {
            $(".boxp-2 .dropdown-menu2").removeClass("loi");
        }
    });
});


$(document).ready(function() {
    $("#owl-demo").owlCarousel({
        navigation: true,
        navigationText: ["上一集", "下一集"],
        pagination: false,


        autoHeight: true,
        transitionStyle: 'fade',


        <!-- autoPlay: 3000,-->自动轮播
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 3]

    });
	$("#owl-demo2").owlCarousel({
        navigation: true,
        navigationText: ["上一张", "下一张"],
        pagination: false,
		


        /*autoHeight: true,*/
        transitionStyle: 'fade',
		mouseDrag: false,
		touchDrag: false,

        <!-- autoPlay: 3000,-->自动轮播
        items: 3,
        itemsDesktop: [1199, 3],
        itemsDesktopSmall: [979, 3]

    });
});



/*取消媒，JS判*/
$(window).resize(function() {
    reTarget();
});
$(function() {
    reTarget();
})

function reTarget() {
    var _width = $(window).width();

    if (_width <= 1200) {
        $('.dropdown-menu2').css('display', 'none')
    } else {
        $('.dropdown-menu2').css('display', 'block')
    }
    if (_width <= 1200) {
        $('.jjs .col-md-4').css('{width', '100%')
    } else {
        $('.jjs .col-md-4').css('{width', 'auto')
    }
};





/*缩*/
/*$(function() {
    $(".more_list").click(function() {
        $(".more").attr("style", "height: auto; overflow: hidden;");
        $(this).hide();
        $(".less_list").show();
        $("#isShow").val("1");
    });
    $(".less_list").click(function() {
        $(".more").attr("style", "height: 286px; overflow: hidden;");
        $(this).hide();
        $(".more_list").show();
        $("#isShow").val("0");
    });
    var isShow = GetQueryString("isShow");
    if (isShow == 1) {
        $(".more_list").click();
    }
});*/

/*手导*/
function addShow(obj) {
    var isShow = $("#isShow").val();
    if (isShow == 1) {
        var href = $(obj).attr("href");
        $(obj).attr("href", href + "?isShow=" + isShow);
    }
}
window.onload = function() {
    var mySwiper1 = new Swiper('#header', {
        freeMode: true,
        slidesPerView: 'auto',
    });
    var mySwiper2 = new Swiper('#banner', {
        autoplay: 5000,
        visibilityFullFit: true,
        loop: true,
        pagination: '.pagination',

    })
}