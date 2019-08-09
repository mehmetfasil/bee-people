<?php
defined("PASS") or die("Dosya yok!");

header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo "<content>";

switch (ACT){
 case "notes":
  
  switch (STEP){
   case "save":
    break;
   case "getvalues":
    break;
   case "delete":
    break;
   case "list":
    break;
  }
  
  break;
 case "errors":
		
  //Hata
		$error = function_exists("error_get_last") ? error_get_last() : array();

		if (count($error) > 0) {
   echo "<list>";
   echo "<body>";
  	
   echo "<row>";
			echo "<value><![CDATA[";
			echo "TYPE: ".$error["type"]."<br>";
			echo "MESSAGE: ".$error["message"]."<br>";
			echo "FILE: ".$error["file"]."<br>";
			echo "LINE: ".$error["line"];
			echo "]]></value>";
   echo "</row>";
			
   echo "</body>";
   echo "</list>";
		}
		
  break;
 case "onlines":
  
  $onlines = session::getOnline();
  
  if ($onlines > 0) {
   echo "<list>";
   echo "<body>";
  	
   foreach ($onlines as $values){
    echo "<row>";
    echo "<value rowspan=\"2\"><![CDATA[".$values["lasttime"]."]]></value>";
    echo "<value><![CDATA[<b>(".$values["username"].")</b> ".$values["fullname"]."]]></value>";
    echo "</row>";
    echo "<row>";
    echo "<value><![CDATA[URI: ".str_replace("&", " &", $values["lastpage"])."]]></value>";
    echo "</row>";
   }
   
   echo "</body>";
   echo "</list>";
  }
  
  break;
 case "lastaccess":
  
		$query = "SELECT DATE_FORMAT(`time`, '%d-%m-%Y %H:%i') AS `date`, "
		. "`ip`, IFNULL(`referer`, '-') AS referer "
		. "FROM `sys_sessions` "
		. "ORDER BY `time` DESC "
		. "LIMIT 0, 100";
		$select = new query($query);
		
		if ($select->numrows() > 0){
   echo "<list>";
   echo "<head>";
   echo "<title><![CDATA[".label("DATE")."]]></title>";
   echo "<title><![CDATA[".label("IP")." / ".label("REFERER")."]]></title>";
   echo "</head>";
   echo "<body>";
   
 		while ($row = $select->fetchobject()) {
 			echo "<row>";
			 echo "<value style=\"width:10%; white-space:nowrap\" rowspan=\"2\"><![CDATA[".$row->date."]]></value>";
			 echo "<value><![CDATA[".long2ip($row->ip)."]]></value>";
			 echo "</row>";
			 echo "<row>";
			 echo "<value><![CDATA[".stripslashes($row->referer)."]]></value>";
			 echo "</row>";
 		}
 		
 		echo "</body>";
 		echo "</list>";
		}
		
  break;
 default:
  //
}

echo "</content>";
?>