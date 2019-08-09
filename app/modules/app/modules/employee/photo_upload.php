<?
defined("PASS") or die("Dosya Yok!");
if(isset($_SESSION['SYS_USER_ID']) && $_SESSION['SYS_USER_ID']>0){
    
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
if(!isAuthorized("",getvalue("ef"),"")){
echo "Yetkiniz Yoktur";
return;
}

 if(isset($_FILES['emp_photo'])){
      $errors= array();
      $file_name = $_FILES['emp_photo']['name'];
      $file_name_u = getvalue("ef");
      $file_size =$_FILES['emp_photo']['size'];
      $file_tmp =$_FILES['emp_photo']['tmp_name'];
      $file_type=$_FILES['emp_photo']['type'];
      $file_ext = substr( strrchr($_FILES['emp_photo']['name'], '.'), 1);
      
      $expensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$expensions)=== false){
         $errors[]="JPEG veya PNG uzantılı bir dosya yüklemeniz gerekmektedir.";
      }
      
      if($file_size > 2097152){
         $errors[]='Fotoğraf boyutu 2 MBdan fazla olamaz.';
      }
      
      if(empty($errors)==true){
        //first check if firms path is created
        $path = 'e2box/ROOT/FIRMS/'.$_SESSION['SYS_USER_FIRM_ID'].'/EMP/'.getvalue("ef").'/';
        //exit($path);
        if(!file_exists($path)){
            
            mkdir($path,0777,true);
        }
        
        //write db
        $mysqli = new mysqli("mysql.ekare.online", "ekare", "CErx23E2", "ekare"); 
        
        $update = "UPDATE ".$table_app_employee_files." SET is_active=0 WHERE app_file_id='1' and emp_id='".getvalue("ef")."' ";
        mysqli_query($mysqli,$update);
        
        $query = "INSERT INTO ".$table_app_employee_files." (`id`,`emp_id`,`filename`,`file_ext`,`app_file_id`,`path`,`upload_date`,`uploaded_by`,`delete_date`,`deleted_by` ,`notes` ,`is_active` ) VALUES(".
        "null,'".getvalue("ef")."','".$file_name."','".$file_ext."','1','".$path.$file_name_u.'.'.$file_ext."',NOW(),'".$_SESSION['SYS_USER_ID']."',null,null,null,1 ".
        ")";
         mysqli_query($mysqli,$query);
       
        
         if(move_uploaded_file($file_tmp,$path.$file_name_u.'.'.$file_ext)){
            echo "Yükleme Başarılı";
            echo "<script type='text/javascript'>top.upLOAD();</script>";
         }else{
            echo "Yükleme Başarısız";
         }
         
         
      }else{
         print_r($errors);
      }
   }
 
}else{
    echo "Dosya Yok!";
}
?>
