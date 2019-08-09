<?php
defined("PASS") or die("Dosya yok!");

//Türler
$types = array(
 "system"=>label("SYSTEM GROUPS"),
 "user"=>label("USER DEFINED GROUPS")
);

//Yetki Seviyeleri
$levels = array(
 1=>label("USER"),
 label("EDITOR"),
 label("ADMINISTRATOR") 
);

//Durumlar
$statuss = array(
 label("CLOSE"),
 label("OPEN")
);

//Sıralama
$user_orders = array(
 "id"=>"ID",
 "name"=>label("USER NAME"),
 "fullname"=>label("USER FULLNAME"),
 "email"=>label("USER EMAIL"),
 "level"=>label("USER LEVEL"),
 "status"=>label("OBJECT STATUS") 
);

$group_orders = array(
 "id"=>"ID",
 "name"=>label("GROUP NAME"),
 "fullname"=>label("GROUP FULLNAME"),
 "status"=>label("OBJECT STATUS") 
);

//Güvenlik Sorusu
$questions = array(
 1 => label("Tuttuğum takım?")
);
?>