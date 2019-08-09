<?php
defined("PASS") or die("Dosya yok!");

//Yol
$path = strlen(getvalue("path")) > 0 ? getvalue("path") : "";

$extension_list = array();
$extensions = "in";
$overwrite = getvalue("overwrite",0);

include_once(CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS."modules".DS."sys_common".DS."upload.php");
exit(0);
?>