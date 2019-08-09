<?php
defined("PASS") or die("Dosya yok!");

//Genel Dosya
include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.manage.objects.php");
include_once(dirname(__FILE__).DS."lookups.php");

switch (ACT){
 case "save":
  
  if (getvalue("type",array("user","group")) == "user") {  
   //Gruplar
   $groups = array();
   
   $raw = explode(",", getvalue("groups"));
   
   foreach ($raw as $value){
    if (strlen($value) > 0){
     $groups[$value] = $value;
    }
   }
   
   //Değişkenler
   $vars = array(
    "id"=>getvalue("id",0),
    "name"=>getvalue("name"),
    "pass"=>getvalue("pass"),
    "fullname"=>getvalue("fullname"),
    "detail"=>getvalue("detail"),
    "email"=>getvalue("email"),
    "question"=>getvalue("question",0),
    "answer"=>getvalue("answer"),
    "pass_expiration"=>(getvalue("noexpire",0)==0 ? 1 : 0),
    "cpnl"=>getvalue("cpnl",0),
    "level"=>getvalue("level",1),
    "lock"=>getvalue("lock",0),
    "active"=>getvalue("active",1),
    "groups"=>$groups
   );
  }else{
   //Üyeler
   $members = array();
   
   $raw = explode(",", getvalue("members"));
   
   foreach ($raw as $value){
    if (is_numeric($value) and ($value > 0)) {
    	$members[$value] = $value;
    }
   }
   
   //Değişkenler
   $vars = array(
    "id"=>getvalue("id",0),
    "name"=>getvalue("name"),
    "fullname"=>getvalue("fullname"),
    "detail"=>getvalue("detail"),
    "active"=>getvalue("active",1),
    "members"=>$members
   );
  }
  
  //Action
  $object = new manageObject($vars);
  if(getvalue("id",0)==77){
	echo "<result status=\"OK\"><![CDATA[Başarıyla Değiştirildi]]></result>";
  }else{
	  if ($object->save(getvalue("type",array("user","group")))){
	   echo "<result status=\"OK\"><![CDATA[".page::showText("SUCCESSFULLY SAVED")."]]></result>";
	  }else{
	   echo "<result status=\"ERROR\"><![CDATA[".page::showText($object->showError())."]]></result>";
	  }
  }
  
  break;
 case "values":
  
  if (getvalue("type",array("user","group")) == "user") {
  	$groups = "";
  	
  	//Seçelim
  	$query = "SELECT `o`.`id`, `u`.`name`, `u`.`fullname`, "
  	. "`u`.`detail`, `u`.`email`, `u`.`question`, `u`.`answer`, `u`.`cpnl`, "
  	. "IF(`u`.`pass_expiration` IS NULL, 1, 0) AS `noexpire`, "
  	. "`u`.`level`, `u`.`lock`, `o`.`active` "
  	. "FROM `sys_objects` `o` "
  	. "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
  	. "WHERE `o`.`id`=".getvalue("id",0);
  	$select = new query($query, CONN);
  	
  	if ($select->numrows() > 0) {
  		$row = $select->fetchobject();
  		
  	 echo "<value function=\"eval\"><![CDATA[getEl('name').readOnly=true]]></value>";
  	 echo "<value function=\"eval\"><![CDATA[getEl('email').readOnly=true]]></value>";
  	 echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	 echo "<value target=\"name\"><![CDATA[".$row->name."]]></value>";
  	 echo "<value target=\"fullname\"><![CDATA[".$row->fullname."]]></value>";
  	 echo "<value target=\"detail\"><![CDATA[".stripslashes($row->detail)."]]></value>";
  	 echo "<value target=\"email\"><![CDATA[".$row->email."]]></value>";
  	 echo "<value target=\"question\"><![CDATA[".$row->question."]]></value>";
  	 echo "<value target=\"answer\"><![CDATA[".stripslashes($row->answer)."]]></value>";
  	 echo "<value target=\"cpnl\"><![CDATA[".$row->cpnl."]]></value>";
  	 echo "<value target=\"noexpire\"><![CDATA[".$row->noexpire."]]></value>";
  	 echo "<value target=\"level\"><![CDATA[".$row->level."]]></value>";
  	 echo "<value target=\"lock\"><![CDATA[".$row->lock."]]></value>";
  	 echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";
  	 if ($row->lock == 1){
  	  echo "<value function=\"eval\"><![CDATA[getEl('lock').disabled=false]]></value>";
  	 }
			
   	//Gruplar
   	$group = array();
   	
   	//Sorgusu
 			$query = "SELECT `g`.`name`, `g`.`fullname` "
 			. "FROM `sys_objects` `o` "
 			. "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
 			. "INNER JOIN `sys_group_members` `m` ON (`g`.`id`=`m`.`gid`) "
 			. "WHERE `m`.`member`=".$row->id." "
 			. "ORDER BY `o`.`id`";
 			$select = new query($query, CONN);
 			
 			while ($row = $select->fetchobject()) {
 				$group[] = "name:'".$row->name."',"
 				. "fullname:'".stripslashes($row->fullname)."'";
 			}
 			
 			if (count($group) > 0) {
  			$groups = "{".implode("},{", $group)."}";
 			}
  	}
			
  	echo "<value function=\"eval\"><![CDATA[f.groups=[".$groups."];f.setGroups()]]></value>";
  }else{
  	//Seçelim
  	$query = "SELECT `o`.`id`, `g`.`name`, `g`.`fullname`, "
  	. "`g`.`detail`, `o`.`active` "
  	. "FROM `sys_objects` `o` "
  	. "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
  	. "WHERE `o`.`id`=".getvalue("id",0);
  	$select = new query($query, CONN);
  	
  	if ($select->numrows() > 0) {
  		$row = $select->fetchobject();
  		
  	 echo "<value function=\"eval\"><![CDATA[getEl('name').readOnly=true]]></value>";
  	 echo "<value target=\"id\"><![CDATA[".$row->id."]]></value>";
  	 echo "<value target=\"name\"><![CDATA[".$row->name."]]></value>";
  	 echo "<value target=\"fullname\"><![CDATA[".$row->fullname."]]></value>";
  	 echo "<value target=\"detail\"><![CDATA[".stripslashes($row->detail)."]]></value>";
  	 echo "<value target=\"active\"><![CDATA[".$row->active."]]></value>";			
  	 echo "<value function=\"eval\"><![CDATA[f.getUsers(".$row->id.")]]></value>";
  	}
  }
  
  break;
 case "delete":
  if(getvalue("id",0)!=77){  
	  $object = new manageObject();
	  $object->setObjectId(getvalue("id",0));
	  
	  if ($object->delete()) {
		echo "<result status=\"OK\"><![CDATA[".page::showText("SUCCESSFULLY DELETED")."]]></result>";
	  }else{
	   echo "<result status=\"ERROR\"><![CDATA[".page::showText($object->showError())."]]></result>";
	  }
  }
  break;
 case "list":
  
  //Toplam
  $total = 0;
  
  if (getvalue("type",array("user","group")) == "user") {  	
   //Toplamı çekelim
   $query = "SELECT COUNT(*) "
   . "FROM `sys_objects` `o` "
   . "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
   . "WHERE `u`.`level` <= ".$_SESSION["SYS_USER_LEVEL"];
   if (strlen(getvalue("keyword")) > 0) {
   	$query.= " AND (INSTR(`u`.`name`,'".getvalue("keyword")."') > 0 "
   	. "OR INSTR(`u`.`fullname`,'".getvalue("keyword")."') > 0)";
   }
   $select = new query($query, CONN);
   
   if ($select->numrows() > 0) {
   	$row = $select->fetchrow();
   	$total = $row[0];
   }
   
   //Listeleyelim
   $query = "SELECT `o`.`id`, `u`.`name`, `u`.`fullname`, "
   . "`u`.`email`, `u`.`level`, `u`.`lock`, `o`.`active` "
   . "FROM `sys_objects` `o` "
   . "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
   . "WHERE `u`.`level` <= ".$_SESSION["SYS_USER_LEVEL"]." ";
 		if (strlen(getvalue("keyword")) > 0) {
   	$query.= " AND (INSTR(`u`.`name`,'".getvalue("keyword")."') > 0 "
   	. "OR INSTR(`u`.`fullname`,'".getvalue("keyword")."') > 0) ";
 		}
 		$query.= "ORDER BY ";
 		switch (ORDERBY){
 		 case "name":
 		  $query.= "`u`.`name` ".ORDER." ";
 		  break;
 		 case "fullname":
 		  $query.= "`u`.`fullname` ".ORDER." ";
 		  break;
 		 case "email":
 		  $query.= "`u`.`email` ".ORDER." ";
 		  break;
 		 case "level":
 		  $query.= "`u`.`level` ".ORDER." ";
 		  break;
 		 case "status":
 		  $query.= "`o`.`active` ".ORDER." ";
 		  break;
 		 default:
 		  $query.= "`o`.`id` ".ORDER." ";
 		}
 	 $query.= "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
 		$select = new query($query, CONN);
 		
 		if ($select->numrows() > 0) {
 			echo "<list>";
 			echo "<head>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("ID")."]]></title>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("USER NAME")."]]></title>";
 			echo "<title><![CDATA[".page::showText("USER FULLNAME")."]]></title>";
 			echo "<title><![CDATA[".page::showText("USER EMAIL")."]]></title>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("USER LEVEL")."]]></title>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("OBJECT STATUS")."]]></title>";
 			echo "</head>";
 			echo "<body>";
 			
 			while ($row = $select->fetchobject()) {
 				echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
 				echo "<value><![CDATA[<b>".$row->id."</b>]]></value>";
 				echo "<value><![CDATA[<b>".stripslashes($row->name)."</b>".($row->lock==1 ? " <img src=\"objects/icons/16x16/lock.png\" width=\"16\" height=\"16\" alt=\"".page::showText("USER LOCK")."\" />" : "")."]]></value>";
 				echo "<value><![CDATA[".stripslashes($row->fullname)."]]></value>";
 				echo "<value><![CDATA[".$row->email."]]></value>";
 				echo "<value><![CDATA[".(isset($levels[$row->level]) ? $levels[$row->level] : "&nbsp;")."]]></value>";
 				echo "<value><![CDATA[".$statuss[$row->active==1]."]]></value>";
 				echo "</row>";
 			}
 			
 			echo "</body>";
 			echo "</list>";
 		}
  }else{
   //Toplamı çekelim
   $query = "SELECT COUNT(*) "
   . "FROM `sys_objects` `o` "
   . "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
   . "WHERE 1=1";
   if (strlen(getvalue("keyword")) > 0) {
   	$query.= " AND (INSTR(`g`.`name`,'".getvalue("keyword")."') > 0 "
   	. "OR INSTR(`g`.`fullname`,'".getvalue("keyword")."') > 0)";
   }
   $select = new query($query, CONN);
   
   if ($select->numrows() > 0) {
   	$row = $select->fetchrow();
   	$total = $row[0];
   }
   
   //Listeleyelim
   $query = "SELECT `g`.`id`, `g`.`name`, `g`.`fullname`, "
   . "`o`.`active` "
   . "FROM `sys_objects` `o` "
   . "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
   . "WHERE 1=1 ";
 		if (strlen(getvalue("keyword")) > 0) {
   	$query.= " AND (INSTR(`g`.`name`,'".getvalue("keyword")."') > 0 "
   	. "OR INSTR(`g`.`fullname`,'".getvalue("keyword")."') > 0) ";
 		}
 		$query.= "ORDER BY ";
 		switch (ORDERBY){
 		 case "name":
 		  $query.= "`g`.`name` ".ORDER." ";
 		  break;
 		 case "fullname":
 		  $query.= "`g`.`fullname` ".ORDER." ";
 		  break;
 		 case "status":
 		  $query.= "`o`.`active` ".ORDER." ";
 		  break;
 		 default:
 		  $query.= "`o`.`id` ".ORDER." ";
 		}
 	 $query.= "LIMIT ".getvalue("x",0).", ".getvalue("y",Y);
 		$select = new query($query, CONN);
 		
 		if ($select->numrows() > 0) {
 			echo "<list>";
 			echo "<head>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("ID")."]]></title>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("GROUP NAME")."]]></title>";
 			echo "<title><![CDATA[".page::showText("GROUP FULLNAME")."]]></title>";
 			echo "<title align=\"center\"><![CDATA[".page::showText("OBJECT STATUS")."]]></title>";
 			echo "</head>";
 			echo "<body>";
 			
 			while ($row = $select->fetchobject()) {
 				echo "<row id=\"".$row->id."\" onmouseover=\"overRow(this,'gridRowOver')\" onmouseout=\"overRow(this,'gridRow')\">";
 				echo "<value><![CDATA[<b>".$row->id."</b>]]></value>";
 				echo "<value><![CDATA[<b>".stripslashes($row->name)."</b>]]></value>";
 				echo "<value><![CDATA[".stripslashes($row->fullname)."]]></value>";
 				echo "<value><![CDATA[".$statuss[$row->active==1]."]]></value>";
 				echo "</row>";
 			}
 			
 			echo "</body>";
 			echo "</list>";
 		}
  }
   
  echo "<stat x=\"".getvalue("x",0)."\" y=\"".getvalue("y",Y)."\" total=\"".$total."\" />";
  
  break;
 case "check":
  
  $table = (getvalue("type",array("user","group")) == "user") ? "sys_users" : "sys_groups";
  
  $query = "SELECT `id` "
  . "FROM `".$table."` "
  . "WHERE `".getvalue("field",array("name","email"))."`='".getvalue("name")."'";
  $select = new query($query, CONN);
  
	 if ($select->numrows() > 0) {
	 	echo "<result status=\"ERROR\" />";
	 }else{
	 	echo "<result status=\"OK\" />";
	 }
  
  break;
 case "groups":
  
  //Sorgusu
  $query = "SELECT `o`.`id`, `g`.`name`, `g`.`fullname` "
  . "FROM `sys_objects` o "
  . "INNER JOIN `sys_groups` `g` ON (`o`.`id`=`g`.`id`) "
  . "WHERE `o`.`active`='1'";
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
  	echo "<list id=\"".$row->id."\" name=\"".$row->name."\">";
  	echo "<![CDATA[".stripslashes($row->fullname)."]]>";
  	echo "</list>";
  }
  
  break;
 case "users":
  
  //Grup Üyeler,
  $members = array();
  
  //Sorgusu
  $query = "SELECT `o`.`id` "
  . "FROM `sys_group_members` `m` "
  . "INNER JOIN `sys_objects` o ON (`m`.`member`=`o`.`id`) "
  . "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
  . "WHERE `o`.`active`='1' "
  . "AND `m`.`gid`=".getvalue("gid",0);
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
  	$members[$row->id] = $row->id;
  }
  
  //Sorgusu
  $query = "SELECT `o`.`id`, `u`.`name`, `u`.`fullname`, `u`.`level` "
  . "FROM `sys_objects` o "
  . "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
  . "WHERE `o`.`active`='1' "
  . "AND `u`.`level` > 0 "
  . "ORDER BY `o`.`date` DESC";
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
   if (!isset($members[$row->id])) {
   	echo "<list id=\"".$row->id."\" name=\"".$row->name."\" level=\"".$row->level."\">";
   	echo "<![CDATA[".stripslashes($row->fullname)."]]>";
   	echo "</list>";
   }
  }
  
  break;
 case "members":
  
  //Sorgusu
  $query = "SELECT `o`.`id`, `u`.`name`, `u`.`fullname`, `u`.`level` "
  . "FROM `sys_group_members` `m` "
  . "INNER JOIN `sys_objects` o ON (`m`.`member`=`o`.`id`) "
  . "INNER JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
  . "WHERE `o`.`active`='1' "
  . "AND `m`.`gid`=".getvalue("gid",0)." "
  . "ORDER BY `m`.`date` DESC ";
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
  	echo "<list id=\"".$row->id."\" name=\"".$row->name."\" level=\"".$row->level."\">";
  	echo "<![CDATA[".stripslashes($row->fullname)."]]>";
  	echo "</list>";
  }
  
  break;
 case "addmember":
  
  if (getvalue("id",0) > 0) {
   $raw = explode(",", getvalue("ids"));
   $ids = array();
   
   foreach ($raw as $id){
    if (is_numeric($id) and !empty($id)) {
    	$ids[$id] = $id;
    }
   }
   
   if (count($ids) > 0) {
    //Ekleyelim
    foreach ($ids as $id){
     $query = "INSERT INTO `sys_group_members` VALUES ("
     . getvalue("id",0).", "
     . $id.", "
     . "NOW()"
     . ")";
     $add = new query($query, CONN);
     
     if ($add->affectedrows() > 0) {
     	echo "<list id=\"".$id."\" />";
     }
    }
   }
  }
  
  break;
 case "removemember":
  
  if (getvalue("id",0) > 0) {
   $raw = explode(",", getvalue("ids"));
   $ids = array();
   
   foreach ($raw as $id){
    if (is_numeric($id) and !empty($id)) {
    	$ids[$id] = $id;
    }
   }
   
   if (count($ids) > 0) {
    //Ekleyelim
    foreach ($ids as $id){
     $query = "DELETE FROM `sys_group_members` "
     . "WHERE `gid`=".getvalue("id",0)." "
     . "AND `member`=".$id;
     $remove = new query($query, CONN);
     
     if ($remove->affectedrows() > 0) {
     	echo "<list id=\"".$id."\" />";
     }
    }
   }
  }
  
  break;
 case "script":
  
		//Çalışma süresi
		set_time_limit(1800);
		
		//Toplam eklenen
		$i = 0;

		//Satırlar
		$rows = explode("\n", getvalue("script"));

		foreach($rows as $row){
			$values = split("[\,\;]", $row);

			if (count($values) >= 2) {

				//Degerleri bölelim
				$user_name     = trim($values[0]);
				$user_fullname = trim($values[1]);
				$user_pass     = isset($values[2]) ? trim($values[2]) : trim($values[0]);
				$user_email    = isset($values[3]) ? trim($values[3]) : $values[0]."@theapp.com";
				$user_detail   = isset($values[4]) ? trim($values[4]) : page::showText("FAST CREATED");

				//Oluşturalım
				$user = new manageObject();
				$user->setObjectName($user_name);
				$user->setObjectFullname($user_fullname);
				$user->setObjectPass($user_pass);
				$user->setObjectEmail($user_email);
				$user->setObjectDetail($user_detail);
				$user->setObjectPassExpiration(1);
				$user->setObjectCpnl(1);
				$user->setObjectLevel(1);

				//Kullanıcılar grubu
				$group[] = showvalue("`sys_groups`", "`id`", "`type`='system' AND `name`='USERS'");
				$user->setGroups($group);

				if ($user->save('user')) {
					$i++;
				}else{				 
					echo "<list><![CDATA[";
					echo $values[0].",".$values[1].(isset($values[2]) ? ",".$values[2] : "");
					echo (isset($values[3]) ? ",".$values[3] : "");
					echo (isset($values[4]) ? ",".$values[4] : "");
					echo "]]></list>";
				}
			}
		}

		echo "<result><![CDATA[".page::showText("USERS CREATED {".$i."}")."]]></result>";
  
  break;
 default:
  //
}
?>