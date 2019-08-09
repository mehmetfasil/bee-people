<?php
defined("PASS") or die("Dosya yok!");

//Sayfayı oluşturuyoruz
$page = new page($_SESSION["SYS_USER_ID"], $_SESSION["SYS_USER_LEVEL"], CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS);

if ($_SESSION["SYS_USER_ID"] < 1) {
	//Login dosyası
	$page->showPageUsingFile("modules/login.php", label("WEBIM LOGIN PAGE"));
}elseif ($_SESSION["SYS_USER_LEVEL"] < 2){
 //Yetki seviyesi yeterli değil
	header("Location: ".CONF_MAIN_PAGE);
}else{
 //Yönetim sayfası
 $page->showPage((PID > 0 ? PID : menuID("ADMIN_HOMEPAGE")), SID, "Web-Im -");
}
?>