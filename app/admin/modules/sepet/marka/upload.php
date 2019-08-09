<?php
defined("PASS") or die("Dosya yok!");

//Yol
$raw_path = "objects/assets/logos/";
$path = CONF_DOCUMENT_ROOT.$raw_path;

if (!file_exists($path)) {
	@mkdir($path, 0777);
}

$extension_list = array("png");
$extensions = "in";
$overwrite = 1;

//Upload Dosyası
include_once(CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS."modules".DS."sys_common".DS."upload.php");

$_SESSION["values"]["picture"] = $raw_path.$file_name;
exit(0);
?>