<?php
defined("PASS") or die("Dosya yok!");

include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.xml.tree.php");

switch (ACT){
	case "save":
		
		//Sayfa ID ve Kullanıcı ID
		$pids = array();
		$oids = array();
		
		$raw = explode(",", trim(getvalue("pages")));
		
		foreach ($raw as $page){
		 $tmp = explode("-", trim($page));
		 if ((count($tmp) == 2) and (strlen($tmp[1]) == 8)) {
		 	$pids[$tmp[0]] = $tmp[1];
		 }
		}
		
		$raw2 = explode(",", trim(getvalue("objects")));
		
		foreach ($raw2 as $object){
		 $oids[trim($object)] = trim($object);
		}
		
		//Silinen
		$d = 0;
		//Eklenen
		$i = 0;
		//Toplam kullanıcı
		$o = 0;
		
		//Kullanıcılara göre işlem yapalım
		foreach ($oids as $oid){
		 //Önce gelen id'lerin yetkilerini silelim
		 $query = "DELETE FROM `sys_permissions` "
		 . "WHERE `oid`=".$oid." "
		 . "AND `pid` IN (".implode(",", array_keys($pids)).")";
		 if((int)$oid==77)
			continue;
		 $delete = new query($query, CONN);
		 
		 if ($delete->affectedrows() > 0) {
		 	$d = $delete->affectedrows();
		 }
		 
		 unset($query);
		 
		 //Ekleyelim
		 $i=0;
		 foreach ($pids as $pid=>$permission){
 		 if (($permission{0} == 1) or (substr($permission,4) > 0)) {
 		 	$query = "INSERT INTO `sys_permissions` VALUES ("
 		 	. $oid.", "
 		 	. $pid.", "
 		 	. "'".substr($permission,0,4)."', "
 		 	. "'".substr($permission,4)."'"
 		 	. ")";
 		 	$insert = new query($query, CONN);
 		 	
 		 	if ($insert->affectedrows() > 0) {
 		 		$i++;
 		 	}
 		 }
		 }
		 
		 $o++;
		}
		
		if ($i > 0) {
		 echo "<result status=\"OK\"><![CDATA[".page::showText("USERS AUTHORIZED TO PAGES {".$o."} {".$i."}")."]]></result>";
		}elseif ($d > 0){
		 echo "<result status=\"OK\"><![CDATA[".page::showText("PAGE PERMISSIONS REMOVED {".$d."}")."]]></result>";
		}else{
		 echo "<result status=\"ERROR\"><![CDATA[".page::showText("NO ACTION TAKEN")."]]></result>";
		}
		
		break;
	case "menus":

		//Geçici
		$tmp = array();

		//Sorgusu
		$query = "SELECT `id`, `parent`, `caption` "
		. "FROM `sys_menus` "
		. "WHERE `active`='1' "
		. "AND `id` > 0 "
		. "AND `type`='".getvalue("type",array("site","admin"))."' "
		. "ORDER BY `parent`, `order`, `id`";
		$select = new query($query, CONN);
		
		while ($row = $select->fetchobject()) {
			$tmp[$row->parent][$row->id] = page::showText(stripslashes($row->caption));
		}

		$result = sortList($tmp, current(array_keys($tmp)), 0);

		//Sonuç
		foreach ($result as $id=>$value){
			echo "<list id=\"".$id."\" ";
			echo "parent=\"".$value["parent"]."\" ";
			echo "level=\"".$value["level"]."\">";
			echo "<![CDATA[".$value["title"]."]]>";
			echo "</list>";
		}
	 
	 break;
	case "objects":
	 
	 //Gösterilecek türler
	 $types = array("user","group");
	 
	 if (getvalue("users",0) != 1) {
	 	array_shift($types);
	 }
	 
	 if ((count($types) > 1) and (getvalue("groups",0) != 1)) {
	 	array_pop($types);
	 }
		
		//Kullanıcı ve Gruplar
		$query = "SELECT * FROM "
		. "(("
		. "SELECT `o1`.`id`, `o1`.`type`, `u`.`name`, `u`.`fullname`, `u`.`level` "
		. "FROM `sys_objects` `o1` "
		. "INNER JOIN `sys_users` `u` ON (`o1`.`id`=`u`.`id`) "
		. "WHERE 1=1 "
  . "AND `u`.`level` > 0 "
		. "AND `o1`.`active`='1' "
//		. "LIMIT 0, 100"
		. ") UNION ALL ("
		. "SELECT `o2`.`id`, `o2`.`type`, `g`.`name`, `g`.`fullname`, 0 AS `level` "
		. "FROM `sys_objects` `o2` "
		. "INNER JOIN `sys_groups` `g` ON (`o2`.`id`=`g`.`id`) "
		. "WHERE 1=1 "
		. "AND `o2`.`active`='1' "
//		. "LIMIT 0, 100"
		. ")) `t` "
		. "WHERE `t`.`type` IN ('".implode("','", $types)."') ";
		if (getvalue("level", 0) > 0) {
 		$query.= "AND `t`.`level`=".getvalue("level", array_keys($levels))." ";
		}
		if (strlen(getvalue("keyword")) > 0) {
			$query.= "AND (INSTR(`t`.`name`, '".getvalue("keyword")."') > 0 "
			. "OR INSTR(`t`.`fullname`, '".getvalue("keyword")."') > 0) ";
		}
		$query.= "ORDER BY `id` DESC";
		$select = new query($query, CONN);
		
		while ($row = $select->fetchobject()) {
			echo "<list id=\"".$row->id."\" type=\"".$row->type."\" level=\"".$row->level."\">";
			echo "<name><![CDATA[".stripslashes($row->name)."]]></name>";
			echo "<fullname><![CDATA[".stripslashes($row->fullname)."]]></fullname>";
			echo "</list>";
		}
		
		break;
	case "pages":

	 $pages = array();
	 $objects = array();
	 $selected_id = 0;
	 
	 $raw = explode(",", trim(getvalue("objects")));
	 
	 foreach ($raw as $id) {
	 	if (is_numeric($id)){
	 	 $objects[$id] = $id;
	 	}
	 }
	 
	 //Menüler ve Sayfalar
	 $query = "SELECT `p`.`mid`, `p`.`id`, IF(`p`.`sid`='index', IFNULL(`p3`.`id`,0), `p2`.`id`) AS `parent`, "
	 . "`p`.`sid`, `m`.`caption` AS `menu_caption`, `p`.`caption` AS `page_caption`, `m`.`level` "
	 . "FROM `sys_menus` `m` "
	 . "JOIN `sys_pages` `p` ON (`m`.`id`=`p`.`mid`) "
	 . "JOIN `sys_pages` `p2` ON (`p`.`mid`=`p2`.`mid` AND `p2`.`sid`='index') "
	 . "LEFT JOIN `sys_menus` `m2` ON (`m`.`parent`=`m2`.`id` AND `m2`.`active`='1') "
	 . "LEFT JOIN `sys_pages` `p3` ON (`m2`.`id`=`p3`.`mid`) "
	 . "WHERE `m`.`active`='1' "
	 . "AND `m`.`level` <= ".$_SESSION["SYS_USER_LEVEL"]." "
	 . "ORDER BY `parent`, `m`.`order`, `m`.`id`, `p`.`id`";
	 $select = new query($query, CONN);
	 while ($row = $select->fetchobject()) {
	 	$pages[$row->id]["parent"]       = $row->parent;
	 	$pages[$row->id]["type"]         = $row->sid == "index" ? "menu" : "page";
	 	$pages[$row->id]["title"]        = $row->sid == "index" ? page::showText(stripslashes($row->menu_caption)) : page::showText(stripslashes($row->page_caption));
	 	$pages[$row->id]["level"]        = $row->level;
	 	$pages[$row->id]["permissions"]  = "0000";
	 	$pages[$row->id]["restrictions"] = "0000";
	 	
	 	if (($row->sid == "index") and ($row->mid == getvalue("mid",0))){
	 	 $selected_id = $row->id;
	 	}
	 }
	 
	 //Yetkiler
	 $query = "SELECT `pr`.`pid` AS `id`, `pr`.`permit`, `pr`.`restrict` "
	 . "FROM `sys_permissions` `pr` "
	 . "WHERE `pr`.`oid` IN (".implode(",", $objects).") "
	 . "AND `pr`.`pid` IN (".implode(",", array_keys($pages)).")";
	 $select = new query($query, CONN);
	 
	 while ($row = $select->fetchobject()) {
	 	$pages[$row->id]["permissions"] = !is_null($row->permit) ? str_pad($row->permit, 4, 0, STR_PAD_RIGHT) : "0000";
	 	$pages[$row->id]["restrictions"] = !is_null($row->permit) ? str_pad($row->restrict, 4, 0, STR_PAD_RIGHT) : "0000";
	 }
	 
	 //XML oluşturalım
	 $tree = new xmlTree($pages);
	 echo $tree->createXML($selected_id);
		
		break;
	case "permissions":
	 
	 $pages = array();
	 $objects = array();
	 
	 $raw = explode(",", trim(getvalue("objects")));
	 
	 foreach ($raw as $id) {
	 	if (is_numeric($id)){
	 	 $objects[$id] = $id;
	 	}
	 }
	 
	 $raw = explode(",", trim(getvalue("pages")));
	 
	 foreach ($raw as $id) {
	 	if (is_numeric($id)){
	 	 $pages[$id] = $id;
	 	}
	 }
	 
	 //Yetkiler
	 $query = "SELECT `pr`.`pid` AS `id`, `pr`.`permit`, `pr`.`restrict` "
	 . "FROM `sys_permissions` `pr` "
	 . "WHERE `pr`.`oid` IN (".implode(",", $objects).") "
	 . "AND `pr`.`pid` IN (".implode(",", $pages).")";
	 $select = new query($query, CONN);
	 
	 while ($row = $select->fetchobject()) {
	  $permissions = $row->permit;
	  $restrictions = $row->restrict;
	  
	  echo "<list target=\"pr".$row->id."\"><![CDATA[".@$permissions{0}."]]></list>";
	  echo "<list target=\"pw".$row->id."\"><![CDATA[".@$permissions{1}."]]></list>";
	  echo "<list target=\"pd".$row->id."\"><![CDATA[".@$permissions{2}."]]></list>";
	  echo "<list target=\"pu".$row->id."\"><![CDATA[".@$permissions{3}."]]></list>";
	  echo "<list target=\"rr".$row->id."\"><![CDATA[".@$restrictions{0}."]]></list>";
	  echo "<list target=\"rw".$row->id."\"><![CDATA[".@$restrictions{1}."]]></list>";
	  echo "<list target=\"rd".$row->id."\"><![CDATA[".@$restrictions{2}."]]></list>";
	  echo "<list target=\"ru".$row->id."\"><![CDATA[".@$restrictions{3}."]]></list>";
	 }
	 
	 break;
}
?>