<?php
					
					$originip1=$_GET[originip1];
					$step=$_GET[step];
					$time=time('Y-m-d H:i:s');
					$fp=fopen("/var/log/csc/stage.txt","a");
					if($step==1){
						fwrite($fh,$time."\n");
						fwrite($fp,"The ds ".$originip1." only has one user,needn't transfer!"."\n");
					}
					if($step==2){
						fwrite($fh,$time."\n");
						fwrite($fp,"The ds ".$originip1." overload, but there is no right to migrate users, please pay attention to see."."\n");
					}
					if($step ==3){
						fwrite($fh,$time."\n");
						fwrite($fp,"The ds ".$originip1." Overloaded, but all users will exceed the threshold causes the target server migration, please add server."."\n");
					}
					fclose($fp);
					echo "<script language=\"javascript\">window.close();</script>";
?>
