<?php
defined("PASS") or die("Dosya yok!");

$default_logo = "templates/sepett/images/default_brand_logo.png";

switch (ACT){
 case "list":
  
  $total = 0;
  
  //Sorgu
  $query = "SELECT COUNT(*) AS `total` "
  . "FROM `sepet_markalar` "
  . "WHERE 1=1";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= " AND INSTR(`isim`, '".getvalue("keyword")."') > 0";
  }
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	$total = $row->total;
  }
  
  echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
  
  //Sorgu
  $query = "SELECT `id`, `isim`, `aciklama`, `logo`, `aktif` "
  . "FROM `sepet_markalar` "
  . "WHERE 1=1 ";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= "AND INSTR(`isim`, '".getvalue("keyword")."') > 0 ";
  }
  $query.= "ORDER BY `id` DESC "
  . "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
   
   echo "<list>";
   echo "<head>";
   echo "<title width=\"5%\" align=\"center\"><![CDATA[ID]]></title>";
   echo "<title width=\"30%\"><![CDATA[".label("BRAND NAME")."]]></title>";
   echo "<title><![CDATA[".label("BRAND DESCRIPTION")."]]></title>";
   echo "<title width=\"15%\" align=\"center\"><![CDATA[".label("BRAND STATUS")."]]></title>";
   echo "</head>";
   echo "<body>";
   
  	while ($row = $select->fetchobject()){
  	 echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
  	 echo "<value><![CDATA[".$row->id."]]></value>";
  	 echo "<value><![CDATA[".stripslashes($row->isim)."]]></value>";
  	 echo "<value><![CDATA[".cropText($row->aciklama)."]]></value>";
  	 echo "<value><![CDATA[".($row->aktif==1 ? label("OPEN") : label("CLOSE"))."]]></value>";
  	 echo "</row>";
  	}
  	
  	echo "</body>";
  	echo "</list>";
  }
  
  break;
 case "save":

  if (strlen(getvalue("isim")) > 0) {
   if (getvalue("id",0) > 0) {
    //Update
    $query = "UPDATE `sepet_markalar` SET "
    . "`isim`='".mysql_escape_string(getvalue("isim"))."', "
    . "`aciklama`=".(strlen(getvalue("aciklama")) > 0 ? "'".mysql_escape_string(getvalue("aciklama"))."'" : "null").", "
    . "`url`=".(strlen(getvalue("url")) > 0 ? "'".mysql_escape_string(getvalue("url"))."'" : "null").", "
    . "`logo`=".(strlen(getvalue("logo")) > 0 ? "'".mysql_escape_string(getvalue("logo"))."'" : "null").", "
    . "`aktif`='".getvalue("aktif",array(1,0))."' "
    . "WHERE `id`=".getvalue("id",0);
   }else{
    //Insert
    $query = "INSERT INTO `sepet_markalar` VALUES ("
    . "null, "
    . "'".mysql_escape_string(getvalue("isim"))."', "
    . (strlen(getvalue("aciklama")) > 0 ? "'".mysql_escape_string(getvalue("aciklama"))."'" : "null").", "
    . (strlen(getvalue("url")) > 0 ? "'".mysql_escape_string(getvalue("url"))."'" : "null").", "
    . (strlen(getvalue("logo")) > 0 ? "'".mysql_escape_string(getvalue("logo"))."'" : "null").", "
    . "'".getvalue("aktif",array(1,0))."'"
    . ")";
   }
   
   $save = new query($query, CONN);
   
   if ($save->affectedrows() > 0) {
    echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
   }else{
    echo "<result status=\"ERROR\"><![CDATA[".label("NOT SAVED")." (".$save->showError().")]]></result>";
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("BRAND NAME CANNOT BE BLANK")."]]></result>";
  }

  break;
 case "values":
  
  $query = "SELECT * FROM `sepet_markalar` WHERE `id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	echo "<value target=\"isim\"><![CDATA[".stripslashes($row->isim)."]]></value>";
  	echo "<value target=\"aciklama\"><![CDATA[".stripslashes($row->aciklama)."]]></value>";
  	echo "<value target=\"url\"><![CDATA[".stripslashes($row->url)."]]></value>";
  	echo "<value target=\"aktif\"><![CDATA[".$row->aktif."]]></value>";
  	echo "<value target=\"logo\"><![CDATA[".$row->logo."]]></value>";
  	echo "<value function=\"eval\"><![CDATA[getEl('logoImg').src='".((strlen($row->logo) > 0 and file_exists(CONF_DOCUMENT_ROOT.$row->logo)) ? $row->logo : $default_logo)."']]></value>";
  }
  
  break;
 case "delete":
  
  //Resmi çekelim varsa silelim
  $query = "SELECT `logo` FROM `sepet_markalar` WHERE `id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	if ((strlen($row->logo) > 0) and (strpos($row->logo, "objects/assets/logos/") !== false)) {
   	@unlink(CONF_DOCUMENT_ROOT.$row->logo);
  	}
  }
  
  //Kaydı silelim
  $query = "DELETE FROM `sepet_markalar` WHERE `id`=".getvalue("id",0);
  $delete = new query($query, CONN);
  
  if ($delete->affectedrows() > 0) {
  	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  }else{
  	echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  }
  
  break;
 case "get":

  echo "<picture><![CDATA[".(isset($_SESSION["values"]["picture"]) ? $_SESSION["values"]["picture"] : $default_logo)."]]></picture>";

  break;
 case "remove":

  unset($_SESSION["values"]);

  if (getvalue("logo") != $default_logo) {
   if (strpos(getvalue("logo"), "objects/assets/logos/") !== false){
    if (unlink(CONF_DOCUMENT_ROOT.getvalue("logo"))){
     //Tabloyu da güncelleyelim
     if (getvalue("id",0) > 0) {
      $query = "UPDATE `sepet_markalar` SET "
      . "`logo`=null "
      . "WHERE `id`=".getvalue("id",0);
      new query($query, CONN);
     }
     
     echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
    }else{
     echo "<result status=\"ERROR\"><![CDATA[".label("REMOVING DID NOT BE PROCESSED")."]]></result>";
    }
   }else{
    echo "<result status=\"ERROR\"><![CDATA[".label("WRONG PARAMETER")."]]></result>";
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("CANNOT DELETE")."]]></result>";
  }

  break;
}
?>