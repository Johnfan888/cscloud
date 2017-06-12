	<?php
class Configuration
{
  private $configFile ="/srv/www/htdocs/www/config/config.txt";

  private $items = array();

  function _construct() { $this->parse(); }
//�õ�ĳ�����ֵ
  function _get($id) { return $this->items[ $id ]; }
//����ĳ�����ֵ
  function _set($id,$v) 
  {
    $this->items[ $id ] = $v; 
	return $this->items[ $id ];
  }
//�����ļ�����
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
//�����ļ�����  
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