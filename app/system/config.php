<?php
/**
 * @copyright Masters 2019
 * @abstract Ayarlar
 * @version 1.0
 */

defined("PASS") or die("Dosya yok!");

//Dil
setlocale(LC_TIME, "tr_TR");
//date_default_timezone_set("Europe/Istanbul"); //uncomment if server has timezone issue.
define("DEFAULT_LANGUAGE", "tr");

//Yönetici Paneli
define("ADMIN_EXTENSION", "admin");
define("ADMIN_FOLDER", "admin");

//MySQL tanýmlamalarý
//change here to connect db.
/*lokal
define("MYHOST", "localhost");
define("MYUSER", "sis_user");
define("MYPASS", "CErx23E2,");
define("MYDB", "ekare_5");
define("MYPORT", "3306");*/

/* yayýn*/
define("MYHOST", "mysql.ekare.online");
define("MYUSER", "ekare");
define("MYPASS", "CErx23E2");
define("MYDB", "ekare");
define("MYPORT", "3306");

?>