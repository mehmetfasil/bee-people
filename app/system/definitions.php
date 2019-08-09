<?php
defined("PASS") or die("Dosya yok!");

//Sistem tanımlamaları
define("PID", getvalue("pid",0));
define("SID", getvalue("sid","index"));
define("PAGE", getvalue("page","list"));
define("ACT", getvalue("act"));
define("STEP", getvalue("step"));
define("OPER", getvalue("oper"));
define("RESULTAS", getvalue("as",array("xml","text")));
define("PROCESS", getvalue("process"));
define("ID", getvalue("id",0));
define("X", getvalue("x",0));
define("Y", (getvalue("y",20) > 100 ? 100 : getvalue("y",20)));
define("ORDERBY", getvalue("orderby"));
define("ORDER", getvalue("order",array("DESC","ASC")));

//Konfigürasyonları set edelim
setConfiguration();

//Menüler
setMenuIDs();

if (!defined("CONF_MAIN_PAGE")) {
	define("CONF_MAIN_PAGE", "index.php");
}

if (!defined("CONF_ADMIN_TEMPLATE")) {
	define("CONF_ADMIN_TEMPLATE", "_default");
}
?>