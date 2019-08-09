<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract Download
 * @version 5.0
 */

defined("PASS") or die("Dosya yok!");

//İndirilmesi izin verilmeyenler
$extensions = array("php","sql");

$file = CONF_DOCUMENT_ROOT.trim(getvalue("file"));
$filename = basename($file);
$ext = str_replace(".", "", strtolower(strrchr($filename, ".")));

if (file_exists($file) and ((!in_array($ext, $extensions)) or ($_SESSION["SYS_USER_LEVEL"] > 0))) {
 //İndirelim..
 header("Pragma: public");
 header("Expires: 0");
 header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
 header("Cache-Control: private", false);
 header("Content-Type: application/octet-stream");
 header("Content-Disposition: attachment; filename=".str_replace(" ", "_", $filename).";");
 header("Content-Transfer-Encoding: binary");
 header("Content-Length: ".filesize($file));
 readfile($file);
}else{
 header("Location: ".CONF_MAIN_PAGE);
}
?>