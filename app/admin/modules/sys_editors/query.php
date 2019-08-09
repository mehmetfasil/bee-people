<?php
defined("PASS") or die("Dosya yok!");

switch (ACT) {
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
		break;
}
?>