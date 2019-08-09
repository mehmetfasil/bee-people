<?php
defined("PASS") or die("Dosya yok!");

//Yol
$raw_path = "objects/assets/products/".getvalue("path")."/";
$path = CONF_DOCUMENT_ROOT.$raw_path;

if (!file_exists($path)) {
	@mkdir($path, 0777);
}

$extension_list = array("png");
$extensions = "in";
$overwrite = 1;

//Upload Dosyası
include_once(CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS."modules".DS."sys_common".DS."upload.php");

//Resmi küçültelim
$min_width = 400;
$min_height = 300;
$filename = $path.$file_name;

$img_size = getimagesize($filename);
$x_size = $img_size[0];
$y_size = $img_size[1];

if ($x_size > $min_width) {
	$w = $min_width;
	$h = number_format(($y_size / $x_size) * $min_width, 0, ',', '');
	//$h = $min_height;
	
	$im = imagecreatefrompng($filename);
	$dest = imagecreatetruecolor($w, $h);
	imageantialias($dest, true);
	imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $x_size, $y_size);
	imagepng($dest, $filename);
	imagedestroy($im);
}

exit(0);
?>