<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.manage.menus.php");
include_once(dirname(__FILE__).DS."lookups.php");

switch (ACT){
 case "save":
  
  //Alt sayfalar
  $subpages = array();
  
  $raw = explode("^", getvalue("subpages"));
  
  $i = 0;
  foreach ($raw as $values){
   $vals = explode("~", $values);
   
   if ((count($vals) == 10) and is_numeric($vals[0])) {
   	$subpages[$i]["id"]         = is_numeric($vals[0]) ? $vals[0] : 0;
   	$subpages[$i]["sid"]        = preg_replace("/[^a-zA-Z0-9_]/", "", $vals[1]);
   	$subpages[$i]["template"]   = $vals[2];
   	$subpages[$i]["title"]      = (strlen($vals[3]) > 0) ? $vals[3] : "INDEX";
   	$subpages[$i]["caption"]    = (strlen($vals[4]) > 0) ? $vals[4] : "INDEX";
   	$subpages[$i]["icon"]       = (strlen($vals[5]) > 0) ? $vals[5] : "spacer.gif";
   	$subpages[$i]["position"]   = in_array($vals[6], array("relative","absolute")) ? $vals[6] : "relative";
   	$subpages[$i]["visibility"] = in_array($vals[7], array("visible","hidden")) ? $vals[7] : "visible";
   	$subpages[$i]["source"]     = in_array($vals[8], array("none","file","html")) ? $vals[8] : "none";
   	$subpages[$i]["path"]       = $subpages[$i]["source"]=="file" ? ($vals[9]{0}=="/" ? substr($vals[9],1) : $vals[9]) : "";
   	$i++;
   }
  }
  
  //Değişkenler
  $vars = array(
   "id"=>getvalue("id",0),
   "parent"=>getvalue("parent",0),
   "type"=>getvalue("type",array("site","admin")),
   "name"=>getvalue("name","SITE_"),
   "icon"=>getvalue("icon","spacer.gif"),
   "caption"=>getvalue("caption"),
   "detail"=>getvalue("detail"),
   "link"=>getvalue("link"),
   "target"=>getvalue("target",array("_self","_blank")),
   "order"=>getvalue("order",1),
   "split"=>getvalue("split",0),
   "level"=>getvalue("level",0),
   "visibility"=>getvalue("visibility",array("visible","hidden")),
   "active"=>getvalue("active",1),
   "subpages"=>$subpages
  );
  

  
  $menu = new manageMenu($vars);
  
  if ($menu->save()){
   echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label($menu->showError())."]]></result>";
  }
  
  break;
 case "values":
  
  //Sorgu
  $query = "SELECT `m1`.`id`, `m1`.`parent`, IFNULL(`m2`.`caption`,'.root') AS `parentTitle`, "
  . "`m1`.`type`, `m1`.`name`, `m1`.`icon`, `m1`.`caption`, `m1`.`detail`, "
  . "`m1`.`link`, `m1`.`target`, `m1`.`order`, `m1`.`split`, `m1`.`level`, "
  . "`m1`.`visibility`, `m1`.`active` "
  . "FROM `sys_menus` `m1` "
  . "LEFT JOIN `sys_menus` `m2` ON (`m1`.`parent`=`m2`.`id`) "
  . "WHERE `m1`.`id`=".getvalue("id",0);
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchobject();
  	$type = $row->type;
  	
  	echo "<value target=\"type\"><![CDATA[".$row->type."]]></value>";
  	echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	echo "<value function=\"eval\"><![CDATA[f.setIDs(".$row->id.")]]></value>";
  	echo "<value function=\"eval\"><![CDATA[f.setTemplates(".($row->type=="site" ? "site_templates" : "admin_templates").")]]></value>";
  	echo "<value function=\"eval\"><![CDATA[getEl('iconImg').src='objects/icons/16x16/".($row->icon=="spacer.gif" ? "noicon.gif" : $row->icon)."']]></value>";
  	echo "<value target=\"icon\"><![CDATA[".$row->icon."]]></value>";
  	echo "<value target=\"split\"><![CDATA[".$row->split."]]></value>";
  	echo "<value target=\"visibility\"><![CDATA[".$row->visibility."]]></value>";
  	echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
  	echo "<value target=\"parent\"><![CDATA[".$row->parent."]]></value>";
  	echo "<value target=\"parentTitle\"><![CDATA[".label(stripslashes($row->parentTitle))."]]></value>";
  	echo "<value target=\"name\"><![CDATA[".$row->name."]]></value>";
  	echo "<value target=\"caption\"><![CDATA[".stripslashes($row->caption)."]]></value>";
  	echo "<value target=\"detail\"><![CDATA[".stripslashes($row->detail)."]]></value>";
  	echo "<value target=\"link\"><![CDATA[".$row->link."]]></value>";
  	echo "<value target=\"target\"><![CDATA[".$row->target."]]></value>";
  	echo "<value target=\"level\"><![CDATA[".$row->level."]]></value>";
  	echo "<value function=\"eval\"><![CDATA[f.setOrders(".$row->order.")]]></value>";
			
  	//Alt Sayfalar
  	$subpages = "";
  	$subpage = array();
  	
  	//Sorgusu
			$query = "SELECT `p`.`id`, `p`.`sid`, `p`.`template`, `p`.`title`, "
			. "`p`.`caption`, `p`.`icon`, `p`.`position`, `p`.`visibility`, "
			. "`p`.`source`, `f`.`file` "
			. "FROM `sys_pages` `p` "
			. "LEFT JOIN `sys_page_file` `f` ON (`p`.`id`=`f`.`pid`) "
			. "WHERE `p`.`mid`=".$row->id." "
			. "ORDER BY `p`.`id`";
			$select = new query($query, CONN);
			
			while ($row = $select->fetchobject()) {
				$subpage[] = "id:".$row->id.","
				. "sid:'".$row->sid."',"
				. "template:'".($row->position=="relative" ? $row->template : "-")."',"
				. "title:'".stripslashes($row->title)."',"
				. "caption:'".stripslashes($row->caption)."',"
				. "icon:'".$row->icon."',"
				. "position:'".$row->position."',"
				. "visibility:'".$row->visibility."',"
				. "source:'".$row->source."',"
				. "path:'".($row->source=="file" ? ($type=="admin" ? ADMIN_FOLDER."/" : "").$row->file : "")."'";
			}
			
			if (count($subpage) > 0) {
 			$subpages = "{".implode("},{", $subpage)."}";
			}
			
  	echo "<value function=\"eval\"><![CDATA[f.subpages = [".$subpages."];f.setSubpages()]]></value>";
  }
  
  break;
 case "delete":
  
  $menu = new manageMenu();
  $menu->setMenuId(getvalue("id",0));
  
  if ($menu->delete()) {
  	echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY DELETED")."]]></result>";
  }else{
   echo "<result status=\"ERROR\"><![CDATA[".label($menu->showError())."]]></result>";
  }
  
  break;
 case "list":
  
  //Toplam
  $total = 0;
  
  //Toplamı çekelim
  $query = "SELECT COUNT(*) "
  . "FROM `sys_menus` "
  . "WHERE `level` <= ".$_SESSION["SYS_USER_LEVEL"];
  if (strlen(getvalue("keyword")) > 0) {
  	$query.= " AND INSTR(`caption`,'".getvalue("keyword")."') > 0";
  }
  if (getvalue("type",array("0","site","admin")) != "0") {
  	$query.= " AND `type`='".getvalue("type",array("site","admin"))."'";
  }
  $select = new query($query, CONN);
  
  if ($select->numrows() > 0) {
  	$row = $select->fetchrow();
  	$total = $row[0];
  }
  
  echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
  
  //Listeleyelim
  $query = "SELECT `m1`.`id`, `m1`.`type`, `m1`.`caption`, "
  . "IFNULL(`m2`.`caption`,'.root') AS `parent`, "
  . "`m1`.`level`, `m1`.`active` "
  . "FROM `sys_menus` `m1` "
  . "LEFT JOIN `sys_menus` `m2` ON (`m1`.`parent`=`m2`.`id`) "
  . "WHERE `m1`.`level` <= ".$_SESSION["SYS_USER_LEVEL"]." ";
		if (strlen(getvalue("keyword")) > 0) {
			$query.= "AND INSTR(`m1`.`caption`, '".getvalue("keyword")."') > 0 ";
		}
  if (getvalue("type",array("0","site","admin")) != "0") {
  	$query.= "AND `m1`.`type`='".getvalue("type",array("site","admin"))."' ";
  }
		$query.= "ORDER BY ";
		switch (ORDERBY){
		 case "type":
		  $query.= "`m1`.`type` ".ORDER." ";
		  break;
		 case "caption":
		  $query.= "`m1`.`caption` ".ORDER." ";
		  break;
		 case "parent":
		  $query.= "`m2`.`caption` ".ORDER." ";
		  break;
		 case "level":
		  $query.= "`m1`.`level` ".ORDER." ";
		  break;
		 case "status":
		  $query.= "`m1`.`active` ".ORDER." ";
		  break;
		 default:
		  $query.= "`m1`.`id` ".ORDER." ";
		}
	 $query.= "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
		$select = new query($query, CONN);
		
		if ($select->numrows() > 0) {
			echo "<list>";
			echo "<head>";
			echo "<title align=\"center\"><![CDATA[".label("ID")."]]></title>";
			echo "<title align=\"center\"><![CDATA[".label("MENU TYPE")."]]></title>";
			echo "<title><![CDATA[".label("MENU CAPTION")."]]></title>";
			echo "<title><![CDATA[".label("MENU PARENT")."]]></title>";
			echo "<title align=\"center\"><![CDATA[".label("MENU LEVEL")."]]></title>";
			echo "<title align=\"center\"><![CDATA[".label("MENU STATUS")."]]></title>";
			echo "</head>";
			echo "<body>";
			
			while ($row = $select->fetchobject()) {
				echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
				echo "<value><![CDATA[<b>".$row->id."</b>]]></value>";
				echo "<value><![CDATA[".$types[$row->type]."]]></value>";
				echo "<value><![CDATA[".label(stripslashes($row->caption))."]]></value>";
				echo "<value><![CDATA[".label(stripslashes($row->parent))."]]></value>";
				echo "<value><![CDATA[".$levels[$row->level]."]]></value>";
				echo "<value><![CDATA[".$statuss[$row->active]."]]></value>";
				echo "</row>";
			}
			
			echo "</body>";
			echo "</list>";
		}
  
  break;
 case "ids":
  
		$raw = array();

		//Varolanlar
		$query = "SELECT `id` FROM `sys_menus` WHERE `id` > 0 ORDER BY `id`";
		$select = new query($query, CONN);
		while ($row = $select->fetchrow()) {
			$raw[] = $row[0];
		}

		for ($i=1; $i <= 200; $i++){
			if (!in_array($i, $raw)) {
				echo "<list id=\"".$i."\" />";
			}
		}
  
  break;
	case "orders":

	 //Sorgusu
		$query = "SELECT `caption` "
		. "FROM `sys_menus` "
		. "WHERE `id` > 0 "
		. "AND `parent`=".getvalue("parent",0)." "
		. "AND `id`!=".getvalue("id",0)." "
		. "AND `type`='".getvalue("type",array("site","admin"))."' "
		. "AND `active`='1' "
		. "ORDER BY `order`, `id`";
		$select = new query($query, CONN);

		echo "<list id=\"1\"><![CDATA[".label("AT THE BEGINNING")."]]></list>";

		$i=2;
		while ($row = $select->fetchobject()) {
			echo "<list id=\"".$i."\"><![CDATA[".label("MENU AFTER {".label(stripslashes($row->caption))."}")."]]></list>";
			$i++;
		}

		break;
	case "parents":

		$tmp = array();

		//Sorgusu
		$query = "SELECT `id`, `parent`, `caption` "
		. "FROM `sys_menus` "
		. "WHERE `active`='1' "
		. "AND `type`='".getvalue("type",array("site","admin"))."' "
		. "ORDER BY `parent`, `order`";
		$select = new query($query);
		while ($row = $select->fetchobject()) {
			$tmp[$row->parent][$row->id] = label(stripslashes($row->caption));
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
	 
	 $menuID = menuID(getvalue("name"));
	 
	 if ((strlen(getvalue("name")) == 0) or ($menuID != 0)) {
	 	echo "<result status=\"ERROR\" />";
	 }else{
	 	echo "<result status=\"OK\" />";
	 }
	 
	 break;
 case "files":

  $path = preg_replace("/^[\/]*?(.*)[\/]*?$/", "\\1", getvalue("path"));

  $folders = getfolders(CONF_DOCUMENT_ROOT.$path);

  foreach ($folders as $folder){
   echo "<list type=\"folder\" path=\"".$path.$folder."/\">".$folder."</list>";
  }

  $files = getfiles(CONF_DOCUMENT_ROOT.$path);

  foreach ($files as $file){
   echo "<list type=\"file\" path=\"".$path.$file."\">".$file."</list>";
  }

  break;
 default:
  //
}
?>