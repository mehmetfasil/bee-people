<?php
defined("PASS") or die("Dosya yok!");

//Yol
$path = "objects/assets/bayilogo/";
$save_path = CONF_DOCUMENT_ROOT.$path;

if (!file_exists($save_path)) {
	@mkdir($save_path, 0777);
}

$POST_MAX_SIZE = ini_get("post_max_size");
$unit = strtoupper(substr($POST_MAX_SIZE, -1));
$multiplier = ($unit == "M" ? 1048576 : ($unit == "K" ? 1024 : ($unit == "G" ? 1073741824 : 1)));

if (isset($_SERVER["CONTENT_LENGTH"]) and ((int)$_SERVER["CONTENT_LENGTH"] > $multiplier*(int)$POST_MAX_SIZE && $POST_MAX_SIZE)) {
 echo label("POST EXCEEDED MAXIMUM ALLOWED SIZE");
 exit(0);
}

// Settings
$upload_name = "Filedata";
$max_file_size_in_bytes = 2147483647;				//2GB
$extension_whitelist = array("png");	//İzin verilen değişken
$valid_chars_regex = ".A-Z0-9_ !@#$%^&()+={}\[\]\',~`-";

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

//Boyut kontrolü
$file_size = @filesize($_FILES[$upload_name]["tmp_name"]);
if (!$file_size || $file_size > $max_file_size_in_bytes) {
 echo label("FILE EXCEEDS THE MAXIMUM ALLOWED SIZE");
 exit(0);
}

if ($file_size <= 0) {
 echo label("FILE SIZE OUTSIDE ALLOWED LOWER BOUND");
 exit(0);
}

//İsim kontrolü
$file_name = preg_replace('/[^'.$valid_chars_regex.']|\.+$/i', "", basename($_FILES[$upload_name]['name']));
if (strlen($file_name) == 0 || strlen($file_name) > $MAX_FILENAME_LENGTH) {
 echo label("INVALID FILE NAME");
 exit(0);
}

//Varsa üzerine yaz
if (file_exists($save_path.$file_name)) {
 @unlink($save_path.$file_name);
}

//Dosya uzantısı
$path_info = pathinfo($_FILES[$upload_name]['name']);
$file_extension = $path_info["extension"];
$is_valid_extension = false;
foreach ($extension_whitelist as $extension) {
 if (strcasecmp($file_extension, $extension) == 0) {
  $is_valid_extension = true;
  break;
 }
}

if (!$is_valid_extension) {
 echo label("INVALID FILE EXTENSION");
 exit(0);
}

if (!@move_uploaded_file($_FILES[$upload_name]["tmp_name"], $save_path.$file_name)) {
 echo label("FILE COULD NOT BE SAVED");
 exit(0);
}

$_SESSION["values"]["picture"] = $path.$file_name;
echo "OK:".label("SUCCESSFULLY UPLOADED");
exit(0);
?>