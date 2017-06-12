<?php /* Smarty version 2.6.14, created on 2012-04-10 07:56:39
         compiled from main.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> new document </title>
  <link href="css/common.css" type="text/css" rel="stylesheet" />
  <link href="css/frame_main.css" type="text/css" rel="stylesheet" />
  <link href="css/page_list.css" type="text/css" rel="stylesheet" />
  <link href="css/page.css" type="text/css" rel="stylesheet" />
 </head>
<body>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div style="display: block; " id="js_frame_box">
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "lib/left.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<div class="frame-contents" id="js_frame_box">
		<div class="page-contents">
			<div class="page-main" id="js_cantain_box">
				<div class="page-header">
            <div class="operate-panel" id="js_top_bar_box"><div class="opt-button"><a href="javascript:;" class="button btn-icon" menu="upload"><i class="ico-btn ib-upload"></i>上传文件</a> <a href="javascript:;" class="button btn-gray btn-icon" menu="adddir"><i class="ico-btn ib-newdir"></i>新建文件夹</a></div><div class="opt-side"></div></div>
            <div class="directory-path">
                <input type="checkbox" check="all">
                <div class="path-contents" rel="page_local"><a href="javascript:;" class="nav-back" btn="goto_dir" aid="-1" cid="-1">上一级</a> <a href="javascript:;" btn="goto_dir" aid="-1" cid="-1">首页</a> » <span title="我的文档">我的文档</span></div>
                <div class="list-filter" id="js_fileter_box"><div class="list-refresh"><a href="javascript:;" onclick="if(!Main.ReInstance()){window.location.reload();}">刷新</a></div><dl><dt>排序：</dt><dd rel="order"><a href="javascript:;" rel="t"><span>时间从新到旧</span><i></i></a><ul rel="c" class="select-list" display="none;" style="display: none; "><li><a href="javascript:;" btn="filter" order="file_name" asc="0">按文件名倒序</a></li><li><a href="javascript:;" btn="filter" order="file_name" asc="1">按文件名顺序</a></li><li><a href="javascript:;" btn="filter" order="user_ptime" asc="0">时间从新到旧</a></li><li><a href="javascript:;" btn="filter" order="user_ptime" asc="1">时间从旧到新</a></li><li><a href="javascript:;" btn="filter" order="file_size" asc="0">文件从大到小</a></li><li><a href="javascript:;" btn="filter" order="file_size" asc="1">文件从小到大</a></li></ul></dd></dl></div>
            </div>
        </div>
			</div>
		</div>
	</div>
</div>
</body>
</html>