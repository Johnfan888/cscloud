<?php /* Smarty version 2.6.14, created on 2018-11-23 21:16:32
         compiled from register.html */ ?>
﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link type="text/css" rel="stylesheet" href="css/register.css" />
<script type="text/javascript" src="js/jquery.js"></script>
<title><?php echo $this->_tpl_vars['title']; ?>
 </title>
</head>
<body>
    <div class="header">
            <ul class="top-login">
			<li><a href="login.php">登录</a></li>
			<li><a href="register.php">注册</a></li>
			</ul>
    </div>
<div class="main">
    <div class="reg-title"><strong>注册CSCloud，拥有20G云硬盘！</strong></div>
        <div class="reg-form">
        <ul class="reg-tab">
            <li class="focus"><span class="mr-email">电子邮件注册</span></li>
	<!--   <li><a href="javascript:alert('暂未开放此功能!'); return false;"><span class="mr-mobile">手机号码注册</span></a></li> -->
        </ul>
        <form vail="1" id="signForm" action="" method="post" autocomplete="off">
            <dl>
                <dt>Email地址：</dt>
                <dd>
                    <input type="text" id="reg_email" name="reg[email]" class="text" maxlength="50" errortype="email" errmsgtype="2"   errmsg="请输入正确的邮箱地址" value="<?php echo $this->_tpl_vars['email']; ?>
">
                                <span class="form-hint form-err"><i></i>请输入正确的邮箱地址</span></dd>
                <dt>密码：</dt>
                <dd>
                    <input type="password" id="reg_password" name="reg[passwd]" class="text" maxlength="18" errortype="notempty" errmsgtype="2" errmsg="请输入6-18个字符的登录密码" minlen="6" onpaste="return false;" err="1">
                                <span class="form-hint form-err"><i></i>请输入6-18个字符的登录密码</span></dd>
                <dd class="form-text"><span><em>安全程度：</em><em class="passwd-level pwd-dangerous" id="js_passwordStrong"><i></i></em> <em id="js_passwordText"></em></span>
                    <em>建议您使用英文、数字混合的密码并增加长度。</em></dd>
                <dt>确认密码：</dt>
                <dd>
                    <input type="password" id="reg_passwordconfirm" name="reg[vpasswd]" class="text" maxlength="18" errortype="rate" rate="reg_password" errmsgtype="3" errmsg="两次输入不一致" onpaste="return false;">
                    <span class="form-hint-succ"></span></dd>
                <dt>验证码：</dt>
                <dd>
                    <input type="text" class="text code" id="reg_valicode" name="reg[code]" errortype="notempty" errmsgtype="3" errmsg="请输入验证码" style="width:70px;margin-right:10px;" maxlength="4">
                    <img id="js_code_img" width="100" height="33" src="authcode.php" onclick="$('#js_code_img').attr('src','authcode.php?date='+new Date().getTime());"> <em>看不清？<a href="javascript:;" onclick="$('#js_code_img').attr('src','authcode.php?date='+new Date().getTime());return false;">换一张</a> </em> <span class="form-hint form-err"><i></i>请输入验证码</span></dd>
                <dd>
                    <button type="submit" name="submit">同意以下协议并注册</button>
                </dd>
                <dd class="form-text"><h3>《CSCloud服务使用协议》</h3></dd>
                <dd class="form-text">CSCloud是长安大学信息学院信息存储技术研究室(ISTL)基于并行存储、虚拟化、版本控制、数据复制、负载均衡等关键技术，自主研发的云存储系统。目前已完成系统搭建和功能测试，现开放试用。试用期间CSCloud不对存储于系统之中数据的安全性做任何承诺和保证。谢谢！</dd>
            </dl>
        </form>
    </div>
<!-- 
<div class="login-link">
    <h3>已经注册过？<a href="login.php">登录ISTL</a></h3>
</div>
-->
</div>

