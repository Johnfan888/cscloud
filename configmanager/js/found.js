// JavaScript Document
function $(id){
	return document.getElementById(id);
}
window.onload = function(){
	$('foundname').focus();
	$('step1').onclick = function(){
		if($('foundname').value != '' && $('fdquestion').value != '' && $('fdanswer').value != ''){
			xmlhttp.open('get','found_chk.php?foundname='+$('foundname').value+'&question='+$('fdquestion').value+'&answer='+$('fdanswer').value,true);
			xmlhttp.onreadystatechange = function(){
				if(xmlhttp.readystate == 4 && xmlhttp.status == 200){
					msg = xmlhttp.responseText;
					if(msg == '2'){
						alert('填写信息错误！');
					}else{
						alert(msg);
						window.close();
					}
				}
			}
			xmlhttp.send(null);
		}else{
			alert('请填写完成信息');
			$('foundname').focus();
			return false;
		}
	}
}