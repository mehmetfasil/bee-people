<?php
defined("PASS") or die("Dosya yok!");

require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.captcha.php");

$captcha = new captcha(6);

$_SESSION["CAPTCHA"][ID] = $captcha->getCaptchaString();
?>