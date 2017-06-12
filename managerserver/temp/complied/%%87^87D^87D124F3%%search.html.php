<?php /* Smarty version 2.6.14, created on 2012-06-10 03:55:53
         compiled from search.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> <?php echo $this->_tpl_vars['title']; ?>
 </title>
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
						<div class="operate-panel" id="js_top_bar_box">
							<div class="frame-search" style="float:right;">
						   <form id="js_search_file_form" action="search.php" method="get">
								<input type="text" rel="txt" name="search_name" style="color: rgb(128, 128, 128); ">
								<button type="submit"><b>搜索</b></button>
							</form>
							</div>
							<div class="opt-side"></div>
						</div>
						 <div class="directory-path">
						 </div>
					</div>

					<div class="page-list" id="js_data_list_outer">
						<div style="min-height:100%;_height:100%;cursor:default; background:#fff;" id="js_data_list"> 
							<?php echo $this->_tpl_vars['nofile']; ?>

							<ul rel="list" style="overflow: hidden;_zoom: 1;" id="js_data_list_inner">
								<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
								<?php if ($this->_tpl_vars['file']['file_type'] == 1): ?>
								<li rel="item">
									<i class="file-type <?php echo $this->_tpl_vars['file']['file_url']; ?>
"></i>
									<div class="file-name">
										<a href="home.php?id=<?php echo $this->_tpl_vars['file']['file_id']; ?>
" title="<?php echo $this->_tpl_vars['file']['file_name']; ?>
"><?php echo $this->_tpl_vars['file']['file_name']; ?>
</a>
										<div style="float:right;">
										  父目录:
										<a href="home.php?id=<?php echo $this->_tpl_vars['file']['parent_id']; ?>
" title="<?php echo $this->_tpl_vars['file']['parent_name']; ?>
">[<?php echo $this->_tpl_vars['file']['parent_name']; ?>
]</a>
										</div>
									</div>
									<div class="file-info">
										<em><?php echo $this->_tpl_vars['file']['modify_time']; ?>
</em> <em><?php echo $this->_tpl_vars['file']['size']; ?>
</em>
									</div>
									<div class="file-opt">
										<a href="home.php?id=<?php echo $this->_tpl_vars['file']['file_id']; ?>
" class="il-divert" title="进入">进入</a>
										<a href="javascript:;" file="<?php echo $this->_tpl_vars['file']['file_id']; ?>
" class="il-delete" title="删除">删除</a>
										<a href="javascript:;" file="<?php echo $this->_tpl_vars['file']['file_id']; ?>
" name="<?php echo $this->_tpl_vars['file']['file_name']; ?>
" class="il-rename" title="重命名">重命名</a>
									</div>
								</li>
								<?php else: ?>
								<li rel="item">
									<i class="file-type <?php echo $this->_tpl_vars['file']['file_url']; ?>
"></i>
									<div class="file-name">
										<a href="#" target="_blank" title="<?php echo $this->_tpl_vars['file']['file_name']; ?>
"><?php echo $this->_tpl_vars['file']['file_name']; ?>
</a>
									</div>
									<div class="file-info">
										<em><?php echo $this->_tpl_vars['file']['modify_time']; ?>
</em> 
										<em><?php echo $this->_tpl_vars['file']['size']; ?>
</em> 
										<em><?php echo $this->_tpl_vars['file']['version']; ?>
</em>
									</div>
									<div class="file-opt">
										<a href="download.php?id=<?php echo $this->_tpl_vars['file']['file_id']; ?>
" class="il-download" title="下载" target="_blank">下载</a>
										<a href="home.php?id=<?php echo $this->_tpl_vars['file']['parent_id']; ?>
" class="il-divert" title="进入父目录">进入父目录</a>
										<a href="javascript:;" file="<?php echo $this->_tpl_vars['file']['file_id']; ?>
" class="il-delete" title="删除">删除</a>
										<a href="javascript:;" file="<?php echo $this->_tpl_vars['file']['file_id']; ?>
" name="<?php echo $this->_tpl_vars['file']['file_name']; ?>
" class="il-rename" title="重命名">重命名</a>
									</div>
								</li>
								<?php endif; ?>
								<?php endforeach; endif; unset($_from); ?>
							</ul>
						</div>
					</div>

					<div class="page-footer">
						<div class="file-pages"></div>
					</div>
			</div>
		</div>
</body>
</html>