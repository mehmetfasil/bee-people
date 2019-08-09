<?php
defined("PASS") or die("Dosya yok!");

//Yol
$image_path = "objects/assets/gallery/";
$path = CONF_DOCUMENT_ROOT.$image_path;

if (!file_exists($path)) {
 @mkdir($path, 0777);
}

if ((getvalue("id",0) > 0) && (strlen(getvalue("path")) > 0)) {
 //Yol
 $image_path = "objects/assets/gallery/".getvalue("path")."/";
 $path = CONF_DOCUMENT_ROOT.$image_path;

 if (!file_exists($path)) {
  @mkdir($path, 0777);
 }

 //Resim Yolu
 $thumb_path = CONF_DOCUMENT_ROOT.$image_path."thumbs/";

 if (!file_exists($thumb_path)) {
  @mkdir($thumb_path, 0777);
 }

 $ext = getvalue("extension",array("png","jpg","gif"));
 $extension_list = array($ext);
 $extensions = "in";
 $overwrite = 1;

 //Upload Dosyası
 include_once(CONF_DOCUMENT_ROOT.ADMIN_FOLDER.DS."modules".DS."sys_common".DS."upload.php");

 $raw = explode("|", getvalue("sizes"));

 if (count($raw) == 6){
  $original_size = array($raw[0], $raw[1]);
  $thumb_size = array($raw[3], $raw[4]);
  $aspect_ratio = array($raw[2], $raw[5]);

  //Resimler
  $filename = $path.$file_name;
  $thumb_filename = $thumb_path.$file_name;

  //Küçük resmi de kopyalayalım
  if (@copy($filename, $thumb_filename)) {
   //Resmi küçültelim
   $min_width = $original_size[0];
   $min_height = $original_size[1];
   $thumb_min_width = $thumb_size[0];
   $thumb_min_height = $thumb_size[1];

   $img_size = getimagesize($filename);
   $x_size = $img_size[0];
   $y_size = $img_size[1];

   if ($x_size > $min_width) {
    $w = $min_width;
    if ($aspect_ratio[0] == 1) {
     $h = number_format(($y_size / $x_size) * $min_width, 0, ',', '');
    }else{
     $h = $min_height;
    }

    switch ($ext){
     default:
     case "png":
      $im = imagecreatefrompng($filename);
      break;
     case "jpg":
      $im = imagecreatefromjpeg($filename);
      break;
     case "gif":
      $im = imagecreatefromgif($filename);
      break;
    }
    $dest = imagecreatetruecolor($w, $h);
    imageantialias($dest, true);
    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $x_size, $y_size);
    switch ($ext){
     default:
     case "png":
      imagepng($dest, $filename);
      break;
     case "jpg":
      imagejpeg($dest, $filename, 100);
      break;
     case "gif":
      imagegif($dest, $filename);
      break;
    }
    imagedestroy($im);
   }

   //Küçük Resim
   if ($x_size > $thumb_min_width) {
    $w = $thumb_min_width;
    if ($aspect_ratio[1] == 1) {
     $h = number_format(($y_size / $x_size) * $thumb_min_width, 0, ',', '');
    }else{
     $h = $thumb_min_height;
    }

    switch ($ext){
     default:
     case "png":
      $im = imagecreatefrompng($thumb_filename);
      break;
     case "jpg":
      $im = imagecreatefromjpeg($thumb_filename);
      break;
     case "gif":
      $im = imagecreatefromgif($thumb_filename);
      break;
    }
    $dest = imagecreatetruecolor($w, $h);
    imageantialias($dest, true);
    imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $h, $x_size, $y_size);
    switch ($ext){
     default:
     case "png":
      imagepng($dest, $thumb_filename);
      break;
     case "jpg":
      imagejpeg($dest, $thumb_filename, 100);
      break;
     case "gif":
      imagegif($dest, $thumb_filename);
      break;
    }
    imagedestroy($im);
   }
   
   //Veritabanına ekleyelim
   $query = "INSERT INTO `gallery_pictures` (`date`, `gallery`, `path`, `active`) VALUES ("
   . "NOW(), "
   . getvalue("id",0).", "
   . "'".$image_path.$file_name."', "
   . "'1'"
   . ")";
   new query($query, CONN);
  }else{
   echo label("AN ERROR OCCURED");
  }
 }else{
  echo label("GALLERY INFORMATION IS NULL");
 }
}else{
 echo label("GALLERY INFORMATION IS NULL");
}

exit(0);
?>