$(function(){

	//委托关闭按钮事件
	$("#dir-cancel").live('click', function(){
		$("#dialog-box").remove();
		$("#mask-layer").remove();
		window.location.reload();
	});
	$(".diag-close").live('click', function(){
		$("#dialog-box").remove();
		$("#mask-layer").remove();
		window.location.reload();
	});

	//新建文件夹   debug
	$("#newdir").click(function(){
		InitDialogBox(newdirbox);
		//定义新建文件夹提交按钮操作
		$("#dir-submit").click(function(){
			var name = $.trim($("#dir-name").val());
			if(name != '')
			{
				var id = $("#dir-id").val();
				$.get("newdir.php", {pid: id, name: name, time: new Date().getTime()}, function(data){
					var callback = eval( "(" + data + ")" );
					//创建成功
					if(callback.result)
					{
						var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-suc-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
						$("body").append(msg);
						$(".popup-hint").fadeOut(1500, function(){
							$(".popup-hint").remove();
							window.location.reload();
						});
					}
					else
					{
						var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-err-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
						$("body").append(msg);
						$(".popup-hint").fadeOut(2500, function(){
							$(".popup-hint").remove();
						});
					}
				});
			}
			else
			{
				var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 485px; display: block; "><i class="hint-icon hint-err-m"></i><em class="sl"><b></b></em><span rel="con">目录名称不能为空。</span><em class="sr"><b></b></em></div>';
				$("body").append(msg);
				$(".popup-hint").fadeOut(2500, function(){
					$(".popup-hint").remove();
				});
			}
		});
	});

	//上传文件
	$("#upload").click(function(){
		var id = $("#dir-id").val();
		var c = $("#c").val();
		var success = 0;
		var error = '';

		$.get("upload.php", {pid: id, time: new Date().getTime()}, function(data){
				var callback = eval( "(" + data + ")" );
				//成功
				if(callback.result)
				{
					InitDialogBox(uploadbox);
					$("#file_upload").uploadify({
					"auto" : false,
					"swf"      : "plugin/uploadify.swf",
					"uploader" : "http://" + callback.to + "/fs_upload.php",
					"buttonText" : "请选择文件(可多选)",
					'fileObjName' : 'the_files',
					"width" : 200,
					"fileSizeLimit" : 0,
					"removeCompleted" : true,
					"formData" : {'pid' : id, 'c': c, 'ip': callback.from},
					"onUploadSuccess" : function(file, data, response){
													var callback = eval( "(" + data + ")" );
													if(!callback.result)
													{
														error = callback.msg;
														//空间不足，取消传输队列
														$('#file_upload').uploadify('stop');
													}
													else
													{
														success++;
													}
												},
					"onQueueComplete" : function(queueData) {
													var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-suc-m"></i><em class="sl"><b></b></em><span rel="con">'+ success +'个文件上传成功&nbsp;' + error +'</span><em class="sr"><b></b></em></div>';
													$("body").append(msg);
													$(".popup-hint").fadeOut(3500, function(){
														$(".popup-hint").remove();
													});
												 }
					});
					$("#dir-submit").click(function(){
						$('#file_upload').uploadify('upload','*');
					});
				}
		});
	});

	
	//重命名文件 debug
	$(".il-rename").click(function(){
		var id = $(this).attr("file");
		var old_name = $(this).attr("name");
		//正则表达式匹配文件后缀名
		var reg = /\.\w+$/;
		InitDialogBox(renamebox);
		$("#dir-name").val(old_name.replace(reg, ''));
		//定义重命名按钮的提交操作
		$("#dir-submit").click(function(){
			var new_name = $.trim($("#dir-name").val());
			if(new_name != "")
			{
				//请求重命名页面
				$.get("rename.php", {id: id, name: new_name, time: new Date().getTime()}, function(data){
					var callback = eval( "(" + data + ")" );
					//重命名成功
					if(callback.result)
					{
						var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-suc-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
						$("body").append(msg);
						$(".popup-hint").fadeOut(1500, function(){
							$(".popup-hint").remove();
							window.location.reload();
						});
					}
					else
					{				
						var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-err-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
						$("body").append(msg);
						$(".popup-hint").fadeOut(2500, function(){
							$(".popup-hint").remove();
						});
					}
				});
			}
			else
			{
				var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 485px; display: block; "><i class="hint-icon hint-err-m"></i><em class="sl"><b></b></em><span rel="con">重命名名称不能为空。</span><em class="sr"><b></b></em></div>';
				$("body").append(msg);
				$(".popup-hint").fadeOut(2500, function(){
					$(".popup-hint").remove();
				});
			}
		});
	});

	//删除文件   debug
	$(".il-delete").click(function(){
		var id = $(this).attr("file");
		InitDialogBox(deldirbox);
		//定义删除按钮的提交操作
		$("#dir-submit").click(function(){
			$.get("delete.php", {id: id, time: new Date().getTime()}, function(data){
				var callback = eval( "(" + data + ")" );
				//删除成功
				if(callback.result)
				{
					var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-suc-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
					$("body").append(msg);
					$(".popup-hint").fadeOut(1500, function(){
						$(".popup-hint").remove();
						window.location.reload();
					});
				}
				else
				{				
					var msg = '<div class="popup-hint" style="z-index: 9999999999; top: 160px; left: 488px; display: block; "><i class="hint-icon hint-err-m"></i><em class="sl"><b></b></em><span rel="con">'+ callback.msg +'</span><em class="sr"><b></b></em></div>';
					$("body").append(msg);
					$(".popup-hint").fadeOut(2500, function(){
						$(".popup-hint").remove();
					});
				}
			});
		});
	});
});

