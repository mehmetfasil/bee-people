<?php
defined("PASS") or die("Dosya yok!");

switch (ACT){
 case "values":
  
  //Sorgu
  $query = "SELECT * FROM `sys_configuration` WHERE `module`='CONF'";
  $select = new query($query, CONN);
  while ($row = $select->fetchobject()) {
  	echo "<value target=\"".$row->variable."\"><![CDATA[".stripslashes($row->value)."]]></value>";
  }
  echo "<value function=\"eval\"><![CDATA[f.checkSYSUser()]]></value>";
  
  break;
 case "save":
  
  new query("DELETE FROM `sys_configuration` WHERE `module`='CONF'");
  
  $raw = array();

		foreach ($_POST as $key=>$value){
			array_push($raw, "('CONF', '".$key."', '".getvalue($key,"",true)."')");
		}
		
		if (count($raw) > 0) {
			$query = "INSERT INTO `sys_configuration` VALUES "
			. implode(",", $raw);
			$save = new query($query, CONN);
			
			if ($save->affectedrows() > 0) {
				echo "<result status=\"OK\"><![CDATA[".label("SUCCESSFULLY SAVED")."]]></result>";
			}else{
				echo "<result status=\"ERROR\"><![CDATA[".label("NOT SAVED")."]]></result>";
			}
		}else{
			echo "<result status=\"ERROR\"><![CDATA[".label("NOT ENOUGH VALUES")."]]></result>";
		}
  
  break;
 case "check":
  
  //Sistem kullanıcıs
  $query = "SELECT `u`.`id`, `u`.`name`, `u`.`fullname` "
  . "FROM `sys_objects` `o` "
  . "JOIN `sys_users` `u` ON (`o`.`id`=`u`.`id`) "
  . "WHERE `o`.`active`='1' "
  . "AND `u`.`level`=0 "
  . "ORDER BY `u`.`name`";
  $select = new query($query, CONN);
  
  while ($row = $select->fetchobject()) {
  	echo "<list id=\"".$row->id."\"><![CDATA[(".stripslashes($row->name).") ".stripslashes($row->fullname)."]]></list>";
  }
  
  break;
 case "create":
  
  //Kullanıcı Class
  include_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.manage.objects.php");
  
		//Oluşturalım
		$user = new manageObject();
		$user->setObjectName("system");
		$user->setObjectFullname(label("SYSTEM USER"));
		$user->setObjectPass(md5(microtime()));
		$user->setObjectEmail("system@system.com");
		$user->setObjectDetail(label("SYSTEM USER"));
		$user->setObjectLevel(0);

		if ($user->save('user')) {
		 echo "<result status=\"OK\"><![CDATA[(system) ".label("SYSTEM USER")."]]></result>";
		}else{
		 echo "<result status=\"ERROR\"><![CDATA[".label("NO ACTION TAKEN")." (".label($user->showError()).")]]></result>";
		}
  
  break;
}
?>