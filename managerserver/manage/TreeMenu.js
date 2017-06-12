 
 
 function   ShowMenu(MenuID)             
 {             
		  if(MenuID.style.display=="none")             
		  {             
		  MenuID.style.display="";             
		  }             
		  else             
		  {             
		  MenuID.style.display="none";             
           } 
  } 
  
  
  function Querydir(MenuID)
  {     
              
		 window.location="query.php?id="+MenuID;
  }
  
  
 
  function Keyquerydir(MenuID)
  {  
		 window.location="keyquery.php?id="+MenuID;
  }
  
  
   function Createtimequerydir(MenuID)
  {  
	 window.location="createtimequery.php?id="+MenuID;
  }
  
  
  function Modifytimequerydir(MenuID)
  { 
	 window.location="modifytimequery.php?id="+MenuID;
  }
  
  
    function Visittimequerydir(MenuID)
  {  
	 window.location="visittimequery.php?id="+MenuID;
  }
  
  
  function Advancedquerydir(MenuID)
  {  
	 window.location="advancedsearch.php?id="+MenuID;
  }
  
  function Dirquerydir(MenuID)
  {  
	 window.location="dirquery.php?id="+MenuID;
  }
  
  
  function Querydir_All(MenuID)
  { 
		 window.location="query_all.php?id="+MenuID;
  }
  
  
  
  
  
  
  
  