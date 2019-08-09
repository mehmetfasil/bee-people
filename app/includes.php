<?php
/**
 * @copyright Masters 2019
 * @author Masters
 * @abstract İçerilecek Dosyalar
 * @version 1.0
 */
// hata ayıklama
error_reporting(E_ALL);

defined("PASS") or die("Dosya yok!");

# Ayar Dosyası
require_once(CONF_DOCUMENT_ROOT."system".DS."config.php");

# Bağlantı Dosyası
require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.mysql.connection.php");

# MySQL Sorgulama
require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.mysql.query.php");

# Oturum Oluşturma Dosyası
require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.session.php");

# Sayfa Oluşturma Dosyası
require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.page.php");

# Genel Fonksiyonlar
require_once(CONF_DOCUMENT_ROOT."system".DS."functions".DS."common.php");

# Form Oluşturma ve Değer Alma
if (isset($_GET[ADMIN_EXTENSION])) {
	require_once(CONF_DOCUMENT_ROOT.DS.ADMIN_FOLDER.DS."system".DS."functions".DS."formelements.php");
}else{
	require_once(CONF_DOCUMENT_ROOT."system".DS."functions".DS."formelements.php");
}

//Geçerli Dil
$languages = getLanguages();
define("LANG", isset($languages[getvalue("lang")]) ? getvalue("lang") : DEFAULT_LANGUAGE);
$_SESSION["lang"] = LANG;

//Dil Özellikleri
setLanguageProperties();

//Bağlantı
$connection = new myConnect(MYHOST, MYUSER, MYPASS, MYDB);

if ($conn = $connection->connect()){
	define("CONN", $conn);
	// karakter seti
	new query("SET NAMES 'utf8' COLLATE 'utf8_turkish_ci'"); 
}else{
	page::showErrorPage(label($connection->showError()), label($connection->showError()));
	exit(0);
}

# Banlanan ip var ise sisteme girişi engelliyoruz
require_once(CONF_DOCUMENT_ROOT.DS."system".DS."functions".DS."controltools.php");
// ip yi cek ediyoruz
checkIpStatus($_SERVER['REMOTE_ADDR']);

# Default değerler
require_once(CONF_DOCUMENT_ROOT."system".DS."definitions.php");

//Oturum
$session = new session(config("CONF_SESSION_TIMELIMIT",60));

if (!$session->start()) {
	page::showErrorPage(label($session->showError()), label($session->showError()));
	exit(0);
}

//Kuki
$session->cookie();

//Çıkış
if(!empty($_SESSION["SYS_USER_ID"]) and isset($_GET["logout"])){
	if (isset($_COOKIE["WEBIM_KUKI"]["user_name"])) {
		setcookie("WEBIM_KUKI[user_name]", $_COOKIE["WEBIM_KUKI"]["user_name"], time()+60*60*24*30);
		setcookie("WEBIM_KUKI[user_pass]", "", time()-3600);
	}else{
		setcookie("WEBIM_KUKI[user_name]", "", time()-3600);
		setcookie("WEBIM_KUKI[user_pass]", "", time()-3600);
	}

	//Çıkış
	if ($session->logout()){
	 header("Location: ".CONF_MAIN_PAGE.(isset($_GET[ADMIN_EXTENSION]) ? "?".ADMIN_EXTENSION : (PID!=0 ? "?pid=".PID : "")));
	}else{
 	page::showErrorPage(label("LOGOUT ERROR"), label("LOGOUT ERROR"));
	}
	exit(0);
}

//Yönetici Paneli
if (isset($_GET[ADMIN_EXTENSION])) {
	//Yönetim Paneli
	include CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS."index.php";
	exit(0);
}

//Sayfa kapalı ise kapatalım
if (($_SESSION["SYS_USER_LEVEL"] < 3) and (config("CONF_SYSTEM_STATUS","online") == "offline") and (PID != menuID("SITE_LOGIN"))) {
	page::showErrorPage(label("UNDER MAINTENANCE"), config("CONF_OFFLINE_MESSAGE",label("UNDER MAINTENANCE")));
	exit(0);
}
?>