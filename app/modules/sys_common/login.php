<?php
defined("PASS") or die("Dosya yok!");

header("Content-type: text/xml; charset=utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo "<content type=\"system\">";

switch (ACT){
	case "login":
			//Gerekli class
			include CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.login.php";
			
			$user_name=getvalue("user_name");
			$user_name=is_utf8($user_name) ? iconv("utf-8","iso-8859-9",$user_name) : $user_name;
			$user_pass=getvalue("user_pass","",true);
			$user_pass=is_utf8($user_pass) ? iconv("utf-8","iso-8859-9",$user_pass) : $user_pass;
			
			//$login = new login($user_name, $user_pass, false, "name", 10, 10);
            $login = new login($user_name, $user_pass, false, "email", 10, 10);
			
			//$login = new login(getvalue("user_name"), getvalue("user_pass","",true), false, "name", 10, 10);
			
			if ($login->doLogin()) {
				echo "<result status=\"OK\" >".$_SESSION["SYS_USER_ID"]."</result>";
			}else{
				echo "<result status=\"ERROR\" try=\"".(isset($_SESSION["SYS_LOGIN_TRY"]) ? $_SESSION["SYS_LOGIN_TRY"] : 0)."\"><![CDATA[".label($login->showError())."]]></result>";
			}
		break;
	case "stats":
		
		//Toplam giriş
		$query = "SELECT COUNT(*) AS `total` FROM `sys_sessions`";
		$select = new query($query);
		$row = $select->fetchobject();
		
		echo "<stat target=\"statTotal\">".$row->total."</stat>";
		
		unset($query);
		unset($select);
		unset($row);
		
		//Online giriş
		$query = "SELECT COUNT(*) AS `total` "
		. "FROM `sys_online` "
		. "WHERE `time` > DATE_SUB(NOW(), INTERVAL 1 MINUTE)";
		$select = new query($query);
		$row = $select->fetchobject();
			
		echo "<stat target=\"statOnline\">".$row->total."</stat>";
		
		unset($query);
		unset($select);
		unset($row);
		
		//Bugünkü ziyaretçi
		$query = "SELECT COUNT(*) AS `total` "
		. "FROM `sys_sessions` "
		. "WHERE DATE_FORMAT(`time`, '%Y-%m-%d')=CURDATE()";
		$select = new query($query);
		$row = $select->fetchobject();
			
		echo "<stat target=\"statToday\">".$row->total."</stat>";

		break;
	default:
		//Hiçbir şey
}

echo "</content>";
?>