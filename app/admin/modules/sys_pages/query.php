<?php
defined("PASS") or die("Dosya yok!");

switch (ACT){
 case "save":
  
  //Güncelleyelim
  $query = "UPDATE `sys_content` SET "
  . "`text`='".mysql_escape_string(getvalue("text","",true))."', "
  . "`modified`=NOW(), "
  . "`modified_by`=".$_SESSION["SYS_USER_ID"].", "
  . "`version`=(`version`+1) "
  . "WHERE `type`='PAGE' "
  . "AND `language`='".getvalue("language",DEFAULT_LANGUAGE)."' "
  . "AND `id`=".getvalue("id",0);
  $save = new query($query, CONN);
  
  if ($save->affectedrows() > 0) {
  	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
  }else{
  	echo "<result status=\"ERROR\"><![CDATA[".label("NOT SAVED")."]]></result>";
  }
  
  break;
 case "values":
  
  $languages = getLanguages();
  
  //Sorgusu
  $query = "SELECT `p`.`id`, `p`.`caption`, `c`.`language`, `c`.`text` "
  . "FROM `sys_pages` `p` "
  . "LEFT JOIN `sys_content` `c` ON (`p`.`id`=`c`.`id` "
  . "AND `c`.`type`='PAGE' "
  . "AND `c`.`language`='".getvalue("language",DEFAULT_LANGUAGE)."') "
  . "WHERE `p`.`source`='html' "
  . "AND `p`.`id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	$id = $row->id;
  	$caption = label(stripslashes($row->caption));
  	$language = $row->language;
  	$text = stripslashes($row->text);
  	
  	if (is_null($language)){
  	 $language = getvalue("language",DEFAULT_LANGUAGE);
    $text = label("UNDER CONSTRUCTION",getvalue("language",DEFAULT_LANGUAGE));
   	
  	 //Bu dil için
   	$query = "INSERT INTO `sys_content` VALUES ("
   	. $id.", "
   	. "'PAGE', "
   	. "'".$language."', "
   	. "'".label("UNDER CONSTRUCTION",getvalue("language",DEFAULT_LANGUAGE))."', "
   	. "NOW(), "
   	. $_SESSION["SYS_USER_ID"].", "
   	. "null, "
   	. "null, "
   	. "0, "
   	. "0"
   	. ")";
   	$insert = new query($query, CONN);
   	
   	if ($insert->affectedrows() < 1){
    	$text = label("PAGE CONTENT NOT CREATED");
   	}
   	echo "<value function=\"eval\"><![CDATA[f.removeCreateText('".$language.$id."', 'x 0')]]></value>";
  	}
  	
  	echo "<value target=\"id\"><![CDATA[".$id."]]></value>";
  	echo "<value target=\"caption\"><![CDATA[".$caption."]]></value>";
  	echo "<value target=\"language\"><![CDATA[".$language."]]></value>";
  	echo "<value target=\"lang\"><![CDATA[(".$language.") ".$languages[$language]."]]></value>";
  	echo "<value target=\"text\"><![CDATA[".$text."]]></value>";
  }
  
  
  break;
 case "pages":
  
  //Sistemde varolan diller
  $languages = getLanguages();
  
		$tmp = array();

		//Sorgusu
		$query = "SELECT `p`.`id`, `m`.`caption` AS `menu_caption`, `p`.`caption` AS `page_caption`, "
		. "`m`.`icon`, `c`.`language`, `c`.`version`, `c`.`shown` "
		. "FROM `sys_menus` `m` "
		. "INNER JOIN `sys_pages` `p` ON (`m`.`id`=`p`.`mid`) "
		. "LEFT JOIN `sys_content` `c` ON (`p`.`id`=`c`.`id` AND `c`.`type`='PAGE') "
		. "WHERE `m`.`active`='1' "
		. "AND `p`.`source`='html' ";
		if (strlen(getvalue("keyword")) > 0) {
			$query.= "AND ((INSTR(`m`.`caption`, '".getvalue("keyword")."') > 0) "
			. "OR (INSTR(`p`.`caption`, '".getvalue("keyword")."') > 0)) ";
		}
		$query.= "ORDER BY `p`.`id` DESC, `p`.`caption`";
		$select = new query($query);
		while ($row = $select->fetchobject()) {
			$tmp[$row->id]["icon"] = ($row->icon=="spacer.gif" ? "objects/icons/".$row->icon : "objects/icons/16x16/".$row->icon);
			$tmp[$row->id]["caption"] = label(stripslashes($row->menu_caption))." / ".label(stripslashes($row->page_caption));
			$tmp[$row->id]["languages"][$row->language]["version"] = $row->version;
			$tmp[$row->id]["languages"][$row->language]["shown"] = number_format($row->shown, 0, null, ".");
		}

		//Sonuç
		foreach ($tmp as $id=>$values){
			echo "<list id=\"".$id."\" icon=\"".$values["icon"]."\">";
			echo "<caption><![CDATA[".$values["caption"]."]]></caption>";
			echo "<languages>";
			
			//Geçici olarak bir değişkene aktarıyoruz
			$tmp_languages = $languages;
			
			foreach ($values["languages"] as $language=>$properties){
 			echo "<language abbr=\"".$language."\" version=\"".$properties["version"]."\" shown=\"".$properties["shown"]."\">";
 			echo "<![CDATA[(".$language.") ".$languages[$language]."]]>";
 			echo "</language>";
 			
 			unset($tmp_languages[$language]);
			}
			
			foreach ($tmp_languages as $abbr=>$language){
 			echo "<language abbr=\"".$abbr."\" version=\"0\">";
 			echo "<![CDATA[(".$abbr.") ".$language."]]>";
 			echo "</language>";
			}
			
			echo "</languages>";
			echo "</list>";
		}
  
  break;
 default:
  //
}
?>