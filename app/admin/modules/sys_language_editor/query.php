<?php
defined("PASS") or die("Dosya yok!");

switch (ACT){
 case "save":
  
  $head = array("ABBR"=>"","NAME"=>"","ENG_NAME"=>"","CHARSET"=>"","DIRECTION"=>"");
  $list = array();
  $row = array();
  
  //Öncelikle gelen değerleri düzene sokalım
  foreach ($_POST as $key=>$value){
   if (preg_match("/^key([0-9]+)$/", $key, $match)){
   	if (isset($_POST["value".@$match[1]])) {
   	 if (getvalue("type",array("php","js")) == "php"){
   	  $row[] = $value.":=".stripslashes($_POST["value".$match[1]]);
   	 }else{
   	  $row[] = "LANGUAGE['".$value."'] = '".$_POST["value".$match[1]]."';";
   	 }
   	}
   }elseif (in_array($key, array_keys($head))) {
   	$head[$key] = stripslashes($value);
   }
  }
  
  //Dosyayı açalım
  if ((strlen($head["ABBR"]) == 2) or (strlen(getvalue("language")) == 2)) {
   //Dil
   $language = ((strlen($head["ABBR"]) == 2) ? $head["ABBR"] : getvalue("language"));
   
   if (getvalue("type",array("php","js")) == "php") {
   	//Dosya adı
    $filename = CONF_DOCUMENT_ROOT."system".DS."languages".DS.$language.DS."language";
    
    //Başlığı
   	$header[] = "[ABBR=".$head["ABBR"].";NAME=".$head["NAME"].";ENG_NAME=".$head["ENG_NAME"].";CHARSET=".$head["CHARSET"].";DIRECTION=".$head["DIRECTION"].";]";
   	array_unshift($row, $header[0]);
   }else{
   	//Dosya adı
    $filename = CONF_DOCUMENT_ROOT."objects".DS."js".DS."languages".DS.$language.DS."language.js";
    
    //Başlığı
   	$header[] = "var LANGUAGE = {};";
   	array_unshift($row, $header[0]);
   }
  	
  	if (!file_exists($filename)) {
  		touch($filename);
  	}
  	
  	$fp = @fopen($filename, "w");
  	$result = @fwrite($fp, implode("\r\n", $row));
  	@fclose($fp);
  	
  	if ($result) {
    echo "<result status=\"OK\"><![CDATA[".label("FILE WRITTEN SUCCESSFULLY")."]]></result>";  	
  	}else{
    echo "<result status=\"ERROR\"><![CDATA[".label("AN ERROR OCCURED WHILE WRITING INTO FILE")."]]></result>";  	
  	}
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("LANGUAGE ABBREVATION NOT FOUND")."]]></result>";
  }
  
  break;
 case "delete":
  
  if (getvalue("language",DEFAULT_LANGUAGE) != DEFAULT_LANGUAGE) {
  	//PHP ve JS dosyası
  	$php_path = CONF_DOCUMENT_ROOT."system".DS."languages".DS.getvalue("language",DEFAULT_LANGUAGE).DS;
  	$php_filename = $php_path."language";
  	$js_path = CONF_DOCUMENT_ROOT."objects".DS."js".DS."languages".DS.getvalue("language",DEFAULT_LANGUAGE).DS;
  	$js_filename = $js_path."language.js";
  	
  	if (unlink($php_filename)) {
  	 echo "ee";
  	 unlink($php_path."index.php");
  		rmdir($php_path);
  	}
  	if (unlink($js_filename)) {
  	 unlink($js_path."index.php");
  		rmdir($js_path);
  	}
   
  	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("YOU CANNOT DELETE DEFAULT LANGUAGE")."]]></result>";
  }
  
  break;
 case "duplicate":
  
  $langs = getLanguages();
  $php_path = CONF_DOCUMENT_ROOT."system".DS."languages".DS;
  $js_path = CONF_DOCUMENT_ROOT."objects".DS."js".DS."languages".DS;
  
 	$source_php_filename = $php_path.getvalue("language",DEFAULT_LANGUAGE).DS."language";
 	
 	$js_files = getFiles($js_path.getvalue("language",DEFAULT_LANGUAGE).DS);
 	
 	$index_filename = $php_path.getvalue("language",DEFAULT_LANGUAGE).DS."index.php";
 	
 	if (!isset($langs[getvalue("new_abbr")])) {
 		//Klasörü oluşturalım
 		if (@mkdir($php_path.getvalue("new_abbr").DS, 0777)){
 		 @mkdir($js_path.getvalue("new_abbr").DS, 0777);
 		 
 		 foreach ($js_files as $file){
 		  @copy($js_path.getvalue("language",DEFAULT_LANGUAGE).DS.$file, $js_path.getvalue("new_abbr").DS.$file);
 		 }
 		 @copy($index_filename, $php_path.getvalue("new_abbr").DS."index.php");
 		 
		  $contents = file_get_contents($source_php_filename);
		  $pattern = "/^\[.*?\]/";
		  $head = "[ABBR=".getvalue("new_abbr").";NAME=".getvalue("new_name","unknown").";ENG_NAME=".getvalue("new_eng_name","unknown").";CHARSET=".getvalue("new_charset","utf-8").";DIRECTION=".getvalue("new_direction","ltr").";]";
		  
		  $contents = preg_replace($pattern, $head, $contents);
		  
		  @file_put_contents($php_path.getvalue("new_abbr").DS."language", $contents);
  	 
		  echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
 		}else{
  	 echo "<result status=\"ERROR\"><![CDATA[".label("AN ERROR OCCURED")."]]></result>";
 		}
 	}else{
 	 echo "<result status=\"ERROR\"><![CDATA[".label("LANGUAGE ALREADY EXISTS")."]]></result>";
 	}
  
  break;
}
?>