//初始化拖动框
function InitDialogBox(boxname){
	$("body").append(mask);
	$("body").append(boxname);
	//$("#dialog-box").draggable();
	$.Move('dialog-box');
}


//遮罩层
var mask = '<div id="mask-layer" style="z-index: 1000000001; background-image: none; background-attachment: scroll; background-origin: initial; background-clip: initial; height: 100%; left: 0px; position: absolute; top: 0px; width: 100%; opacity: 0; background-position: 0px 0px; background-repeat: repeat repeat; "><div style="height:100%;width:100%;"></div></div>';

//新建文件夹框
var newdirbox = '<div id="dialog-box" class="dialog-box window-current" style="z-index: 1000000002; top: 72px; left: 440px; "><h2 class="dialog-title" rel="title_box"><span rel="base_title">新建文件夹</span><div class="dialog-handle"><a href="javascript:;" class="diag-close">关闭</a></div></h2><div rel="base_content"><div class="dialog-input"><input type="text" id="dir-name" name="dir-name" class="text"></div><div class="dialog-bottom"><div class="con"><a href="javascript:;" id="dir-submit" class="button">确定</a><a href="javascript:;" id="dir-cancel" class="button btn-gray">取消</a></div></div></div>';

//删除文件框
var deldirbox = '<div id="dialog-box" class="dialog-box window-current" style="z-index: 1000000002; top: 72px; left: 440px; "><h2 class="dialog-title" rel="title_box"><span rel="base_title">删除文件</span><div class="dialog-handle"><a href="javascript:;" class="diag-close">关闭</a></div></h2><div rel="base_content"><div class="dialog-msg" rel="content"><h3><i class="hint-icon hint-war"></i>确认要删除选中的文件吗？</h3><div class="dialog-msg-text"></div></div><div class="dialog-bottom"><div class="con"><a href="javascript:;" id="dir-submit" class="button">确定</a><a href="javascript:;" id="dir-cancel" class="button btn-gray">取消</a></div></div></div>';

//重命名文件框
var renamebox = '<div id="dialog-box" class="dialog-box window-current" style="z-index: 1000000002; top: 72px; left: 440px; "><h2 class="dialog-title" rel="title_box"><span rel="base_title">重命名文件</span><div class="dialog-handle"><a href="javascript:;" class="diag-close">关闭</a></div></h2><div rel="base_content"><div class="dialog-input"><input type="text" id="dir-name" name="dir-name" class="text"></div><div class="dialog-bottom"><div class="con"><a href="javascript:;" id="dir-submit" class="button">确定</a><a href="javascript:;" id="dir-cancel" class="button btn-gray">取消</a></div></div></div>';

//上传文件框
var uploadbox = '<div id="dialog-box" class="dialog-box window-current" style="z-index: 1000000002; top: 72px; left: 440px; "><h2 class="dialog-title" rel="title_box"><span rel="base_title">上传文件</span><div class="dialog-handle"><a href="javascript:;" class="diag-close">关闭</a></div></h2><div rel="base_content"><div class="dialog-input"> <input type="file" name="file_upload" id="file_upload" /></div><div class="dialog-bottom"><div class="con"><a href="javascript:;" id="dir-submit" class="button">开始上传</a><a href="javascript:;" id="dir-cancel" class="button btn-gray">关闭</a></div></div></div>';

$.Move = function(_this){
        if(typeof(_this)=='object'){
            _this=_this;
        }else{
            _this=$("#"+_this);
        }
        if(!_this){return false;}

        _this.css({'position':'absolute'}).hover(function(){$(this).css("cursor","move");},function(){$(this).css("cursor","default");})
        _this.mousedown(function(e){ 
			var offset = $(this).offset();
            var x = e.pageX - offset.left;
            var y = e.pageY - offset.top;
            $(document).bind("mousemove",function(ev){
				_this.bind('selectstart',function(){return false;});
                var _x = ev.pageX - x;
				var _y = ev.pageY - y;
                _this.css({'left':_x+"px",'top':_y+"px"});
            });
        });

        $(document).mouseup(function(){
            $(this).unbind("mousemove");
            _this.css({'opacity':''});
        })
    };
