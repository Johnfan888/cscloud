	<?php
class Configuration
{
  private $configFile ="/srv/www/htdocs/www/config/config.txt";

  private $items = array();

  function _construct() { $this->parse(); }
//得到某个项的值
  function _get($id) { return $this->items[ $id ]; }
//设置某个项的值
  function _set($id,$v) 
  {
    $this->items[ $id ] = $v; 
	return $this->items[ $id ];
  }
//解析文件函数
  function parse()
  {
  
    $fh = fopen( $this->configFile, 'r' );
	
    while( $l = fgets( $fh ) )
    {
      if ( preg_match( '/^#/', $l ) == false )
      {
	    if(preg_match( '/^[\[]/', $l ) == false)
	    {
        preg_match( '/^(.*?)=(.*?)$/', $l, $found );
        $this->items[ $found[1] ] = $found[2];
		/*echo($this->items[ $found[1] ]);
		echo"&nbsp;";
		echo $found[1];
		echo"<br>";*/
	    }
		/*else{
		 echo "yes";
		
		}*/
	   }
    }
    fclose( $fh );
  }
//存入文件函数  
  function save()
  {
   
     $nf = '';
    $fh = fopen( $this->configFile, 'r' );
	 
    while( $l = fgets( $fh ) )
    {
      if (preg_match( '/^#/', $l ) == false)
      {
	     if(preg_match('/^[\[]/', $l ) == false)
	    {
        preg_match( '/^(.*?)=(.*?)$/', $l, $found );
        $nf .= $found[1]."=".$this->items[$found[1]]."\n";
		}
		else
		{
		 $nf .= $l;
		}
      }
      else
      {
        $nf .= $l;
      }
    }
    fclose( $fh );
    copy( $this->configFile, $this->configFile.'.bak' );
    $fh = fopen( $this->configFile, 'w' );
	fwrite( $fh, $nf );
    fclose( $fh );
  }


  
}
?>