<script type="text/javascript">
    String.formatmodel = function(str,model){
        for(var k in model){
                var re = new RegExp("{"+k+"}","g");
                str = str.replace(re,model[k]);
        }
        return str;
    }


   (function(ele){
       //以下用于检查密码强度
        var agt = navigator.userAgent.toLowerCase();
        var is_op = (agt.indexOf("opera") != -1);
        var is_ie = (agt.indexOf("msie") != -1) && document.all && !is_op;
        var is_mac = (agt.indexOf("mac") != -1);
        var is_gk = (agt.indexOf("gecko") != -1);
        var is_sf = (agt.indexOf("safari") != -1);

        function gff(str, pfx){
            var i = str.indexOf(pfx);
            if (i != -1) {
                var v = parseFloat(str.substring(i + pfx.length));
                if (!isNaN(v)) {
                    return v;
                }
            }
            return null;
        }

        function Compatible(){
            if (is_ie && !is_op && !is_mac) {
                var v = gff(agt, "msie ");
                if (v != null) {
                    return (v >= 6.0);
                }
            }
            if (is_gk && !is_sf) {
                var v = gff(agt, "rv:");
                if (v != null) {
                    return (v >= 1.4);
                }
                else {
                    v = gff(agt, "galeon/");
                    if (v != null) {
                        return (v >= 1.3);
                    }
                }
            }
            if (is_sf) {
                var v = gff(agt, "applewebkit/");
                if (v != null) {
                    return (v >= 124);
                }
            }
            return false;
        }

        /* We also try to create an xmlhttp object to see if the browser supports it */
        var isBrowserCompatible = Compatible();

        //CharMode函数
        //测试某个字符是属于哪一类.
        function CharMode(iN){
            if (iN >= 48 && iN <= 57) //数字
                return 1;
            if (iN >= 65 && iN <= 90) //大写字母
                return 2;
            if (iN >= 97 && iN <= 122) //小写
                return 4;
            else
                return 8; //特殊字符
        }

        //bitTotal函数
        //计算出当前密码当中一共有多少种模式
        function bitTotal(num){
            modes = 0;
            for (i = 0; i < 4; i++) {
                if (num & 1)
                    modes++;
                num >>>= 1;
            }
            return modes;
        }
        //checkStrong函数
        //返回密码的强度级别
        function checkPasswdRate(sPW){
            if (sPW.length <= 5)
                return 0; //密码太短
            Modes = 0;
            for (i = 0; i < sPW.length; i++) {
                //测试每一个字符的类别并统计一共有多少种模式.
                Modes |= CharMode(sPW.charCodeAt(i));
            }
            return bitTotal(Modes);
        }

        ele.bind("keyup", function(){
            var val = $(this).val(), level = checkPasswdRate(val), levelStr = ["不合格", "弱", "一般", "强", "极强"];
            var className = "dangerous";
            var persent = 0;
            if (level > 0 && level < 3) {
                className = "dangerous";
                persent = "30%";
            }
            if (level > 2) {
                if (level < 4) {
                    className = "simple";
                    persent = "60%";
                }
                else {
                    className = "safe";
                    persent = "100%";
                }
            }
            $("#js_passwordText").html(levelStr[level]);
            var strongBox = $("#js_passwordStrong");
            strongBox.removeClass().addClass("passwd-level pwd-"+className);
            strongBox.find("i").css({width: persent});
        });
  })($("#reg_password"));


    var FIRST_HIDE_STATE = false;
    var VailForm = (function(){
        var checkList = [];
        var errDom = {};
        var emailReg = /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i;

        var checkInputVal = function(){
            for(var i = 0, len = checkList.length; i < len; i++){
                var input = checkList[i];
                var label = input.prev();
                if(input.val() != ""){
                    label.css("display") == "none" || label.hide();
                }else{
                    label.css("display") == "none" && label.show();
                }
            }
        }

        var showMsg = function(input, msg){
            if(!msg){
                msg = input.attr("errmsg");
            }
            var par = input.parent();
            var hint = par.find(".form-hint");
            if(!hint.length){
                hint = $('<span class="form-hint form-err"><i></i>'+msg+'</span>');
                par.append(hint);
            }
            else{
                hint.html('<i></i>' + msg).show();
            }
        }

        var hideMsg = function(input){
            var par = input.parent();
            var hint = par.find(".form-hint");
            if(hint.length){
                hint.hide();
            }
        }

        var showMsgSucc = function(input){
            var par = input.parent();
            var hint = par.find(".form-hint-succ");
            if(!hint.length){
                par.append('<span class="form-hint-succ"></span>');
            }
        }
        var hideMsgSucc = function(input){
            var par = input.parent();
            var hint = par.find(".form-hint-succ");
            if(hint.length){
                hint.remove();
            }
        }

        var checkFun = function(input){
            var val = input.val();
            var type = input.attr("errortype");
            switch(type){
                case "email":
                    if(!emailReg.test(val)){
                        return false;
                    }
                    break;
                case "notempty":
                    if($.trim(val) == ""){
                        return false;
                    }
                    if(input.attr("minlen") && val.length < Number(input.attr("minlen"))){
                        return false;
                    }
                    break;
                case "rate":
                    if($("#" + input.attr("rate")).val() != val){
                        return false;
                    }
                    break;
            }
            return true;
        }

        var checkIng = false;
        var ajaxCheck = function(ele, sucCallback, errCallback){
            if(!checkIng){
                checkIng = true;
                $.getScript(ele.attr("isjs") + "&js_return=js_check_back_val&" + ele.attr("name") + "=" + encodeURIComponent(ele.val()), function(){
                    checkIng = false;
                    if(window["js_check_back_val"]){
                        var res = window["js_check_back_val"];
                        if(!res.state){
                            ele.attr("err", "2");
                            showMsg(ele, res.message);
                            hideMsgSucc(ele);
                            if(errCallback) errCallback();
                        }else{
                            hideMsg(ele);
                            showMsgSucc(ele);
                            ele.attr("err","");
                            if(sucCallback) sucCallback();
                        }
                        window["js_check_back_val"] = null;
                    }
                })
            }
        }

        var bindInput = function(form,input){
            checkList.push(input);
            if(input.attr("errortype") == "rate"){
                $("#" + input.attr("rate")).bind("keyup", function(){
                    hideMsg(input);
                })
            }

            input.bind("blur", function(e){
                var ele = $(this);
                if(ele.attr("isjs") || ele.attr("err")){
                    var checkRes = checkFun(ele);
                    if(checkRes){
                        hideMsg(ele);
                        //$(this).attr("err", "");
                        ele.removeAttr("err");
                        if(ele.attr("isjs")){
                            ajaxCheck(ele);
                        }
                    }else{
                        showMsg(ele);
                        hideMsgSucc(ele);
                    }
                }
                else{
                    var checkRes = checkFun(ele);
                    if(checkRes){
                        hideMsg(ele);
                        showMsgSucc(ele);
                    }else{
                        showMsg(ele);
                        hideMsgSucc(ele);
                        ele.attr("err", "1");
                    }
                }

            }).bind("focus", function(){
                var ele = $(this);
                ele.removeAttr("err");

                if(form.attr("rel") == "reg"){
                    $("form[rel='login']").find("input[errortype]").each(function(){
                        hideMsg($(this));
                    })
                }else{
                    $("form[rel='reg']").find("input[errortype]").each(function(){
                        hideMsg($(this));
                    })
                }

            }).bind("keydown", function(){
                var ele = $(this);
                if(ele.attr("isjs")){
                    hideMsg(ele);
                }
            })
        }

        var _hideTimer;

        return {
            ShowMsg: function(ele, msg){
                showMsg(ele,msg);
            },
            HideMsg: function(ele){
                hideMsg(ele);
            },
            Init: function(){
                $("form[vail]").each(function(i){
                    var form = $(this);

                    form.find("input[errortype]").each(function(j){
                        bindInput(form, $(this));
                    });
                    form.bind("submit", function(){
                        if(_hideTimer){
                            window.clearTimeout(_hideTimer);
                        }
                        var res = true;
                        var firstDom;
                        var form = $(this);
                        form.find("input[errortype]").each(function(){
                            var ele = $(this);
                            if(ele.attr("err") == "2"){
                                res = false;
                                firstDom = ele;
                            }else{
                                var checkRes = checkFun(ele);
                                if(checkRes){
                                    hideMsg(ele);
                                }else{
                                    showMsg(ele);
                                    res = false;
                                    ele.attr("err", "1");
                                    if(!firstDom){
                                        firstDom = ele;
                                    }
                                }
                            }
                        });
                        if(!res){
                            if(firstDom.attr("isjs")) {
                               form.find("input[errortype]").each(function(){
                                    !$(this).attr("isjs") && hideMsg($(this));
                                });
                               var ele = firstDom;
                               if(ele.attr("err") != "1"){
                                   ajaxCheck(ele, function(){
                                       window.setTimeout(function(){
                                            form.submit();
                                        }, 10);
                                   });
                               }
                            }
                            firstDom.focus();
                        }

                        if(res){
                            if(form.attr("rel") == "reg"){
                                if(!$("#js_agree_checkbox").attr("checked")){
                                    res = false;
                                    alert("抱歉，您必须同意ISTL使用协议才可以注册！");
                                        return false;
                                }
                                if($("#reg_email").attr("err") == undefined){
                                    ajaxCheck($("#reg_email"), function(){
                                       window.setTimeout(function(){
                                            form.submit();
                                        }, 10);
                                    });
                                    return false;
                                }
                                if($("#reg_user_name").attr("err") == undefined){
                                    ajaxCheck($("#reg_user_name"), function(){
                                       window.setTimeout(function(){
                                            form.submit();
                                        }, 10);
                                    });
                                    return false;
                                }
                            }
                        }

                        if(res){
                            if(form.attr("rel") == "reg"){
                                var codeDom = $("#reg_valicode");
                                if(codeDom.length){
                                    if(codeDom.attr("err") != "3"){
                                        ajaxCheck(codeDom, function(){
                                            codeDom.attr("err", "3");
                                            form.submit();
                                        }, function(){
                                            $("#js_code_img").attr("src", "authcode.php?" + new Date().getTime());
                                            window.setTimeout(function(){
                                                codeDom.select();
                                            },50);
                                        });
                                        return false;
                                    }
                                }
                            }
                        }

                        return res;
                    })
                })
                window.setInterval(checkInputVal, 10);
            }
        }
    })();

    (function(){
        VailForm.Init();
        window.setTimeout(function(){
            $("#reg_email").focus();
        }, 400);

    })();

</script>

<div class="footer">
    <div class="con">
        <div class="copy-right">Copyright © 2014 ISTL  ALL RIGHTS RESERVED</div>
<!--        <dl>
            <dt>ISTL云存储空间</dt>
        </dl>
        <dl>
            <dt>关于ISTL</dt>
        </dl>
        <dl>
            <dt>疑问与帮助</dt>
            <dd><a href="#">服务协议</a></dd>
            <dd><a href="#">帮助中心</a></dd>
            <dd><a href="#">我要反馈</a></dd>
        </dl> -->
    </div>
</div>
<script type="text/javascript">
	function getCode(){
	  document.getElementById("authCode").src = "authcode.php?" + new Date().getTime();
	}
	function onSubmit(){
	  document.getElementById("signForm").submit()
	}
</script>
<?php echo $this->_tpl_vars['text']; ?>

</body>
</html>