<?php
defined("PASS") or die("Dosya yok!");

function setOrders ($parent=0, $order=0, $id=0){
	//Çekilecek id leri array'e atalım
	$ids = array();

	//Varolan sırayı seç
	$query = "SELECT `id` "
	. "FROM `sepet_kategoriler` "
	. "WHERE `parent`=".$parent." "
	. "ORDER BY `sira`";
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
		new query("UPDATE `sepet_kategoriler` SET `sira`=".$t." WHERE `id`=".$new_id);

		//Normal sırayı artırıyoruz
		$t++;
	}
}

switch (ACT){
 case "list":
  
  $total = 0;
  
  //Sorgu
  $query = "SELECT COUNT(*) AS `total` "
  . "FROM `sepet_kategoriler` "
  . "WHERE 1=1";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= " AND INSTR(`baslik`, '".getvalue("keyword")."') > 0";
  }
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	$total = $row->total;
  }
  
  echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
  
  //Sorgu
  $query = "SELECT `s1`.`id`, IFNULL(`s2`.`baslik`, '.root') AS `parentTitle`, "
  . "`s1`.`isim`, `s1`.`baslik`, `s1`.`simge`, `s1`.`aktif` "
  . "FROM `sepet_kategoriler` `s1` "
  . "LEFT JOIN `sepet_kategoriler` `s2` ON (`s1`.`parent`=`s2`.`id`) "
  . "WHERE 1=1 ";
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= "AND INSTR(`s1`.`baslik`, '".getvalue("keyword")."') > 0 ";
  }
  $query.= "ORDER BY `s1`.`id` DESC "
  . "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
   
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
  	 echo "<value><![CDATA[".stripslashes($row->isim)."]]></value>";
  	 echo "<value><![CDATA[<b>".stripslashes($row->baslik)."</b>]]></value>";
  	 echo "<value><![CDATA[".($row->aktif==1 ? label("OPEN") : label("CLOSE"))."]]></value>";
  	 echo "</row>";
  	}
  	
  	echo "</body>";
  	echo "</list>";
  }
  
  break;
 case "save":

  if (strlen(getvalue("isim")) > 0) {
   if (preg_match("/^[a-zA-Z0-9_]+$/", getvalue("isim"))) {
    if (strlen(getvalue("baslik")) > 0) {
     if (getvalue("simge") != "0") {
      if (getvalue("id",0) > 0) {
       //Update
       $query = "UPDATE `sepet_kategoriler` SET "
       . "`parent`=".getvalue("parent",0).", "
       . "`isim`='".mysql_escape_string(getvalue("isim"))."', "
       . "`baslik`='".mysql_escape_string(getvalue("baslik"))."', "
       . "`aciklama`=".(strlen(getvalue("aciklama")) > 0 ? "'".mysql_escape_string(getvalue("aciklama"))."'" : "null").", "
       . "`simge`='".mysql_escape_string(getvalue("simge"))."', "
       . "`sira`=".getvalue("sira",1).", "
       . "`aktif`='".getvalue("aktif",array(1,0))."' "
       . "WHERE `id`=".getvalue("id",0);
      }else{
       //Insert
       $query = "INSERT INTO `sepet_kategoriler` VALUES ("
       . "null, "
       . getvalue("parent",0).", "
       . "'".mysql_escape_string(getvalue("isim"))."', "
       . "'".mysql_escape_string(getvalue("baslik"))."', "
       . (strlen(getvalue("aciklama")) > 0 ? "'".mysql_escape_string(getvalue("aciklama"))."'" : "null").", "
       . "'".getvalue("simge")."', "
       . getvalue("sira",1).", "
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
      echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY ICON CANNOT BE BLANK")."]]></result>";
     }
    }else{
     echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY TITLE CANNOT BE BLANK")."]]></result>";
    }
   }else{
    echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY NAME CONTAINS INVALID CHARACTERS")."]]></result>";
   }
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label("CATEGORY NAME CANNOT BE BLANK")."]]></result>";
  }

  break;
 case "values":
  
  $query = "SELECT `s1`.`id`, `s1`.`parent`, `s1`.`isim`, "
  . "IFNULL(`s2`.`baslik`, '.root') AS `parentTitle`, "
  . "`s1`.`baslik`, `s1`.`aciklama`, `s1`.`simge`, `s1`.`sira`, `s1`.`aktif` "
  . "FROM `sepet_kategoriler` `s1` "
  . "LEFT JOIN `sepet_kategoriler` `s2` ON (`s1`.`parent`=`s2`.`id`) "
  . "WHERE `s1`.`id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	
  	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	echo "<value target=\"parent\"><![CDATA[".$row->parent."]]></value>";
  	echo "<value target=\"parentTitle\"><![CDATA[".stripslashes($row->parentTitle)."]]></value>";
  	echo "<value target=\"isim\"><![CDATA[".stripslashes($row->isim)."]]></value>";
  	echo "<value target=\"baslik\"><![CDATA[".stripslashes($row->baslik)."]]></value>";
  	echo "<value target=\"aciklama\"><![CDATA[".stripslashes($row->aciklama)."]]></value>";
  	echo "<value target=\"simge\"><![CDATA[".$row->simge."]]></value>";
  	echo "<value target=\"aktif\"><![CDATA[".$row->aktif."]]></value>";
  	echo "<value function=\"eval\"><![CDATA[getEl('simgeImg').src='objects/assets/icons/".$row->simge."']]></value>";
  	echo "<value function=\"eval\"><![CDATA[f.setOrders(".$row->sira.")]]></value>";
  }
  
  break;
 case "delete":
  
		//Sıralama için parent'ı çekelim ve varlığını da kontrol etmiş olalım
		$query = "SELECT `parent` FROM `sepet_kategoriler` WHERE `id`=".getvalue("id",0);
		$select = new query($query, CONN);

		if ($select->numrows() > 0) {
			$row = $select->fetchobject();
			$parent = $row->parent;

			//Kontrol edelim alt menüsü var mı?
			$query = "SELECT `id` FROM `sepet_kategoriler` WHERE `parent`=".getvalue("id",0);
			$select = new query($query, CONN);

			if ($select->numrows() > 0) {
				echo "<result status=\"ERROR\"><![CDATA[".label("THIS CATEGORY IS PARENT OF ANOTHER CATEGORY")."]]></result>";
			}else{
				//Kategoriyi silelim
				$query = "DELETE FROM `sepet_kategoriler` WHERE `id`=".getvalue("id",0);
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
		$query = "SELECT `baslik` "
		. "FROM `sepet_kategoriler` "
		. "WHERE `id` > 0 "
		. "AND `parent`=".getvalue("parent",0)." "
		. "AND `id`!=".getvalue("id",0)." "
		. "AND `aktif`='1' "
		. "ORDER BY `sira`, `id`";
		$select = new query($query, CONN);

		echo "<list id=\"1\"><![CDATA[".label("AT THE BEGINNING")."]]></list>";

		$i=2;
		while ($row = $select->fetchobject()) {
			echo "<list id=\"".$i."\"><![CDATA[".label("AFTER {".stripslashes($row->baslik)."}")."]]></list>";
			$i++;
		}
		
  break;
 case "parents":

		$tmp = array();

		//Sorgusu
		$query = "SELECT `id`, `parent`, `baslik` "
		. "FROM `sepet_kategoriler` "
		. "WHERE `aktif`='1' "
		. "ORDER BY `parent`, `sira`";
		$select = new query($query, CONN);
		while ($row = $select->fetchobject()) {
			$tmp[$row->parent][$row->id] = label(stripslashes($row->baslik));
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
	 . "FROM `sepet_kategoriler` "
	 . "WHERE `id`!='".getvalue("id",0)."' "
	 . "AND `isim`='".getvalue("isim")."'";
	 $select = new query($query, CONN);
	 
	 if ((strlen(getvalue("isim")) == 0) or ($select->numrows() > 0)) {
	 	echo "<result status=\"ERROR\" />";
	 }else{
	 	echo "<result status=\"OK\" />";
	 }
	 
	 break;
}
?>