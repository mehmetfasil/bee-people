<?php
defined("PASS") or die("Dosya yok!");

//Türler
$types = array(
 "site"=>label("SITE"),
 "admin"=>label("ADMIN")
);

//Yetki Seviyeleri
$levels = array(
 label("NO LEVEL"),
 label("USER"),
 label("EDITOR"),
 label("ADMINISTRATOR") 
);

//Durumlar
$statuss = array(
 label("CLOSE"),
 label("OPEN")
);

//Sıralama
$orders = array(
 "id"=>"ID",
 "type"=>label("MENU TYPE"),
 "caption"=>label("MENU CAPTION"),
 "parent"=>label("MENU PARENT"),
 "level"=>label("MENU LEVEL"),
 "status"=>label("MENU STATUS") 
);
?>