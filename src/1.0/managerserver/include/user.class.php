<?php
class username extends comment{
	private function username(){
		parent::comment();
	}
	static $instance = false;
	
	public function getInstance() {
	if (!username::$instance) {
	username::$instance = new username();
	}
	return username::$instance;
	}
	/*function add_user($record){
		$this->check_null($record['username'], '�û���');
		$this->check_null($record['password'], '����');
		$this->check_null($record['email'], 'E-mail');
		$sql = "select count(*) as num from members where username='{$record['username']}'";
		$rs = mysql_query($sql,$this->Con1);
		$row = mysql_fetch_array($rs);
		if($row['num']>0){
			$this->msg('�Բ����û����Ѵ���');
			exit();
		}
		$sql = "select count(*) as num from members where email='{$record['email']}'";
		$rs = mysql_query($sql,$this->Con1);
		$row = mysql_fetch_array($rs);
		if($row['num']>0){
			$this->msg('�Բ���E-mail�Ѵ���');
			exit();
		}
		return $this->insert($record, 'members');		
	}*/
	
	function edit_user($record){
		$sql="select * from members where user_id='{$record['user_id']}'";
		$result=mysql_query($sql,$this->Con1);
		$menu=mysql_fetch_array($result);
		$username=$menu["username"];
		
		$sql="select * from filesize where username='".$username."'";
		$res=mysql_query($sql,$this->Con1);
		$num=mysql_num_rows($res);
		if($num>0)
		{
		$sql="update filesize set totalsize='{$record['size']}' where username='".$username."'";
		mysql_query($sql,$this->Con1);
		}
		else{
		$sql="insert into filesize(username,totalsize) values ('".$username."','{$record['size']}')";
		mysql_query($sql,$this->Con1);
		}
		$sql="update members set is_admin= '{$record['is_admin']}',is_open= '{$record['is_open']}',company= '{$record['company']}',name= '{$record['name']}',country= '{$record['country']}',tel= '{$record['tel']}',fax= '{$record['fax']}' where user_id= '{$record['user_id']}'";
		return mysql_query($sql,$this->Con1);
	}
	
	function del_user($id){
		$this->check_null($id, '�û�ID');
		$sql = "delete from members where user_id = {$id}";
		return mysql_query($sql,$this->Con1);
	}
	
	function list_user($st = 0) {		
		$page_num = 30;
		$pages = $this->build_pagelinks(array(	'TOTAL_POSS'  => $this->get_count(),
												'PER_PAGE'    => $page_num,
												'CUR_ST'  => $st,
												'BASE_URL'    => "user.php?act=list_user"
											)
										);
		$this -> assign( "pages" , $pages);
		$sql = 'select * from members order by user_id desc LIMIT '.$st*$page_num.','.$page_num;
		$rs = mysql_query($sql,$this->Con1);
		$nums=mysql_num_rows($rs);
		$i=0;
		for($i=0;$i<$nums;$i++)
		{
		   $row=mysql_fetch_array($rs);
		   $arr[] = $row;
		}
		return $arr;
	}
	
	function get_count(){
		$sql = "SELECT COUNT(*) AS num FROM members";
		$rs = mysql_query($sql,$this->Con1);
		$row = mysql_num_rows($rs);
		return $row['num'];
	}
	
	function update_user_psw($record){
		$this->check_null($record['user_id'], '�û�ID');
		$this->check_null($record['new_psw'], '������');
		$this->check_null($record['new_psw2'], '������2');
		if($record['new_psw'] != $record['new_psw2']){
			$this->msg('�Բ��������������벻ͬ��');
		}
		$ret['password'] = md5($record['new_psw']);
		$sql="update members set password= '{$ret['password']}' where user_id= '{$record['user_id']}'";
		return mysql_query($sql,$this->Con1);
	}
	
	function get_user_info($user_id){
		$this->check_null($user_id, '�û�ID');
		$sql = "select * from members where user_id = {$user_id}";
		$rs = mysql_query($sql,$this->Con1);
		$rs1=mysql_query("select totalsize from filesize where username=(select username from members where user_id='".$user_id."')",$this->Con1);
		$row = mysql_fetch_array($rs);
		$row1 = mysql_fetch_array($rs1);
		$row1["totalsize"]=round($row1["totalsize"]/1000000,2);
		$row=array_merge($row,$row1);
		$row2=array_merge($row,$row1);
		return $row2;
	}
	
	function login($username, $password){
		$sql = "select user_id,username,is_admin from members where username='{$username}' and password='".md5($password)."' and is_open='1'";
		$rs = mysql_query($sql,$this->Con1);
		$num=mysql_num_rows($rs);
		if($num== 1){
			$row = mysql_fetch_array($rs);
			setcookie("admin[user_id]", $row['user_id']);
			setcookie("admin[username]", $row['username']);
	//�ж��û�������Ȩ��1��ϵͳ����Ա��0����ͨ�û�
			if($row['is_admin']=='1')
			{ 
			  setcookie("administator", 1);
			  $this->msg('��ϲ�����Ѿ��ɹ���½��', 'admin.php');
			 }
			else if($row['is_admin']=='0')
			{ 
			  setcookie("administator", 0);
			  $this->msg('��ϲ�����Ѿ��ɹ���½��', 'admin.php');
			}
			
			/* $this->msg('��ϲ�����Ѿ��ɹ���½��', 'admin.php');*/
		}else
		{
			$this->msg('�û������벻ƥ���û�й���Ȩ�ޣ����Ժ����ԣ�', -1);
		}
	}
	
	function login_out()
	{
	        setcookie("admin[user_id]", "");
			setcookie("admin[username]", "");
			setcookie("administator", 0);
	
	}
	
	function user_login($username, $password){
		/*$sql = "select user_id,username,is_open from members where username='{$username}' and password='".md5($password)."'";*/
		$sql = "select user_id,username,is_open from members where username='{$username}' and password='".md5($password)."'";
		$rs =mysql_query($sql,$this->Con1);
		$row =mysql_fetch_array($rs);
		//print_r($row);
		if($row['is_open'] == 1){			
			setcookie("user[user_id]", $row['user_id']);
			setcookie("user[username]", $row['username']);
			setcookie("user[is_open]", $row['is_open']);
			//$this->msg('Welcome to our website', 'index.php');
			header("Location: index.php");
		}elseif($row['is_open'] == 0){
			$this->msg('������Ϣ�Ѿ��ύ������Ա�������Ժ����ԡ�', -1);
		}else{
			$this->msg('�û��������벻��ȷ��');
		}
	}
}
?>