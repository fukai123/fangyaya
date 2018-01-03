layui.use(['layer', 'form', 'element'], function () {
    var layer = layui.layer
        , form = layui.form()
        , element = layui.element()

});


function check_phone() {
    var phone2 = document.getElementById('phone').value;
    if (phone2.match(/^1\d{10}$/) != null) {
        //  layer.tips('手机号格式错误', '#teacherphone', {tips: ['2', '#FF0000']}); 
        document.getElementById('p').className = 'layui-btn layui-btn-primary';
    } else {
        document.getElementById('p').className = 'layui-btn layui-btn-disabled';
        layer.tips('手机号码格式不正确', '#phone', {
            tips: [2, '#1AA094']
        });
        return false;
    }
}
$(function () {

        // $('#phone').focus();
        $('#phone').blur(function () {
            if($('#phone').val()==''){
                layer.tips('手机号不能为空', '#phone', {
                    tips: [2, '#1AA094']
                });
                return false;
            }
             check_phone();
        });
        //检测手机号合法
        // $('#phone').keyup(function () {
        //     check_phone();
        // });
        //切换验证码
        $('#vertify').click(function () {
            var ran = Math.random();
            $(this).attr('src', '__CONTROLLER__/getVertify/rand/' + ran + '');
        });
//            获取短信验证码
        $('#p').click(function () {
            var pc = document.getElementById('p');
            if (pc.className == 'layui-btn layui-btn-primary') {
                //                alert('可以使用');  

                $.ajax({
                    url: '/home/login/getPhone',
                    type: 'post',
                    data: {'phone': $('#phone').val()},
                    dataType: 'json',
                    success: function (data) {
                        if (data.state == 0) {

                            layer.tips(data.msg, '#p', {
                                tips: [2, '#1AA094']
                            });
                        } else {

                            layer.tips(data.msg, '#p', {
                                tips: [2, '#1AA094']
                            });

                        }
                    }
                })
                pc.className = 'layui-btn layui-btn-disabled';
                var t = 60;
                var stop = setInterval(function () {
                    document.getElementById('p').innerText = '短信已发送,' + t;
                    t--;
                    if (t == 0) {
                        clearInterval(stop);
                        pc.className = 'layui-btn layui-btn-primary';
                        pc.innerText = '获取动态码';

                    }
                }, 900);
            } else {
            }
        });
//            点击注册按钮
        $('#btnRegister').click(function () {
            if ($('#phone').val() == '') {
                layer.tips('手机号不能为空', '#phone', {
                    tips: [2, '#1AA094']
                });
                $('#phone').focus();
                return false;
            }
            else {
                if ($('#phone').val().match(/^1\d{10}$/) == null) {
                    layer.tips('手机号格式不正确', '#phone', {
                        tips: [2, '#1AA094']
                    });
                    $('#phone').focus();
                    return false;
                }
            }

            if ($('#phoneCode').val() == '') {
                layer.tips('请输入短信验证码', '#phoneCode', {
                    tips: [2, '#1AA094']
                });
                $('#phoneCode').focus();
                return false;
            }


            if ($('#pwd').val() == '') {
                layer.tips('请输入密码', '#pwd', {
                    tips: [2, '#1AA094']
                });
                return false;
            }
            if ($('#vertiCode').val() == '') {
                layer.tips('请输入图片中的验证码', '#vertiCode', {
                    tips: [2, '#1AA094']
                });
                return false;
            }


            $.ajax({
                url: '/home/login/register',
                type: 'post',
                data: {
                    'phone': $('#phone').val(),
                    'pwd': $('#pwd').val(),
                    'phoneCode': $('#phoneCode').val(),
                    'vertiCode': $('#vertiCode').val()
                },
                dataType: 'json',
                success: function (data) {
                    if (data.state == 1) {
                        layer.msg('注册成功，正在为您跳转...', {
                            icon: 1,
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        }, function () {
                            window.location.href = '/home/index/index';
                        });


                    }
                    else {
                        layer.tips(data.msg, '#' + data.remark, {
                            tips: [2, '#1AA094']
                        });
                        return false;
                    }

                }

            });


        });

    }
);
