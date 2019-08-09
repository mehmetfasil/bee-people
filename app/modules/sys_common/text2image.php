<?php
defined("PASS") or die("Dosya yok!");

require_once(CONF_DOCUMENT_ROOT."system".DS."classes".DS."class.text2image.php");

$text2image = new text2image(250, 50, array(255,255,255), array(0,0,0));
$text2image->ShowTextAsPng(12, 20, 30, getvalue("text","text değişkeni ile yazı ekleyin"));
?>