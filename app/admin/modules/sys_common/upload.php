<?php
defined("PASS") or die("Dosya yok!");

if (ISUPLOADABLE) {
 if (isset($path)) {
  //Klasör
 	$path = (strpos($path, CONF_DOCUMENT_ROOT) === false) ? CONF_DOCUMENT_ROOT.$path : $path;
  $save_path = ($path{strlen($path)-1} != "/") ? $path."/" : $path;
  
  if (file_exists($save_path)) {
   $POST_MAX_SIZE = ini_get("post_max_size");
   $unit = strtoupper(substr($POST_MAX_SIZE, -1));
   $multiplier = ($unit == "M" ? 1048576 : ($unit == "K" ? 1024 : ($unit == "G" ? 1073741824 : 1)));
 
   if (isset($_SERVER["CONTENT_LENGTH"]) and ((int)$_SERVER["CONTENT_LENGTH"] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE)) {
    echo label("POST EXCEEDED MAXIMUM ALLOWED SIZE");
    exit(0);
   }
 
   //Ayarlar
   $upload_name = "Filedata";
   $max_file_size_in_bytes = 2147483647;	// 2GB
   $valid_chars_regex = ".A-Z0-9_!@#$%^&()+={}\[\]\',~`-";	//Geçerli karakterler
 
   // Other variables
   $MAX_FILENAME_LENGTH = 260;
   $file_name = "";
   $file_extension = "";
   $uploadErrors = array(
    0 => "THERE IS NO ERROR, THE FILE UPLOADED WITH SUCCESS",
    1 => "THE UPLOADED FILE EXCEEDS THE UPLOAD_MAX_FILESIZE DIRECTIVE IN PHP.INI",
    2 => "THE UPLOADED FILE EXCEEDS THE MAX_FILE_SIZE DIRECTIVE THAT WAS SPECIFIED IN THE HTML FORM",
    3 => "THE UPLOADED FILE WAS ONLY PARTIALLY UPLOADED",
    4 => "NO FILE WAS UPLOADED",
    6 => "MISSING A TEMPORARY FOLDER"
   );
 
   //Yükleme işlemi
   if (!isset($_FILES[$upload_name])) {
    echo label("NO UPLOAD FOUND IN \$_FILES FOR {".$upload_name."}");
    exit(0);
   }elseif (isset($_FILES[$upload_name]["error"]) && $_FILES[$upload_name]["error"] != 0) {
    echo label($uploadErrors[$_FILES[$upload_name]["error"]]);
    exit(0);
   }elseif (!isset($_FILES[$upload_name]["tmp_name"]) || !@is_uploaded_file($_FILES[$upload_name]["tmp_name"])) {
    echo label("UPLOAD FAILED IS_UPLOADED_FILE TEST");
    exit(0);
   }elseif (!isset($_FILES[$upload_name]['name'])) {
    echo label("FILE HAS NO NAME");
    exit(0);
   }
 
   //Dosya boyutu kontrolü (Ayarı 2GB yaptık)
   $file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
   if (!$file_size || $file_size > $max_file_size_in_bytes) {
    echo label("FILE EXCEEDS THE MAXIMUM ALLOWED SIZE");
    exit(0);
   }
 
   if ($file_size <= 0) {
    echo label("FILE SIZE OUTSIDE ALLOWED LOWER BOUND");
    exit(0);
   }
 
   //Dosya adı kontrolü
   $file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "_", basename($_FILES[$upload_name]['name']));
   if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
    echo label("INVALID FILE NAME");
    exit(0);
   }
 
   //Dosya varlığı kontrolü
   if (file_exists($save_path.$file_name)) {
    if (@$overwrite == 1){
     @unlink($save_path.$file_name);
    }else{
     echo label("FILE WITH THIS NAME ALREADY EXISTS");
     exit(0);
    }
   }
 
   //Uzantı kontroü
   $path_info = pathinfo($_FILES[$upload_name]['name']);
   $file_extension = $path_info["extension"];
   
   //Uzantı kontrolü
   $extension_stop = false;
   
   foreach (@$extension_list as $extension) {
    if (@$extensions == "in") {
     if (strcasecmp($file_extension, $extension) != 0) {
      $extension_stop = true;
      break;
     }
    }else{
     if (strcasecmp($file_extension, $extension) == 0) {
      $extension_stop = true;
      break;
     }
    }
   }
   
   if ($extension_stop) {
    echo label("INVALID FILE EXTENSION");
    exit(0);
   }
 
   if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
    echo label("FILE COULD NOT BE SAVED");
    exit(0);
   }
 
   echo "OK:".label("SUCCESSFULLY UPLOADED");
  }else{
   echo label("UPLOAD FOLDER NOT FOUND");
   exit(0);
  }
 }else{
  echo label("AN ERROR OCCURED");
  exit(0);
 }
}else{
 echo label("YOU HAVE NO PERMISSION TO UPLOAD");
 exit(0);
}
?>