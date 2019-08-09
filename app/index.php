<?php
/**
 * @copyright Masters 2019
 * @author Mehmet FASIL
 * @abstract Index
 * @company Bee INC
 * @version 1.0
 */
 
define("PASS", "OK");

//Oturum
session_start();
session_regenerate_id();

//Versiyon
define("AUTHOR", "Mehmet FASIL");
define("VERSION", "1.0.0");

define("DS", DIRECTORY_SEPARATOR);
define("CONF_DOCUMENT_ROOT", dirname(__FILE__).DS);

require_once(CONF_DOCUMENT_ROOT."includes.php");

$page = new page($_SESSION["SYS_USER_ID"], $_SESSION["SYS_USER_LEVEL"], CONF_DOCUMENT_ROOT);
$page->showPage((PID > 0 ? PID : menuID("SITE_APP")), SID);

?>
