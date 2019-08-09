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
?>