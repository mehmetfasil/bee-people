<?php
defined("PASS") or die("Dosya yok!");

function setOrders ($parent=0, $order=0, $id=0){
	//Çekilecek id leri array'e atalım
	$ids = array();

	//Varolan sırayı seç
	$query = "SELECT `id` "
	. "FROM `news_categories` "
	. "WHERE `parent`=".$parent." "
	. "ORDER BY `order`";
	$select = new query($query, CONN);
	while($row = $select->fetchobject()){
		if ($row->id != $id) {
			$ids[$row->id] = $row->id;
		}
	}

	//Yeni Sıra
	$t=1;

	foreach ($ids as $new_id){
		if ($t == $order) {
			$t++;
		}

		//Sorgusu
		new query("UPDATE `news_categories` SET `order`=".$t." WHERE `id`=".$new_id);

		//Normal sırayı artırıyoruz
		$t++;
	}
}

//Resim yolu
$path = "objects/assets/news/";

switch (ACT){
 case "list":
  
  $total = 0;
  
  //Sorgu
  $query = "SELECT COUNT(*) AS `total` "
  . "FROM `news` "
  . "WHERE 1=1";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= " AND INSTR(`title`, '".getvalue("keyword")."') > 0";
  }
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	$total = $row->total;
  }
  
  echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
  
  if ($total > 0) {
   //Sorgu
   $query = "SELECT `n`.`id`, DATE_FORMAT(`n`.`publish_date`, '%d-%m-%Y') AS `publish_date`, "
   . "DATE_FORMAT(`n`.`expire_date`, '%d-%m-%Y') AS `expire_date`, `c`.`title` AS `category`, "
   . "`n`.`title`, `n`.`subtitle`, `n`.`level`, `n`.`picture`, `n`.`description`, `n`.`active` "
   . "FROM `news` `n` "
   . "JOIN `news_categories` `c` ON (`n`.`category`=`c`.`id`) "
   . "WHERE 1=1 ";
   if (strlen(getvalue("keyword")) > 0) {
   	$query.= "AND INSTR(`n`.`title`, '".getvalue("keyword")."') > 0 ";
   }
 		$query.= "ORDER BY ";
 		switch (ORDERBY){
 		 case "publish_date":
 		  $query.= "`n`.`publish_date` ".ORDER." ";
 		  break;
 		 case "title":
 		  $query.= "`n`.`title` ".ORDER." ";
 		  break;
 		 case "category":
 		  $query.= "`c`.`title` ".ORDER." ";
 		  break;
 		 case "level":
 		  $query.= "`n`.`level` ".ORDER." ";
 		  break;
 		 case "status":
 		  $query.= "`n`.`active` ".ORDER." ";
 		  break;
 		 default:
 		  $query.= "`n`.`id` ".ORDER." ";
 		}
   $query.= "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
   $select = new query($query, CONN);
   
   echo "<list>";
   echo "<head>";
   echo "<title width=\"5%\" align=\"center\"><![CDATA[ID]]></title>";
   echo "<title><![CDATA[".label("NEWS")."]]></title>";
   echo "<title width=\"30%\"><![CDATA[".label("PROPERTIES")."]]></title>";
   echo "<title width=\"15%\" align=\"center\"><![CDATA[".label("NEWS STATUS")."]]></title>";
   echo "</head>";
   echo "<body>";
   
  	while ($row = $select->fetchobject()){
  	 echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
  	 echo "<value><![CDATA[".$row->id."]]></value>";
  	 echo "<value><![CDATA[";
  	 echo "<div style=\"display:inline; float:left\"><img src=\"".(is_null($row->picture) ? "objects/assets/default_image.png" : $row->picture)."\" width=\"110\" height=\"80\" align=\"left\" alt=\"\" style=\"padding:0 5px\" /></div> ";
  	 echo "<div style=\"display:inline;\"><b>".stripslashes($row->title)."</b><br />";
  	 if (!is_null($row->subtitle)) {
  	 	echo "<i>".stripslashes($row->subtitle)."</i><br />";
  	 }
  	 echo cropText($row->description, 250);
  	 echo "</div>";
  	 echo "]]></value>";
  	 echo "<value><![CDATA[";
  	 echo "<div class=\"question\"><label>".label("NEWS CATEGORY")."</label></div>";
  	 echo "<div class=\"answer\">".stripslashes($row->category)."</div>";
  	 echo "<div class=\"question\"><label>".label("PUBLISH DATE")."</label></div>";
  	 echo "<div class=\"answer\">".$row->publish_date.(!is_null($row->expire_date) ? " - ".$row->expire_date : "")."</div>";
  	 echo "]]></value>";
  	 echo "<value><![CDATA[".($row->active==1 ? label("OPEN") : label("CLOSE"))."]]></value>";
  	 echo "</row>";
  	}
  	
  	echo "</body>";
  	echo "</list>";
  }
  
  break;
 case "save":
  
  if (getvalue("category",0) > 0) {
  	if (strlen(getvalue("title")) > 0) {
  		if (isDate(getvalue("publishdate"))) {
  			if ((getvalue("unlimited",0) == 1) or isDate(getvalue("expiredate"))) {
  				if (strlen(getvalue("description")) > 0) {
       if (getvalue("id",0) > 0) {
        //ID
        $id = getvalue("id",0);
        
        //Update
       	$query = "UPDATE `news` SET "
       	. "`language`='".getvalue("language",LANG)."', "
       	. "`category`=".getvalue("category",0).", "
       	. "`title`='".mysql_escape_string(getvalue("title"))."', "
       	. "`subtitle`=".(strlen(getvalue("subtitle")) > 0 ? "'".mysql_escape_string(getvalue("subtitle"))."'" : "null").", "
       	. "`author`=".(strlen(getvalue("author")) > 0 ? "'".mysql_escape_string(getvalue("author"))."'" : "null").", "
       	. "`publish_date`=STR_TO_DATE('".getvalue("publishdate",date("d-m-Y"))."', '%d-%m-%Y'), "
       	. "`expire_date`=".(((getvalue("unlimited",0)==0) and isDate(getvalue("expiredate"))) ? "STR_TO_DATE('".getvalue("expiredate",date("d-m-Y"))."', '%d-%m-%Y')" : "null").", "
       	. "`level`=".getvalue("level",0).", "
       	. "`picture`=".(strlen(getvalue("picture")) > 0 ? "'".getvalue("picture")."'" : "null").", "
       	. "`description`='".mysql_escape_string(getvalue("description","",true))."', "
       	. "`active`='".getvalue("active",array(1,0))."' "
       	. "WHERE `id`=".getvalue("id",0);
       }else{
        //Insert
       	$query = "INSERT INTO `news` VALUES ("
       	. "null, "
       	. "NOW(), "
       	. "'".getvalue("language",LANG)."', "
       	. getvalue("category",0).", "
       	. "'".mysql_escape_string(getvalue("title"))."', "
       	. (strlen(getvalue("subtitle")) > 0 ? "'".mysql_escape_string(getvalue("subtitle"))."'" : "null").", "
       	. (strlen(getvalue("author")) > 0 ? "'".mysql_escape_string(getvalue("author"))."'" : "null").", "
       	. "STR_TO_DATE('".getvalue("publishdate",date("d-m-Y"))."', '%d-%m-%Y'), "
       	. (((getvalue("unlimited",0)==0) and isDate(getvalue("expiredate"))) ? "STR_TO_DATE('".getvalue("expiredate",date("d-m-Y"))."', '%d-%m-%Y')" : "null").", "
       	. getvalue("level",0).", "
       	. (strlen(getvalue("picture")) > 0 ? "'".getvalue("picture")."'" : "null").", "
       	. "'".mysql_escape_string(getvalue("description","",true))."', "
       	. "0, "
       	. "50, "
       	. "'".getvalue("active",array(1,0))."'"
       	. ")";
       }
       $save = new query($query, CONN);
       
       if ($save->affectedrows() > 0) {
        if (getvalue("id",0) < 1) {
         $id = $save->insertid();
        }
       	$i++;
       }
       
       if ($id > 0) {
        //Var mı?
        $exists = false;
        
        //Sorgusu
        $query = "SELECT `id` FROM `sys_content` WHERE `type`='NEWS' AND `id`=".$id;
        $select = new query($query, CONN);
        
        if ($select->numrows() > 0) {
        	$exists = true;
        }
        
       	if (strlen(getvalue("detail")) > 0) {
       	 if ($exists) {
       	 	//Update
       	 	$query = "UPDATE `sys_content` SET "
       	  . "`language`='".getvalue("language",LANG)."', "
       	  . "`text`='".mysql_escape_string(getvalue("detail","",true))."', "
       	  . "`modified`=NOW(), "
       	  . "`modified_by`=".$_SESSION["SYS_USER_ID"].", "
       	  . "`version`=(`version`+1) "
       	 	. "WHERE `type`='NEWS' "
       	 	. "AND `id`=".$id;
       	 }else{
       	  //Insert
       	  $query = "INSERT INTO `sys_content` VALUES ("
       	  . $id.", "
       	  . "'NEWS', "
       	  . "'".getvalue("language",LANG)."', "
       	  . "'".mysql_escape_string(getvalue("detail","",true))."', "
       	  . "NOW(), "
       	  . $_SESSION["SYS_USER_ID"].", "
       	  . "null, "
       	  . "null, "
       	  . "0, "
       	  . "0"
       	  . ")";
       	 }
       	 $save = new query($query, CONN);
       	 
       	 if ($save->affectedrows()) {
       	 	$i++;
       	 }
       	}elseif ($exists){
       	 //Silelim
       	 $query = "DELETE FROM `sys_content` WHERE `type`='NEWS' AND `id`=".$id;
       	 $delete = new query($query, CONN);
       	 
       	 if ($delete->affectedrows() > 0) {
       	 	$i++;
       	 }
       	}
       }
       
       if ($i > 0) {
  				  echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
       }else{
  				  echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
       }
  				}else{
  				 echo "<result status=\"ERROR\"><![CDATA[".label("NEWS DESCRIPTION MUST BE FILLED")."]]></result>";
  				}
  			}else{
  				echo "<result status=\"ERROR\"><![CDATA[".label("EXPIRE DATE MUST BE FILLED")."]]></result>";
  			}
  		}else{
  			echo "<result status=\"ERROR\"><![CDATA[".label("INVALID DATE")."]]></result>";
  		}
  	}else{
 			echo "<result status=\"ERROR\"><![CDATA[".label("NEWS TITLE EMPTY")."]]></result>";
  	}
  }else{
			echo "<result status=\"ERROR\"><![CDATA[".label("NEWS CATEGORY MUST BE SELECTED")."]]></result>";
  }
  
  break;
 case "values":
  
  $query = "SELECT `n`.`id`, `n`.`language`, `n`.`category`, "
  . "`c`.`title` AS `categoryTitle`, `n`.`title`, `n`.`author`, "
  . "DATE_FORMAT(`n`.`publish_date`, '%d-%m-%Y') AS `publish_date`, "
  . "DATE_FORMAT(`n`.`expire_date`, '%d-%m-%Y') AS `expire_date`, "
  . "`n`.`level`, `n`.`picture`, `n`.`description`, "
  . "`t`.`text` AS `detail`, `n`.`hit`, `n`.`rate`, `n`.`active` "
  . "FROM `news` `n` "
  . "JOIN `news_categories` `c` ON (`n`.`category`=`c`.`id`) "
  . "LEFT JOIN `sys_content` `t` ON (`n`.`id`=`t`.`id` AND `t`.`type`='NEWS') "
  . "WHERE `n`.`id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	echo "<value target=\"language\"><![CDATA[".$row->language."]]></value>";
  	echo "<value target=\"category\"><![CDATA[".$row->category."]]></value>";
  	echo "<value target=\"categoryTitle\"><![CDATA[".stripslashes($row->categoryTitle)."]]></value>";
  	echo "<value target=\"title\"><![CDATA[".stripslashes($row->title)."]]></value>";
  	echo "<value target=\"author\"><![CDATA[".stripslashes($row->author)."]]></value>";
  	echo "<value target=\"publishdate\"><![CDATA[".$row->publish_date."]]></value>";
  	echo "<value target=\"unlimited\"><![CDATA[".(is_null($row->expire_date) ? 1 : 0)."]]></value>";
  	echo "<value target=\"expiredate\"><![CDATA[".$row->expire_date."]]></value>";
  	echo "<value target=\"level\"><![CDATA[".$row->level."]]></value>";
  	echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
  	echo "<value target=\"hit\"><![CDATA[".$row->hit."]]></value>";
  	echo "<value target=\"rate\"><![CDATA[".$row->rate."]]></value>";
  	echo "<value target=\"description\"><![CDATA[".stripslashes($row->description)."]]></value>";
  	if (!is_null($row->picture)){
   	echo "<value target=\"picture\"><![CDATA[".$row->picture."]]></value>";
   	echo "<value function=\"eval\"><![CDATA[getEl('pictureImg').src='".$row->picture."']]></value>";
  	 echo "<value function=\"eval\"><![CDATA[getEl('pictureName').innerHTML='".basename($row->picture)." <a href=\"javascript:void(0)\" onclick=\"f.removePicture()\"><img src=\"objects/icons/16x16/drop.png\" width=\"16\" height=\"16\" alt=\"\" /></a>']]></value>";
  	}
  	if (!is_null($row->detail)) {
   	echo "<value target=\"detail\"><![CDATA[".stripslashes($row->detail)."]]></value>";
  	}
  	echo "<value function=\"eval\"><![CDATA[f.expiredateCheck()]]></value>";
  	echo "<value function=\"eval\"><![CDATA[f.setHTML()]]></value>";
  }
  
  break;
 case "delete":
  
  //Silelim gitsin
  $query = "DELETE FROM `news` WHERE `id`=".getvalue("id",0);
  $delete = new query($query, CONN);
  
  if ($delete->affectedrows() > 0) {
  	$query = "DELETE FROM `sys_content` WHERE `type`='NEWS' AND `id`=".getvalue("id",0);
  	new query($query, CONN);
  	
  	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  }else{
  	echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  }
  
  break;
 case "pictures":
  
  //Resimler
  $images = getFiles($path, "", array(".jpg"));
  
  foreach ($images as $image){
   echo "<list><![CDATA[".$path.$image."]]></list>";
  }
  
  break;
 case "delpic":
  
  if (strlen(getvalue("picture")) > 0) {
  	if (file_exists(CONF_DOCUMENT_ROOT.getvalue("picture"))) {
  	 if (unlink(CONF_DOCUMENT_ROOT.getvalue("picture"))){
   	 echo "<result status=\"OK\" />";
  	 }else{
   	 echo "<result status=\"ERROR\"><![CDATA[".label("AN ERROR OCCURED WHILE DELETING")."]]></result>";
  	 }
  	}else{
  	 echo "<result status=\"ERROR\"><![CDATA[".label("FILE NOT EXISTS")."]]></result>";
  	}
  }else{
 	 echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  }
  
  break;
 case "categories":
  
  switch (STEP){
   case "list":
    
    $total = 0;
    
    //Sorgu
    $query = "SELECT COUNT(*) AS `total` "
    . "FROM `news_categories` "
    . "WHERE 1=1";
    if (strlen(getvalue("keyword")) > 0) {
    	$query.= " AND INSTR(`title`, '".getvalue("keyword")."') > 0";
    }
    $select = new query($query, CONN);
    
    if ($select->numrows() > 0) {
    	$row = $select->fetchobject();
    	$total = $row->total;
    }
    
    echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
    
    if ($total > 0) {
     //Sorgu
     $query = "SELECT `s1`.`id`, IFNULL(`s2`.`title`, '.root') AS `parentTitle`, "
     . "`s1`.`name`, `s1`.`title`, `s1`.`icon`, `s1`.`active` "
     . "FROM `news_categories` `s1` "
     . "LEFT JOIN `news_categories` `s2` ON (`s1`.`parent`=`s2`.`id`) "
     . "WHERE 1=1 ";
     if (strlen(getvalue("keyword")) > 0) {
     	$query.= "AND INSTR(`s1`.`title`, '".getvalue("keyword")."') > 0 ";
     }
     $query.= "ORDER BY `s1`.`id` DESC "
     . "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
     $select = new query($query, CONN);
     
     echo "<list>";
     echo "<head>";
     echo "<title width=\"5%\" align=\"center\"><![CDATA[ID]]></title>";
     echo "<title width=\"20%\" align=\"center\"><![CDATA[".label("CATEGORY PARENT")."]]></title>";
     echo "<title width=\"20%\" align=\"center\"><![CDATA[".label("CATEGORY NAME")."]]></title>";
     echo "<title><![CDATA[".label("CATEGORY TITLE")."]]></title>";
     echo "<title width=\"15%\" align=\"center\"><![CDATA[".label("CATEGORY STATUS")."]]></title>";
     echo "</head>";
     echo "<body>";
     
    	while ($row = $select->fetchobject()){
    	 echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
    	 echo "<value><![CDATA[".$row->id."]]></value>";
    	 echo "<value><![CDATA[".stripslashes($row->parentTitle)."]]></value>";
    	 echo "<value><![CDATA[".stripslashes($row->name)."]]></value>";
    	 echo "<value><![CDATA[<b>".stripslashes($row->title)."</b>]]></value>";
    	 echo "<value><![CDATA[".($row->active==1 ? label("OPEN") : label("CLOSE"))."]]></value>";
    	 echo "</row>";
    	}
    	
    	echo "</body>";
    	echo "</list>";
    }
    
    break;
   case "save":
  
    if (strlen(getvalue("name")) > 0) {
     if (strlen(getvalue("title")) > 0) {
      if (getvalue("id",0) > 0) {
       //Update
       $query = "UPDATE `news_categories` SET "
       . "`parent`=".getvalue("parent",0).", "
       . "`name`='".mysql_escape_string(getvalue("name"))."', "
       . "`title`='".mysql_escape_string(getvalue("title"))."', "
       . "`description`=".(strlen(getvalue("description")) > 0 ? "'".mysql_escape_string(getvalue("description"))."'" : "null").", "
       . "`icon`='".mysql_escape_string(getvalue("icon"))."', "
       . "`order`=".getvalue("order",1).", "
       . "`active`='".getvalue("active",array(1,0))."' "
       . "WHERE `id`=".getvalue("id",0);
      }else{
       //Insert
       $query = "INSERT INTO `news_categories` VALUES ("
       . "null, "
       . getvalue("parent",0).", "
       . "'".mysql_escape_string(getvalue("name"))."', "
       . "'".mysql_escape_string(getvalue("title"))."', "
       . (strlen(getvalue("description")) > 0 ? "'".mysql_escape_string(getvalue("description"))."'" : "null").", "
       . "'".getvalue("icon")."', "
       . getvalue("order",1).", "
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
      echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY TITLE CANNOT BE BLANK")."]]></result>";
     }
    }else{
     echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY NAME CANNOT BE BLANK")."]]></result>";
    }
  
    break;
   case "values":
    
    $query = "SELECT `c1`.`id`, `c1`.`parent`, `c1`.`name`, "
    . "IFNULL(`c2`.`title`, '.root') AS `parentTitle`, "
    . "`c1`.`title`, `c1`.`description`, `c1`.`icon`, `c1`.`order`, `c1`.`active` "
    . "FROM `news_categories` `c1` "
    . "LEFT JOIN `news_categories` `c2` ON (`c1`.`parent`=`c2`.`id`) "
    . "WHERE `c1`.`id`=".getvalue("id",0);
    $select = new query($query, CONN);
    
    if ($select->numrows() > 0) {
    	$row = $select->fetchobject();
    	
    	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
    	echo "<value target=\"parent\"><![CDATA[".$row->parent."]]></value>";
    	echo "<value target=\"parentTitle\"><![CDATA[".stripslashes($row->parentTitle)."]]></value>";
    	echo "<value target=\"name\"><![CDATA[".stripslashes($row->name)."]]></value>";
    	echo "<value target=\"title\"><![CDATA[".stripslashes($row->title)."]]></value>";
    	echo "<value target=\"description\"><![CDATA[".stripslashes($row->description)."]]></value>";
    	echo "<value target=\"icon\"><![CDATA[".$row->icon."]]></value>";
    	echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
    	echo "<value function=\"eval\"><![CDATA[getEl('iconImg').src='objects/icons/16x16/".$row->icon."']]></value>";
    	echo "<value function=\"eval\"><![CDATA[f.setOrders(".$row->order.")]]></value>";
    }
    
    break;
   case "delete":
    
  		//Sıralama için parent'ı çekelim ve varlığını da kontrol etmiş olalım
  		$query = "SELECT `parent` FROM `news_categories` WHERE `id`=".getvalue("id",0);
  		$select = new query($query, CONN);
  
  		if ($select->numrows() > 0) {
  			$row = $select->fetchobject();
  			$parent = $row->parent;
  
  			//Kontrol edelim alt menüsü var mı?
  			$query = "SELECT `id` FROM `news_categories` WHERE `parent`=".getvalue("id",0);
  			$select = new query($query, CONN);
  
  			if ($select->numrows() > 0) {
  				echo "<result status=\"ERROR\"><![CDATA[".label("THIS CATEGORY IS PARENT OF ANOTHER CATEGORY")."]]></result>";
  			}else{
  				//Menüyü silelim
  				$query = "DELETE FROM `news_categories` WHERE `id`=".getvalue("id",0);
  				$delete = new query($query, CONN);
  
  				if ($delete->affectedrows() > 0) {
  					//Sıralayalım
  					setOrders($parent);
      	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  				}else{
  					echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")."]]></result>";
  				}
  			}
  		}else{
  			echo "<result status=\"ERROR\"><![CDATA[".label("THIS CATEGORY IS NOT EXISTS")."]]></result>";
  		}
    
    break;
   case "orders":
  	 
    //Sorgusu
  		$query = "SELECT `title` "
  		. "FROM `news_categories` "
  		. "WHERE `id` > 0 "
  		. "AND `parent`=".getvalue("parent",0)." "
  		. "AND `id`!=".getvalue("id",0)." "
  		. "AND `active`='1' "
  		. "ORDER BY `order`, `id`";
  		$select = new query($query, CONN);
  
  		echo "<list id=\"1\"><![CDATA[".label("AT THE BEGINNING")."]]></list>";
  
  		$i=2;
  		while ($row = $select->fetchobject()) {
  			echo "<list id=\"".$i."\"><![CDATA[".label("AFTER {".stripslashes($row->title)."}")."]]></list>";
  			$i++;
  		}
  		
    break;
   case "parents":
  
  		$tmp = array();
  
  		//Sorgusu
  		$query = "SELECT `id`, `parent`, `title` "
  		. "FROM `news_categories` "
  		. "WHERE `active`='1' "
  		. "ORDER BY `parent`, `order`";
  		$select = new query($query);
  		while ($row = $select->fetchobject()) {
  			$tmp[$row->parent][$row->id] = label(stripslashes($row->title));
  		}
  
  		//Array
  		$result = sortList($tmp, current(array_keys($tmp)), 0);
  
  		//Sonuç
  		foreach ($result as $id=>$value){
  			echo "<list id=\"".$id."\" level=\"".$value["level"]."\">";
  			echo "<![CDATA[".$value["title"]."]]>";
  			echo "</list>";
  		}
  		
    break;
  	case "check":
  	 
  	 $query = "SELECT `id` "
  	 . "FROM `news_categories` "
  	 . "WHERE `id`!='".getvalue("id",0)."' "
  	 . "AND `name`='".getvalue("name")."'";
  	 $select = new query($query, CONN);
  	 
  	 if ((strlen(getvalue("name")) == 0) or ($select->numrows() > 0)) {
  	 	echo "<result status=\"ERROR\" />";
  	 }else{
  	 	echo "<result status=\"OK\" />";
  	 }
  	 
  	 break;
  }
  
  break;
}
?>