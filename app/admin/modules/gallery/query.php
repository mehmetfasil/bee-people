<?php
defined("PASS") or die("Dosya yok!");

//Lookup Dosya
include_once(dirname(__FILE__).DS."lookups.php");

switch (ACT){
 case "list":
  
  $languages = getLanguages();
  
  //Sorgu
  $query = "SELECT `g`.`id`, `g`.`language`, `g`.`name`, `g`.`title`, "
  . "`g`.`description`, COUNT(`gp`.`id`) AS `total`, `g`.`active` "
  . "FROM `gallery` `g` "
  . "LEFT JOIN `gallery_pictures` `gp` ON (`g`.`id`=`gp`.`gallery`) "
  . "WHERE 1=1 ";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= "AND INSTR(`g`.`title`, '".getvalue("keyword")."') > 0 ";
  }
  $query.= "GROUP BY `g`.`id` "
  . "ORDER BY `g`.`id` DESC";
  $select = new query($query, CONN);
  
 	while ($row = $select->fetchobject()){
 	 echo "<list id=\"".$row->id."\">";
 	 echo "<value label=\"".label("GALLERY LANGUAGE")."\"><![CDATA[".$languages[$row->language]."]]></value>";
 	 echo "<value label=\"".label("GALLERY NAME")."\"><![CDATA[".stripslashes($row->name)."]]></value>";
 	 echo "<value label=\"".label("GALLERY TITLE")."\"><![CDATA[<b>".stripslashes($row->title)."</b>]]></value>";
 	 echo "<value id=\"total\" label=\"".label("TOTAL PICTURE")."\"><![CDATA[".$row->total."]]></value>";
 	 echo "<value label=\"".label("GALLERY STATUS")."\"><![CDATA[".($row->active==1 ? label("OPEN") : label("CLOSE"))."]]></value>";
 	 echo "</list>";
 	}
  
  break;
 case "save":

  if (strlen(getvalue("name")) > 0) {
   if (preg_match("/^[a-zA-Z0-9_]+$/", getvalue("name"))) {
    if (strlen(getvalue("title")) > 0) {
     if (getvalue("id",0) > 0) {
      //Update
      $query = "UPDATE `gallery` SET "
      . "`language`='".getvalue("language",LANG)."', "
      . "`name`='".mysql_escape_string(getvalue("name"))."', "
      . "`title`='".mysql_escape_string(getvalue("title"))."', "
      . "`description`=".(strlen(getvalue("description")) > 0 ? "'".mysql_escape_string(getvalue("description"))."'" : "null").", "
      . "`author`=".(strlen(getvalue("author")) > 0 ? "'".mysql_escape_string(getvalue("author"))."'" : "null").", "
      . "`size`='".getvalue("width",100)."|".getvalue("height",100)."|".getvalue("ratio",0)."', "
      . "`thumb_size`='".getvalue("thumb_width",getvalue("width",100))."|".getvalue("thumb_height",getvalue("height",100))."|".getvalue("thumb_ratio",getvalue("ratio",0))."', "
      . "`extension`='".getvalue("extension",array_keys($extensions))."', "
      . "`active`='".getvalue("active",array(1,0))."' "
      . "WHERE `id`=".getvalue("id",0);
     }else{
      //Insert
      $query = "INSERT INTO `gallery` VALUES ("
      . "null, "
      . "NOW(), "
      . "'".getvalue("language",LANG)."', "
      . "'".mysql_escape_string(getvalue("name"))."', "
      . "'".mysql_escape_string(getvalue("title"))."', "
      . (strlen(getvalue("description")) > 0 ? "'".mysql_escape_string(getvalue("description"))."'" : "null").", "
      . (strlen(getvalue("author")) > 0 ? "'".mysql_escape_string(getvalue("author"))."'" : "null").", "
      . "'".getvalue("width",100)."|".getvalue("height",100)."|".getvalue("ratio",0)."', "
      . "'".getvalue("thumb_width",getvalue("width",100))."|".getvalue("thumb_height",getvalue("height",100))."|".getvalue("thumb_ratio",getvalue("ratio",0))."', "
      . "'".getvalue("extension",array_keys($extensions))."', "
      . "'".getvalue("active",array(1,0))."'"
      . ")";
     }
     
     $save = new query($query, CONN);
     
     if ($save->affectedrows() > 0) {
      echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
     }else{
      echo "<result status=\"ERROR\"><![CDATA[".label("NOT SAVED")." (".$save->showError().")]]></result>";
     }
    }else{
     echo "<result status=\"ERROR\"><![CDATA[".label("GALLERY TITLE CANNOT BE BLANK")."]]></result>";
    }
   }else{
    echo "<result status=\"ERROR\"><![CDATA[".label("GALLERY NAME CONTAINS INVALID CHARACTERS")."]]></result>";
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("GALLERY NAME CANNOT BE BLANK")."]]></result>";
  }

  break;
 case "values":
  
  $query = "SELECT * "
  . "FROM `gallery` "
  . "WHERE `id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	list($width, $height, $ratio) = explode("|", $row->size);
  	list($thumb_width, $thumb_height, $thumb_ratio) = explode("|", $row->thumb_size);
  	
  	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	echo "<value target=\"language\"><![CDATA[".$row->language."]]></value>";
  	echo "<value target=\"name\"><![CDATA[".stripslashes($row->name)."]]></value>";
  	echo "<value target=\"title\"><![CDATA[".stripslashes($row->title)."]]></value>";
  	echo "<value target=\"description\"><![CDATA[".stripslashes($row->description)."]]></value>";
  	echo "<value target=\"author\"><![CDATA[".stripslashes($row->author)."]]></value>";
  	echo "<value target=\"width\"><![CDATA[".@$width."]]></value>";
  	echo "<value target=\"height\"><![CDATA[".@$height."]]></value>";
  	echo "<value target=\"ratio\"><![CDATA[".@$ratio."]]></value>";
  	echo "<value target=\"thumb_width\"><![CDATA[".@$thumb_width."]]></value>";
  	echo "<value target=\"thumb_height\"><![CDATA[".@$thumb_height."]]></value>";
  	echo "<value target=\"thumb_ratio\"><![CDATA[".@$thumb_ratio."]]></value>";
  	echo "<value target=\"extension\"><![CDATA[".$row->extension."]]></value>";
  	echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
  }
  
  break;
 case "delete":
  
  //Galeri bilgisi
  $query = "SELECT `name` FROM `gallery` WHERE `id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
   $row = $select->fetchobject();
   $gallery_name = $row->name;
   
 		//Galeriyi silelim
 		$query = "DELETE FROM `gallery` WHERE `id`=".getvalue("id",0);
 		$delete = new query($query, CONN);
 
 		if ($delete->affectedrows() > 0) {
 		 //Resimleri çekelim ve silelim
 		 $query = "SELECT `path` FROM `gallery_pictures` WHERE `gallery`=".getvalue("id",0);
 		 $select = new query($query, CONN);
 		 
 		 while ($row = $select->fetchobject()) {
 		 	@unlink(CONF_DOCUMENT_ROOT.str_replace(basename($row->path), "thumbs/".basename($row->path), $row->path));
 		 	@unlink(CONF_DOCUMENT_ROOT.$row->path);
 		 }
 		 
 		 @unlink(CONF_DOCUMENT_ROOT."objects".DS."assets".DS."gallery".DS.$gallery_name.DS."thumbs".DS);
 		 @unlink(CONF_DOCUMENT_ROOT."objects".DS."assets".DS."gallery".DS.$gallery_name.DS);
 		 
 		 $query = "DELETE FROM `gallery_pictures` WHERE `gallery`=".getvalue("id",0);
 		 new query($query);
 		 
   	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
 		}else{
 			echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
 		}
  }
  
  break;
	case "check":
	 
	 $query = "SELECT `id` "
	 . "FROM `gallery` "
	 . "WHERE `id`!='".getvalue("id",0)."' "
	 . "AND `name`='".getvalue("name")."'";
	 $select = new query($query, CONN);
	 
	 if ((strlen(getvalue("name")) == 0) or ($select->numrows() > 0)) {
	 	echo "<result status=\"ERROR\" />";
	 }else{
	 	echo "<result status=\"OK\" />";
	 }
	 
	 break;
	case "pictures":
	 
	 $query = "SELECT `id`, `name`, `title`, `size`, `thumb_size`, `extension` "
	 . "FROM `gallery` "
	 . "WHERE `id`=".getvalue("id",0);
	 $select = new query($query, CONN);
	 
	 if ($select->numrows() > 0) {
	 	$row = $select->fetchobject();
	 	
	 	echo "<info id=\"".$row->id."\" ";
	 	echo "name=\"".$row->name."\" ";
	 	echo "image_sizes=\"".$row->size."|".$row->thumb_size."\" ";
	 	echo "image_extension=\"".$row->extension."\" ";
	 	echo "image_name=\"".@$extensions[$row->extension]."\">";
	 	echo "<![CDATA[".stripslashes($row->title)."]]>";
	 	echo "</info>";
	 	
	 	//Galeri Resimleri
 	 $query = "SELECT `id`, `title`, `description`, `path`, `active` "
 	 . "FROM `gallery_pictures` "
 	 . "WHERE `gallery`=".$row->id." "
 	 . "ORDER BY `id` DESC";
 	 $select = new query($query, CONN);
 	 
 	 while ($row = $select->fetchobject()) {
 	 	echo "<list id=\"".$row->id."\">";
 	 	echo "<value target=\"title\"><![CDATA[".stripslashes($row->title)."]]></value>";
 	 	echo "<value target=\"description\"><![CDATA[".stripslashes($row->description)."]]></value>";
 	 	echo "<value target=\"path\"><![CDATA[";
 	 	$filename = $row->path;
 	 	if (!file_exists($filename)){
 	 	 echo "objects/icons/128x128/stop.png";
 	 	}else{
 	 	 echo $filename;
 	 	}
 	 	echo "]]></value>";
 	 	echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
 	 	echo "</list>";
 	 }
	 }
	 
	 break;
	case "picture":
	 
	 if (getvalue("id",0) > 0) {
 	 if (STEP == "save"){
 	  //Güncelleyelim
 	  $query = "UPDATE `gallery_pictures` SET "
 	  . "`title`=".(strlen(getvalue("title")) > 0 ? "'".mysql_escape_string(getvalue("title"))."'" : "null").", "
 	  . "`description`=".(strlen(getvalue("description")) > 0 ? "'".mysql_escape_string(getvalue("description"))."'" : "null").", "
 	  . "`active`='".getvalue("active",array(1,0))."' "
 	  . "WHERE `id`=".getvalue("id",0);
 	  $save = new query($query, CONN);
 	  
 	  if ($save->affectedrows() > 0) {
  	  echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
 	  }else{
  	  echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
 	  }
 	 }elseif (STEP == "delete"){
 	  //Path'i alalım
 	  $query = "SELECT `gallery`, `path` FROM `gallery_pictures` WHERE `id`=".getvalue("id",0);
 	  $select = new query($query, CONN);
 	  
 	  if ($select->numrows() > 0) {
 	   $row = $select->fetchobject();
 	   $id = $row->gallery;
 	   $path = $row->path;
 	   
  	  //Silelim
  	  $query = "DELETE FROM `gallery_pictures` WHERE `id`=".getvalue("id",0);
  	  $delete = new query($query, CONN);
  	  
  	  if ($delete->affectedrows() > 0) {
  	  	@unlink(CONF_DOCUMENT_ROOT.$path);
  	  	@unlink(CONF_DOCUMENT_ROOT.str_replace(basename($path), "thumbs/".basename($path), $path));
  	   
  	  	echo "<result id=\"".$id."\" status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  	  }else{
  	   echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  	  }
 	  }
 	 }
	 }
	 
	 break;
	case "xml":
	 
	 //Sorgusu
	 $query = "SELECT `id`, `name`, `title` FROM `gallery` WHERE `id`=".getvalue("id",0);
	 $select = new query($query, CONN);
	 
	 if ($select->numrows() > 0) {
	 	$row = $select->fetchobject();
	 	
	 	$id = $row->id;
	 	$title = stripslashes($row->title);
	 	$path = "objects/assets/gallery/".$row->name."/";
	 	$thumb_path = "objects/assets/gallery/".$row->name."/thumbs/";
	 	$xml_path = CONF_DOCUMENT_ROOT.$path."gallery.xml";
	 	
	 	$handle = fopen($xml_path, "w");
	 	
	 	if ($handle) {
	 	 $content = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n"
	 	 . "<simpleviewerGallery maxImageWidth=\"800\" maxImageHeight=\"600\" textColor=\"0xffffff\" frameColor=\"0xffffff\" frameWidth=\"10\" stagePadding=\"0\" thumbnailColumns=\"10\" thumbnailRows=\"1\" navPosition=\"top\" title=\"".$title."\" enableRightClickOpen=\"false\" backgroundImagePath=\"\" imagePath=\"".$path."\" thumbPath=\"".$thumb_path."\">\r\n";
	 	 
	 	 //Resimler
	 	 $query = "SELECT `title`, `path` "
	 	 . "FROM `gallery_pictures` "
	 	 . "WHERE `active`='1' "
	 	 . "AND `gallery`=".$id;
	 	 $select = new query($query, CONN);
	 	 while ($row = $select->fetchobject()) {
	 	 	$content.= "<image>\r\n"
	 	 	. "<filename>".basename($row->path)."</filename>\r\n"
	 	 	. "<caption>".stripslashes($row->title)."</caption>\r\n"
	 	 	. "</image>\r\n";
	 	 }
	 		$content.= "</simpleviewerGallery>";
	 	 
	 		if (fwrite($handle, $content)){
	 		 echo "<result status=\"OK\"><![CDATA[".label("FILE WRITTEN SUCCESSFULLY")."]]></result>";
	 		}
	 	}
	 	
	 	fclose($handle);
	 }
	 	 
	 break;
}
?>