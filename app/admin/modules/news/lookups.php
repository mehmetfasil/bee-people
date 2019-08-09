<?php
defined("PASS") or die("Dosya yok!");

//Yetki Seviyeleri
$levels = array(
 label("NO LEVEL"),
 label("USER"),
 label("EDITOR"),
 label("ADMINISTRATOR") 
);

//Sıralama
$orders = array(
 "id"=>"ID",
 "publish_date"=>label("PUBLISH DATE"),
 "title"=>label("NEWS TITLE"),
 "category"=>label("NEWS CATEGORY"),
 "level"=>label("NEWS LEVEL"),
 "status"=>label("NEWS STATUS") 
);
?>