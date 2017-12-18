<?php
					
					$originip1=$_GET[originip1];
					$fp=fopen("/var/log/csc/stage.txt","a");
					$time=time('Y-m-d H:i:s');
					fwrite($fh,$time."\n");
					fwrite($fp,"The ds ".$originip1." only has one user,needn't transfer!"."\n");
					fclose($fp);
					echo "<script language=\"javascript\">window.close();</script>";
?>
