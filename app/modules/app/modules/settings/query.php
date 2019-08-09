<?
defined("PASS") or die("Dosya Yok!");
include (CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch (ACT) {
    
    
    case "save_dosyaturu":
        /*
        [dosya_rid] => 
        [dosya_adi] => asdasd
        */
         $dosya_rid=checkInput(getvalue("dosya_rid"));
        $dosya_adi = checkInput(getvalue("dosya_adi"));
        
        if($dosya_adi=="" || $dosya_adi==null){
            echo "<result status='ERROR'>Dosya türü adı girilmelidir.</result>";
            return;
        }
        
        if(is_numeric($dosya_rid) && $dosya_rid>0){
            //update
            //yetkili mi?
                $sql = "UPDATE ".$table_app_file_definition." SET file_name='".$dosya_adi."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$dosya_rid."' ";
                $u = new query($sql);
                if($u->affectedrows()>0){
                    echo "<result status='OK'>İzin türü başarıyla güncellendi.</result>";
                    return;
                }else{
                    echo "<result status='ERROR'>Herhangi bir güncelleme yapılmadı.</result>";
                    return;  
                }
            
        }else{
            //insert
            $sql_insert = "INSERT INTO ".$table_app_file_definition." (id,file_name,is_active,firm_id,is_general) ".
            " VALUES(null,'".$dosya_adi."',1,'".$_SESSION['SYS_USER_FIRM_ID']."',0)";
            $q = new query($sql_insert);
            if($q->affectedrows()>0){
                echo "<result status='OK'>Dosya türü başarıyla eklendi.</result>";
                return;
            }else{
               echo "<result status='ERROR'>Dosya türü eklenirken hata oluştu.</result>";
                return; 
            }
            
        }
        
    break;
    
    case "g_filetypes":
        $sql = "SELECT id,`file_name`,`is_general` FROM ".$table_app_file_definition." WHERE ((is_general=1) or (firm_id='".$_SESSION['SYS_USER_FIRM_ID']."')) and is_active='1' ";
        $q = new query($sql);
        if($q->numrows()>0){
            echo "<result status='OK'></result>"; 
            while($row=$q->fetchobject()){
                echo "<f i='".$row->id."' n='".$row->file_name."' p='".($row->is_general==1 ? "0" : "1" )."'></f>";
            }
        }
    break;
    
    case "d_d":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $i = checkInput(getvalue("i"));
            if($i>0 && is_numeric($i)){
                    $d = "DELETE FROM ".$table_app_file_definition." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."' and is_general=0 ";
                    $q = new query($d);
                    if($q->affectedrows()>0){
                        echo "<result status='OK'>Kayıt Silindi</result>";
                        return;
                    }   else{
                        echo "<result status='EROR'>Hata Oluştu!</result>";
                        return;
                    } 
                
            }else{
                echo "<result status='EROR'>Dosya Türü Bilgisi Alınamadı</result>";
                return;
            }
        }
    break;
    
    
    
    
    
    
    
    
    
    
    case "g_izintypes":
        $sql = "SELECT id,`name`,`desc` FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and type='4' ";
        $q = new query($sql);
        if($q->numrows()>0){
            echo "<result status='OK'></result>"; 
            while($row=$q->fetchobject()){
                echo "<it i='".$row->id."' n='".$row->name."' d='".$row->desc."'></it>";
            }
        }
    break;
    
    case "save_izinturu":
        $izin_rid=checkInput(getvalue("izin_rid"));
        $zimmet_adi = checkInput(getvalue("izin_adi"));
        $izin_aciklamasi = checkInput(getvalue("izin_aciklamasi"));
        
        if($zimmet_adi=="" || $zimmet_adi==null){
            echo "<result status='ERROR'>İzin türü adı girilmelidir.</result>";
            return;
        }
        
        if($izin_aciklamasi=="" || $izin_aciklamasi==null){
            echo "<result status='ERROR'>İzin türü açıklaması girilmelidir.</result>";
            return;
        }
        
        if(is_numeric($izin_rid) && $izin_rid>0){
            //update
            //yetkili mi?
            if(isAuthorizedForLookups($izin_rid)){
                $sql = "UPDATE ".$table_app_f_lookups." SET name='".$zimmet_adi."',desc='".$izin_aciklamasi."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$izin_rid."' ";
                $u = new query($sql);
                if($u->affectedrows()>0){
                    echo "<result status='OK'>İzin türü başarıyla güncellendi.</result>";
                    return;
                }else{
                    echo "<result status='ERROR'>İzin türü güncellenirken hata oluştu.</result>";
                    return;  
                }
            }else{
              echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
              return;  
            }
        }else{
            //insert
            $sql_insert = "INSERT INTO ".$table_app_f_lookups." (id,firm_id,is_general,name,type,added_date,added_by,is_active) ".
            " VALUES(null,'".$_SESSION['SYS_USER_FIRM_ID']."',0,'".$zimmet_adi."','4',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
            $q = new query($sql_insert);
            if($q->affectedrows()>0){
                echo "<result status='OK'>İzin türü başarıyla eklendi.</result>";
                return;
            }else{
               echo "<result status='ERROR'>İzin türü eklenirken hata oluştu.</result>";
                return; 
            }
            
        }
    break;
    
    
    
    
    
    
    
    
    
    
    case "d_clndr":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $i = checkInput(getvalue("i"));
            if($i>0 && is_numeric($i)){
                if(isAuthorizedForItemOnTable($i,"app_firm_workcal")){
                    $d = "DELETE FROM ".$table_app_firm_wc." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."'";
                    $q = new query($d);
                    if($q->affectedrows()>0){
                        echo "<result status='OK'>Kayıt Silindi</result>";
                        return;
                    }   else{
                        echo "<result status='EROR'>Hata Oluştu!</result>";
                        return;
                    } 
                }else{
                    echo "<result status='EROR'>Yetkisiz Erişim</result>";
                    return;
                }
            }else{
                echo "<result status='EROR'>Çalışma Takvimi Bilgisi Alınamadı</result>";
                return;
            }
        }
    break;
    
    case "g_cl_detail":
    $i = checkInput(getvalue("i"));
    if(is_numeric($i) && $i>0){
        //isAuth
        if(isAuthorizedForItemOnTable($i,"app_firm_workcal")){
            $q = "select calendar_name,working_days,work_hour_start,work_hour_end,meal_hour_start,meal_hour_end from ".$table_app_firm_wc.
            " WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."' ";
            $s = new query($q);
            if($s->numrows()>0){
                echo "<result status='OK'></result>\n";
                $row=$s->fetchobject();
                echo "<ce n='".$row->calendar_name."' wd='".$row->working_days."' whs='".$row->work_hour_start."' ".
                " whe='".$row->work_hour_end."' mhs='".$row->meal_hour_start."' mhe='".$row->meal_hour_end."'></ce>";
            }else{
                echo "<result status='ERROR'>Kayıt Bulunamadı.</result>";
                return; 
            }
        }else{
            echo "<result status='ERROR'>Yetkisiz İşlem.</result>";
            return; 
        }
    }else{
        echo "<result status='ERROR'>Bilgiler Alınamadı.</result>";
        return;  
    }
    break;
    
    case "g_ct":
        $sql = "SELECT id,calendar_name FROM ".$table_app_firm_wc." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' ";
        $q = new query($sql);
        if($q->numrows()>0){
            echo "<result status='OK'></result>"; 
            while($row=$q->fetchobject()){
                echo "<wc i='".$row->id."' n='".$row->calendar_name."'></wc>";
            }
        }
    break;
    
    case "save_ct":
    /*
    [takvim_rid] => 
    [ct_adi] => 123123
    [days] => Array
        (
            [0] => 1
            [1] => 2
            [2] => 3
            [3] => 4
            [4] => 5
            [5] => 6
        )

    [ct_baslangic_saati] => 12:31
    [ct_bitis_saati] => 12:12
    [ct_yemek_baslangic_saati] => 12:12
    [ct_yemek_bitis_saati] => 12:12
    */
    $days = (isset($_POST['days'])  ? $_POST['days'] : array());
    $takvim_rid = checkInput(getvalue("takvim_rid"));
    $ct_adi = checkInput(getvalue("ct_adi"));
    $ct_baslangic_saati = checkInput(getvalue("ct_baslangic_saati"));
    $ct_bitis_saati = checkInput(getvalue("ct_bitis_saati"));
    $ct_yemek_baslangic_saati = checkInput(getvalue("ct_yemek_baslangic_saati"));
    $ct_yemek_bitis_saati = checkInput(getvalue("ct_yemek_bitis_saati"));
    
    if(count($days)<=0){
       echo "<result status='ERROR'>En az 1 Çalışma günü seçilmelidir.</result>";
        return;  
    }
    $dayStr = "";
    foreach($days as $k=>$v){
        $days[$k] = checkInput($days[$k]);
        if($days[$k]==""){
        exit("<result status='ERROR'>Lütfen çalışma günlerini doğru olarak seçin.</result>");   
        }
        $dayStr .=$days[$k]."-";
    }
    $dayStr = substr($dayStr,0,-1);
    
    if($ct_adi=="" || $ct_adi==null){
       echo "<result status='ERROR'>Takvim Adı girilmelidir.</result>";
        return; 
    }
    if(!isHourValid($ct_baslangic_saati)){
        echo "<result status='ERROR'>Takvim Başlangıç Saati formatınız yanlış</result>";
        return;
    }
    if(!isHourValid($ct_bitis_saati)){
        echo "<result status='ERROR'>Takvim Bitiş Saati formatınız yanlış</result>";
        return;
    }
    if(!isHourValid($ct_yemek_baslangic_saati)){
        echo "<result status='ERROR'>Yemek Başlangıç Saati formatınız yanlış</result>";
        return;
    }
    if(!isHourValid($ct_yemek_bitis_saati)){
        echo "<result status='ERROR'>Yemek Bitiş Saati formatınız yanlış</result>";
        return;
    }
    
    //regex
    if(is_numeric($takvim_rid) && $takvim_rid>0){
        //update
        if(isAuthorizedForItemOnTable($takvim_rid,"app_firm_workcal")){
            $sql_u = "UPDATE ".$table_app_firm_wc." SET calendar_name='".$ct_adi."', working_days='".$dayStr."',".
            " work_hour_start='".$ct_baslangic_saati."', work_hour_end='".$ct_bitis_saati."',meal_hour_start='".$ct_yemek_baslangic_saati."',".
            " meal_hour_end='".$ct_yemek_bitis_saati."' WHERE id='".$takvim_rid."' and firm_id='".$_SESSION['SYS_USER_FIRM_ID']."'";
            $u = new query($sql_u);
            if($u->affectedrows()>=0){
                echo "<result status='OK'>Güncelleme işlemi başarılı!</result>";
               return;
            }else{
               echo "<result status='ERROR'>Güncelleme sırasında hata oluştu!</result>";
               return; 
            }
        }else{
            echo "<result status='ERROR'>Yetkisiz işlem</result>";
            return; 
        }
    }else{
        //insert
        
        $sql_insert = "INSERT INTO ".$table_app_firm_wc. " (id,firm_id,calendar_name,working_days,work_hour_start,work_hour_end,meal_hour_start,meal_hour_end,is_active) VALUES(".
        " null,'".$_SESSION['SYS_USER_FIRM_ID']."', '".$ct_adi."','".$dayStr."','".$ct_baslangic_saati."','".$ct_bitis_saati."','".$ct_yemek_baslangic_saati."','".$ct_yemek_bitis_saati."',1)";
        $q = new query($sql_insert);
        if($q->affectedrows()>0){
            //success
           echo "<result status='OK'>Çalışma Takvimi Kaydı Başarıyla Yapıldı.</result>";
           return; 
        }else{
            echo "<result status='ERROR'>Kayıt sırasında hata oluştu!</result>";
            return;
        }
    }
    
    break;
    
    case "s_zimmet":
    $zimmet_rid=checkInput(getvalue("zimmet_rid"));
    $zimmet_adi = checkInput(getvalue("zimmet_adi"));
    
    if($zimmet_adi=="" || $zimmet_adi==null){
        echo "<result status='ERROR'>Zimmet Kategorisi girilmelidir.</result>";
        return;
    }
    
    if(is_numeric($zimmet_rid) && $zimmet_rid>0){
        //update
        //yetkili mi?
        if(isAuthorizedForLookups($zimmet_rid)){
            $sql = "UPDATE ".$table_app_f_lookups." SET name='".$zimmet_adi."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$zimmet_rid."' ";
            $u = new query($sql);
            if($u->affectedrows()>0){
                echo "<result status='OK'>Zimmet Kategorisi başarıyla güncellendi.</result>";
                return;
            }else{
                echo "<result status='ERROR'>Zimmet Kategorisi güncellenirken hata oluştu.</result>";
                return;  
            }
        }else{
          echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
          return;  
        }
    }else{
        //insert
        $sql_insert = "INSERT INTO ".$table_app_f_lookups." (id,firm_id,is_general,name,type,added_date,added_by,is_active) ".
        " VALUES(null,'".$_SESSION['SYS_USER_FIRM_ID']."',0,'".$zimmet_adi."','3',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
        $q = new query($sql_insert);
        if($q->affectedrows()>0){
            echo "<result status='OK'>Zimmet Kategorisi başarıyla eklendi.</result>";
            return;
        }else{
           echo "<result status='ERROR'>Zimmet Kategorisi eklenirken hata oluştu.</result>";
            return; 
        }
        
    }
    break;
    
    
    case "g_zimmet":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $get = "SELECT id,name FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and type='3' ";
            $q = new query($get);
            if($q->numrows()>0){
                echo "<result status='OK'></result>";
                while($r=$q->fetchobject()){
                    echo "<ic i='".$r->id."' n='".$r->name."'></ic>\n";
                }
            }else{
                echo "<result status='NR'>Kayıt Yok</result>";
                return; 
            }
        }else{
           echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
           return; 
        }
    break;
    
     case "d_zimmet":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $i = checkInput(getvalue("i"));
            if($i>0 && is_numeric($i)){
                if(isAuthorizedForLookups($i)){
                    $d = "DELETE FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."'";
                    $q = new query($d);
                    if($q->affectedrows()>0){
                        echo "<result status='OK'>Kayıt Silindi</result>";
                        return;
                    }   else{
                        echo "<result status='EROR'>Hata Oluştu!</result>";
                        return;
                    } 
                }else{
                    echo "<result status='EROR'>Yetkisiz Erişim</result>";
                    return;
                }
            }else{
                echo "<result status='EROR'>Unvan Bilgisi Alınamadı</result>";
                return;
            }
        }
    break;
    
    
    
    
    
    
    
    
    case "g_ic":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $get = "SELECT id,name FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and type='2' ";
            $q = new query($get);
            if($q->numrows()>0){
                echo "<result status='OK'></result>";
                while($r=$q->fetchobject()){
                    echo "<ic i='".$r->id."' n='".$r->name."'></ic>\n";
                }
            }else{
                echo "<result status='NR'>Kayıt Yok</result>";
                return; 
            }
        }else{
           echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
           return; 
        }
    break;
    
    case "s_ic":
    
    /*
    
    */
    $ic_rid=checkInput(getvalue("ic_rid"));
    $ic_adi = checkInput(getvalue("ic_adi"));
    
    if($ic_adi=="" || $ic_adi==null){
        echo "<result status='ERROR'>İşden çıkarma nedeni girilmelidir.</result>";
        return;
    }
    
    if(is_numeric($ic_rid) && $ic_rid>0){
        //update
        //yetkili mi?
        if(isAuthorizedForLookups($ic_rid)){
            $sql = "UPDATE ".$table_app_f_lookups." SET name='".$ic_adi."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$ic_rid."' ";
            $u = new query($sql);
            if($u->affectedrows()>0){
                echo "<result status='OK'>İşden çıkarma nedeni başarıyla güncellendi.</result>";
                return;
            }else{
                echo "<result status='ERROR'>İşden çıkarma nedeni güncellenirken hata oluştu.</result>";
                return;  
            }
        }else{
          echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
          return;  
        }
    }else{
        //insert
        $sql_insert = "INSERT INTO ".$table_app_f_lookups." (id,firm_id,is_general,name,type,added_date,added_by,is_active) ".
        " VALUES(null,'".$_SESSION['SYS_USER_FIRM_ID']."',0,'".$ic_adi."','2',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
        $q = new query($sql_insert);
        if($q->affectedrows()>0){
            echo "<result status='OK'>İşden çıkarma nedeni başarıyla eklendi.</result>";
            return;
        }else{
           echo "<result status='ERROR'>İşden çıkarma nedeni eklenirken hata oluştu.</result>";
            return; 
        }
        
    }
    break;
    
    case "d_ic":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $i = checkInput(getvalue("i"));
            if($i>0 && is_numeric($i)){
                if(isAuthorizedForLookups($i)){
                    $d = "DELETE FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."'";
                    $q = new query($d);
                    if($q->affectedrows()>0){
                        echo "<result status='OK'>Kayıt Silindi</result>";
                        return;
                    }   else{
                        echo "<result status='EROR'>Hata Oluştu!</result>";
                        return;
                    } 
                }else{
                    echo "<result status='EROR'>Yetkisiz Erişim</result>";
                    return;
                }
            }else{
                echo "<result status='EROR'>Unvan Bilgisi Alınamadı</result>";
                return;
            }
        }
    break;
    
    
    case "d_u":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $i = checkInput(getvalue("i"));
            if($i>0 && is_numeric($i)){
                if(isAuthorizedForLookups($i)){
                    $d = "DELETE FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$i."'";
                    $q = new query($d);
                    if($q->affectedrows()>0){
                        echo "<result status='OK'>Kayıt Silindi</result>";
                        return;
                    }   else{
                        echo "<result status='EROR'>Hata Oluştu!</result>";
                        return;
                    } 
                }else{
                    echo "<result status='EROR'>Yetkisiz Erişim</result>";
                    return;
                }
            }else{
                echo "<result status='EROR'>Unvan Bilgisi Alınamadı</result>";
                return;
            }
        }
    break;
    
    case "g_u":
        if(isset($_SESSION['SYS_USER_FIRM_ID']) && $_SESSION['SYS_USER_FIRM_ID']>0){
            $get = "SELECT id,name FROM ".$table_app_f_lookups." WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and type='1' ";
            $q = new query($get);
            if($q->numrows()>0){
                echo "<result status='OK'></result>";
                while($r=$q->fetchobject()){
                    echo "<u i='".$r->id."' n='".$r->name."'></u>\n";
                }
            }else{
                echo "<result status='NR'>Kayıt Yok</result>";
                return; 
            }
        }else{
           echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
           return; 
        }
    break;
    
    case "s_u":
    
    /*
    [unvan_rid] => 
    [unvan_adi] => asdasd
    */
    $unvan_rid=checkInput(getvalue("unvan_rid"));
    $unvan_adi = checkInput(getvalue("unvan_adi"));
    
    if($unvan_adi=="" || $unvan_adi==null){
        echo "<result status='ERROR'>Unvan adı girilmelidir.</result>";
        return;
    }
    
    if(is_numeric($unvan_rid) && $unvan_rid>0){
        //update
        //yetkili mi?
        if(isAuthorizedForLookups($unvan_rid)){
            $sql = "UPDATE ".$table_app_f_lookups." SET name='".$unvan_adi."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$unvan_rid."' ";
            $u = new query($sql);
            if($u->affectedrows()>0){
                echo "<result status='OK'>Unvan başarıyla güncellendi.</result>";
                return;
            }else{
                echo "<result status='ERROR'>Unvan güncellenirken hata oluştu.</result>";
                return;  
            }
        }else{
          echo "<result status='ERROR'>Yetkisiz Erişim.</result>";
          return;  
        }
    }else{
        //insert
        $sql_insert = "INSERT INTO ".$table_app_f_lookups." (id,firm_id,is_general,name,type,added_date,added_by,is_active) ".
        " VALUES(null,'".$_SESSION['SYS_USER_FIRM_ID']."',0,'".$unvan_adi."','1',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
        $q = new query($sql_insert);
        if($q->affectedrows()>0){
            echo "<result status='OK'>Unvan başarıyla eklendi.</result>";
            return;
        }else{
           echo "<result status='ERROR'>Unvan eklenirken hata oluştu.</result>";
            return; 
        }
        
    }
    break;

    case "s_unit":

        /* SUBE
        [sube_rid] => 
        [sube_adi] => EEMP
        [sube_il] => 6
        [sube_ilce] => 63
        [sube_adresi] => eemp
        [t] => 1
        */
        
        /*DEPARTMAN
        [departman_rid] => 
        [departman_subesi] => 1
        [departman_adi] => asdasd
        [t] => 2
        */
        
        $t = checkInput(getvalue("t"));
        
         if ($t != 1 && $t != 2) {
                echo "<result status='ERROR'>Birim türü doğru seçilmelidir.</result>";
                return;
         }
            
        if ($t == 1) {
            $sube_adi = checkInput(getvalue("sube_adi"));
            $sube_il = checkInput(getvalue("sube_il"));
            $sube_ilce = checkInput(getvalue("sube_ilce"));
            $sube_adresi = checkInput(getvalue("sube_adresi"));
            $sube_rid = checkInput(getvalue("sube_rid"));
        
            //sube
            if ($sube_adi == "" || $sube_adi == null) {
                echo "<result status='ERROR'>Şube Adı Boş Olamaz</result>";
                return;
            }
            if ($sube_il == null || !is_numeric($sube_il)) {
                echo "<result status='ERROR'>Şube İli Seçilmelidir</result>";
                return;
            }
            if ($sube_ilce == null || !is_numeric($sube_ilce)) {
                echo "<result status='ERROR'>Şube İlçesi Seçilmelidir</result>";
                return;
            }
            if ($sube_adresi == "" || $sube_adresi == null) {
                echo "<result status='ERROR'>Şube Adresi Boş Olamaz</result>";
                return;
            }
            
            if($sube_rid!="" && $sube_rid>0){
                //update sube
            }else{
                //sube
                $sql_insert = "INSERT INTO " . $table_app_branch .
                    " (id,firm_id,branch_name,branch_county,branch_town,branch_address,added_date,added_by,is_active) " .
                    " VALUES(null,'" . $_SESSION['SYS_USER_FIRM_ID'] . "','" . $sube_adi . "','" . $sube_il .
                    "','" . $sube_ilce . "','" . $sube_adresi . "',NOW(),'" . $_SESSION['SYS_USER_ID'] .
                    "',1) ";
                $insert = new query($sql_insert);
                if ($insert->affectedrows() > 0) {
                    echo "<result status='OK'>Şube Kaydı Başarıyla Girildi.</result>";
                    return;
                } else {
                    echo "<result status='OK'>Kayıt anında hata oluştu.</result>";
                    return;
                }
            }
        } else {
            //departman
            $departman_rid = checkInput(getvalue("departman_rid"));
            $departman_subesi = checkInput(getvalue("departman_subesi"));
            $departman_adi = checkInput(getvalue("departman_adi"));
            
            if($departman_subesi=="" || !is_numeric($departman_subesi)){
                echo "<result status='ERROR'>Departman Şubesi Seçilmelidir.</result>";
                return;
            }
            if($departman_adi=="" || $departman_adi==null){
               echo "<result status='ERROR'>Departman Adı Girilmelidir.</result>";
               return; 
            }
            if($departman_rid!="" && $departman_rid>0){
                //departman update
            }else{
                //departman insert
                $sql = "INSERT INTO ".$table_app_department." (id,branch_id,department_name,added_date,added_by,is_active)".
                " VALUES(null,'".$departman_subesi."','".$departman_adi."',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
                $insert = new query($sql);
                if($insert->affectedrows()>0){
                    echo "<result status='OK'>Departman Kaydı Yapıldı.</result>";
                    return;
                }   else{
                    echo "<result status='ERROR'>Kayıt Anında Hata Oluştu.</result>";
                    return;
                }             
            }
            
        }

        break;

    case "get_units":
        $user_id = $_SESSION['SYS_USER_ID'];
        $t = checkInput(getvalue("t"));
        if ($t != 1 && $t != 2) {
            echo "<result status='ERROR'>Yetkisiz Erişim</result>";
            return;
        }

        if ($t == 1) {
            //sube
            $sql_get = "select fb.id,fb.branch_name,c.isim from " . $table_app_branch .
                " as fb 
        left join " . $table_l_county .
                " as c on c.id = fb.branch_county where fb.firm_id='" . $_SESSION['SYS_USER_FIRM_ID'] .
                "'";
            $query = new query($sql_get);
            if ($query->numrows() > 0) {
                while ($row = $query->fetchobject()) {
                    echo "<su i='" . $row->id . "' n='" . $row->branch_name . "' il='" . $row->isim .
                        "'></su>";
                }
            }
        }else{
            $sql_get = "select d.id,d.branch_id,d.department_name,b.branch_name from ".$table_app_department." as d
        left join ".$table_app_branch." as b on b.id = d.branch_id where b.firm_id='".$_SESSION['SYS_USER_FIRM_ID']."'";
             $query = new query($sql_get);
            if ($query->numrows() > 0) {
                while ($row = $query->fetchobject()) {
                    echo "<d i='" . $row->id . "' n='" . $row->department_name . "' sube='" . $row->branch_name .
                        "'></d>";
                }
            }
        }
        break;

    case "save_firm_detail":

        //send datas
        /**
         * [firma_ismi] => Şirketim
         * [firma_ili] => -1
         * [firma_ilcesi] => -1
         * [firma_tel] => 
         * [firma_fax] => 
         * [firma_website] => 
         * [mersis_no] => 
         * [sgk_no] => 
         * [firma_unvan] => 
         * [vergi_no] => 
         * [vergi_dairesi] => 
         * [fatura_adresi] => 
         * [fatura_eposta] => 
         */

        $get_firm_name = checkInput(getvalue("firma_ismi"));
        $get_firm_county = checkInput(getvalue("firma_ili"));
        $get_firm_town = checkInput(getvalue("firma_ilcesi"));
        $get_firm_address = checkInput(getvalue("firma_adresi"));
        $get_firm_tel = checkInput(getvalue("firma_tel"));
        $get_firm_fax = checkInput(getvalue("firma_fax"));
        $get_firm_website = checkInput(getvalue("firma_website"));
        $get_mersis_no = checkInput(getvalue("mersis_no"));
        $get_sgk_no = checkInput(getvalue("sgk_no"));
        $get_firma_apellation = checkInput(getvalue("firma_unvan"));
        $get_tax_number = checkInput(getvalue("vergi_no"));
        $get_tax_place = checkInput(getvalue("vergi_dairesi"));
        $get_bill_address = checkInput(getvalue("fatura_adresi"));
        $get_bill_email = checkInput(getvalue("fatura_eposta"));

        $sql_update_app_firms = "UPDATE " . $table_app_firms . " SET firm_name='" . $get_firm_name .
            "',county_id='" . $get_firm_county . "', " . " town_id='" . $get_firm_town .
            "',full_address='" . $get_firm_address . "',phone_number1='" . $get_firm_tel .
            "',fax_number='" . $get_firm_fax . "',website='" . $get_firm_website . "', " .
            " mersis_number='" . $get_mersis_no . "', sgk_number='" . $get_sgk_no .
            "',firm_apellation='" . $get_firma_apellation . "',tax_number='" . $get_tax_number .
            "', " . " tax_place='" . $get_tax_place . "',bill_address='" . $get_bill_address .
            "',bill_email='" . $get_bill_email . "' WHERE id='" . $_SESSION['SYS_USER_FIRM_ID'] .
            "'";
        $update_app_firms = new query($sql_update_app_firms);

        if ($update_app_firms->affectedrows() >= 0) {
            echo "<result status='OK'></result>";
        } else {
            echo "<result status='ERROR'></result>";
        }

        break;

    case "get_firm_details":

        $sql_select_app_firms = "SELECT * FROM " . $table_app_firms . " WHERE id='" . $_SESSION['SYS_USER_FIRM_ID'] .
            "' ";
        $select_app_firm = new query($sql_select_app_firms);
        if ($select_app_firm->numrows() > 0) {
            echo "<result status='OK'></result>";
            while ($row = $select_app_firm->fetchobject()) {
                echo "<f name='" . $row->firm_name . "' address='" . $row->full_address .
                    "' county='" . $row->county_id . "'" . " town='" . $row->town_id . "' phone='" .
                    $row->phone_number1 . "' fax='" . $row->fax_number . "'" . " website='" . $row->
                    website . "' mersis='" . $row->mersis_number . "' sgk='" . $row->sgk_number .
                    "'" . " apellation='" . $row->firm_apellation . "' tax_number='" . $row->
                    tax_number . "' tax_place='" . $row->tax_place . "'" . " bill_address='" . $row->
                    bill_address . "' bill_email='" . $row->bill_email . "'></f>";
            }
        } else {
            echo "<result status='ERROR'></result>";
        }

        break;
}

?>