<?php
define("_SITE_ROOT", "/srv/www/htdocs/");
require(_SITE_ROOT.'themes/smarty/Smarty.class.php');

class comment extends Smarty {
	var $cachecount,$CONF, $INCOME;
	//构造函数
	function comment(){
		require_once(_SITE_ROOT.'include/config.php'); //连接数据库的参数设置
		$this->cachecount= 20;
		$this->CONF = $CONF; //数组
		if($this->CONF['sys']['show_error']){
			ini_set('display_errors', true); //设置值
		}else{
			ini_set('display_errors', false);
		}
		//test
		//$this->msg(print_r($this->CONF), '-1');
		
		$this->Smarty();
		
		/*$this->DB = ADONewConnection('mysql');
		$this->DB->Connect( $this->CONF['db']['host'], $this->CONF['db']['user'], $this->CONF['db']['pwd'], $this->CONF['db']['name'] );
		$this->DB->execute("SET NAMES 'gbk'", $this->DB);*/
		
		
		$this->Con1=mysql_connect($this->CONF['db']['host'],$this->CONF['db']['user'],$this->CONF['db']['pwd']);   
		mysql_query("set names 'gbk'");    
		mysql_select_db($this->CONF['db']['name'] ,$this->Con1);
		
		
		if(!$this->Con1){
			$this->msg("对不起，无法连接数据库，请稍后重试！", '-1');
		}
		$this->parse_incoming();
	}
	
	 function cancleconnoction()
	 {
	    mysql_close($this->Con1);
	  }
	function msg($msg, $url) {
		$this -> assign("msg",$msg);
		$this -> assign("url",$url);
        $this -> display("manage/error.html");
        exit();
	}
	
	function check_null($var, $title) {
		if($var == ""){
			$this->msg("对不起，<strong>{$title}</strong>不能为空", "-1");
		}
	}
	
	function build_pagelinks($record) {
		$nav = array();
		
		if ( ($record['TOTAL_POSS'] % $record['PER_PAGE']) == 0 ){
			$page_num = $record['TOTAL_POSS'] / $record['PER_PAGE'];
		} else {
			$page_num = ceil($record['TOTAL_POSS'] / $record['PER_PAGE']);
		}
		$page_num--;
		//生成翻页连接
		if($record['CUR_ST'] == 0) {
			$nav['first_page'] = "<a href='#'>第一页</a>";
			$nav['last_page'] = "<a href='#'>前一页</a>";
		}else{
			$nav['first_page'] = "<a href='{$record['BASE_URL']}&amp;st=0'>第一页</a>";
			$nav['last_page'] = "<a href='{$record['BASE_URL']}&amp;st=".($record['CUR_ST']-1)."'>前一页</a>";
		}
		if($record['CUR_ST'] >= $page_num) {
			$nav['end_page'] = "<a href='#'>最后一页</a>";
			$nav['next_page'] = "<a href='#'>下一页</a>";
		} else {
			$nav['end_page'] = "<a href='{$record['BASE_URL']}&amp;st={$page_num}'>最后一页</a>";
			$nav['next_page'] = "<a href='{$record['BASE_URL']}&amp;st=".($record['CUR_ST']+1)."'>下一页</a>";
		}
		//生成跳转页
		$nav['jump_page'] = "<select onchange=\"javascript:window.location='{$record['BASE_URL']}&amp;st=' + this.options[this.selectedIndex].value\">\n";
		for($i=0; $i<=$page_num; $i++){
			$nav['jump_page'] .= "	<option value={$i}";
			if($i == $record['CUR_ST']){
				$nav['jump_page'] .= " selected";
			}
			$nav['jump_page'] .= ">GoTo ".($i+1)."</option>\n";
		}
		$nav['jump_page'] .= "</select>";
		return "{$nav['first_page']} {$nav['last_page']} {$nav['next_page']} {$nav['end_page']} {$nav['jump_page']}";
	}
	

	function parse_incoming(){
		if( is_array($_REQUEST) ) {
			while( list($k, $v) = each($_REQUEST) ) {
				if ( is_array($_REQUEST[$k]) ) {
					while( list($k2, $v2) = each($_REQUEST[$k]) ) {
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				} else {
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		$this->INCOME = $return;
	}
	
	function clean_key($key) {    
    	if ($key == "")	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./", "", $key);
    	$key = preg_replace( "/\_\_(.+?)\_\_/", "", $key);
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key);
    	return $key;
    }
	
	function clean_value($val) {
    	
    	if ($val == "") {
    		return "";
    	}    	
    	$val = str_replace("&", "&amp;", $val);
    	$val = str_replace(">", "&gt;", $val);
    	$val = str_replace("<", "&lt;", $val);
    	$val = str_replace('"', "&quot;", $val);
    	$val = preg_replace("/\n/", "<br>", $val);    	
    	$val = str_replace("!", "&#33;", $val);
    	$val = str_replace("'", "&#39;", $val);
    	
    	return $val;
    }
    
    function revert_value($val) {
    	
    	if ($val == "") {
    		return "";
    	}    	
    	$val = str_replace("&amp;", "&", $val);
    	$val = str_replace("&gt;", ">", $val);
    	$val = str_replace("&lt;", "<", $val);
    	$val = str_replace("&quot;", '"', $val);
    	$val = preg_replace("<br>", "/\n/", $val);    	
    	$val = str_replace("&#33;", "!", $val);
    	$val = str_replace("&#39;", "'", $val);
    	
    	return $val;
    }
    
    	
	function update($record, $table, $condition){    
	   $sql = "UPDATE `{$table}` SET ";
	   foreach($record as $key => $value){
	       $sql .= "`{$key}`='{$value}',";
	   }
	   $sql = substr($sql, 0, -1);
	   $sql .= " WHERE {$condition}";
	   return $this->DB->execute($sql);
	}
}


/*//原conn中的内容

//检查用户是否登录
function isLogin(){
	global $conn;
	if(isset($_COOKIE["username"]) and isset($_COOKIE["password"])){
		$rs = mysql_query("select user_id from members where username = '".$_COOKIE["username"]."' and password = '".$_COOKIE["password"]."'");
		if(mysql_num_rows($rs)<=0){
			echo "<script>
				alert('请登录.');
				window.location.href='login.php';
			</script>";
		}
	}
}*/
//信息提示函数
function info($content,$url){
print <<<EOT
<br />
<table cellpadding="5" cellspacing="0" align="center" class="tableborder1" style="width:60%; border-top:none;">
	<tr>
	<th  height="20" align=center>提示信息</th>
	</tr>
	<tr>
	<td class="tablebody5" align="center">
	<table cellpadding="3" cellspacing="1" align="center" style="width:100%">
	<tr>
	<td align=left>
	<blockquote>
	<BR>

{$content}<BR>

<br>
如果页面没有自动跳转,请 <a target="_self" href="{$url}">[ 单击进入网页 ]</a>&nbsp;&nbsp;
<script>
	function nav(){
		window.location.href = "{$url}";
	}
	//3秒后跳转页面
	setTimeout("nav()", 3000);
</script>
	</blockquote>
	</td></tr>
	</table>
	</td>
	</tr>
	</table>
EOT;
	exit();
}
?>