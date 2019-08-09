<?
defined("PASS") or die("Yükleniyor...");
include_once (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
if (isset($_SESSION['SYS_USER_ID']) && $_SESSION['SYS_USER_ID'] > 0) {
    //type => izin belgesi, rapor etc.
    //file => file to upload
    //emp => emp to relate file
    
    if (isset($_FILES['fileUploadFile'])) {
       
        if (getvalue("fileUploadType") != "" || getvalue("fileUploadType") != null) {
            if (getvalue("uploadId") != "" || getvalue("uploadId") != null) {
                //auth OK. file OK. process
                $type = trim(getvalue("fileUploadType"));
                $emp = trim(getvalue("uploadId"));
                $fileUploadName = trim(getvalue("fileUploadName"));
                if (isAuthorized("", $emp, "")) {
                    $errors = array();
                    $file_name = $_FILES['fileUploadFile']['name'];
                    $file_name_u = $emp."-".$type."-".rand(1,999);
                    $file_size = $_FILES['fileUploadFile']['size'];
                    $file_tmp = $_FILES['fileUploadFile']['tmp_name'];
                    $file_type = $_FILES['fileUploadFile']['type'];
                    $file_ext = substr(strrchr($_FILES['fileUploadFile']['name'], '.'), 1);
                    $expensions = array(
                        "jpeg",
                        "jpg",
                        "png",
                        "pdf",
                        "tiff");

                    if (in_array($file_ext, $expensions) === false) {
                        $errors[] = "JPG, PNG, PDF, TIFF uzantılarından birisi olmalıdır. ";
                    }

                    if ($file_size > 2097152) {
                        $errors[] = 'Fotoğraf boyutu 2 MBdan fazla olamaz.';
                    }
                    
                    if (count($errors) <= 0) {
                        //first check if firms path is created
                        $path = 'e2box/ROOT/FIRMS/' . $_SESSION['SYS_USER_FIRM_ID'] . '/EMP/' . $emp.'/';
                        //exit($path);
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $fullPath = $path.$file_name_u.'.'.$file_ext;
                        if(move_uploaded_file($file_tmp,$fullPath)){
                            //delete the old one.
                            $sql_update_emp_files = "UPDATE ".$table_app_employee_files." SET is_active=0 where emp_id='".$emp."' and app_file_id='".$type."' ";
                            new query($sql_update_emp_files);
                            //write to db
                            $sql_insert_emp_files = "INSERT INTO ".$table_app_employee_files. "(id,emp_id,filename,file_ext,app_file_id,path,upload_date,uploaded_by,is_active)".
                            " VALUES(null,'".$emp."','".$fileUploadName."','".$file_ext."','".$type."','".$fullPath."',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
                            
                            $insert_emp_files = new query($sql_insert_emp_files);
                            
                            echo "Yükleme Başarılı";
                            echo "<script>top.fileUploaded()</script>";
                            
                         }else{
                            echo "Yükleme Başarısız";
                         }
                    }else{
                        echo $errors[0];
                    }

                } else {
                    die("Yetki Yok");
                }
            } else {
                die("Personel Seçilmedi");
            }
        } else {
            die("Tür Seçilmedi");
        }
    } else {
        die("Yekleniyor...");
    }
} else {
    die("Yükleniyor...");
}
?>