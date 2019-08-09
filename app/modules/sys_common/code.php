<?php
defined("PASS") or die("Dosya yok!");

require(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.text2image.php");
require(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.password.generator.php");

$textToImage = new text2image(getvalue("width",70), getvalue("height",25), array(255,255,255), array(255,0,0));

//Session a aktardığımızı siliyoruz
unset($_SESSION["CAPTCHA"][ID]);

$password = new passwordGenerator();
$text = $password->generate("integer", getvalue("length",6));

//Session a atıyoruz
$_SESSION["CAPTCHA"][ID] = $text;

$textToImage->ShowTextAsPng(12, getvalue("x",5), getvalue("y",20), $text);
?>