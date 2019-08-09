<?php
$pattern = "/^(.*?)[\\\\\/]?objects[\\\\\/]?editor[\\\\\/]?.*?$/";
preg_match($pattern, dirname(__FILE__), $match);
$tmp_path = @$match[1].DIRECTORY_SEPARATOR."objects".DIRECTORY_SEPARATOR."assets/content/images";
$path = str_replace("\\", "/", $tmp_path);

$bReturnAbsolute=false;

$sBaseVirtual0="objects/assets/content/images";  //Assuming that the path is http://yourserver/Editor/assets/ ("Relative to Root" Path is required)
$sBase0 = $path;
$sName0="Resimler";

$sBaseVirtual1="";
$sBase1="";
$sName1="";

$sBaseVirtual2="";
$sBase2="";
$sName2="";

$sBaseVirtual3="";
$sBase3="";
$sName3="";
?>