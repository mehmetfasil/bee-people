<?php
defined("PASS") or die("Dosya Yok!");
include(CONF_DOCUMENT_ROOT . "modules" . DS . "app" . DS . "includes.php");
switch(ACT){
    case "save":
    
    //params
    $title = checkInput(getvalue("title"));
    $desc = checkInput(getvalue("desc"));
    $uoi = checkInput(getvalue("uoi"));
    $startUnix = checkInput(getvalue("start"));
    $endUnix = checkInput(getvalue("end"));
    $id = checkInput(getvalue("id"));
    
    if($title=="" || $title==null){
        echo "<result status='ERROR'>Etkinlik Başlığı Boş Olamaz</result>";
        return;
    }
    if($uoi!=1 && $uoi!=2){
        echo "<result status='ERROR'>Lütfen Değerleri Doğru Seçiniz</result>";
        return;
    }
    
    if($startUnix=="" || $startUnix==null){
        echo "<result status='ERROR'>Başlangıç Tarihi Doğru Seçilmelidir.</result>";
        return;
    }
    
    if($endUnix=="" || $endUnix==null){
        echo "<result status='ERROR'>Bitiş Tarihi Doğru Seçilmelidir.</result>";
        return;
    }
    if($uoi==2 && $id<=0){
        echo "<result status='ERROR'>Lütfen Değerleri Doğru Seçiniz</result>";
        return;
    } 
    
    $date = new DateTime();
    $date->setTimestamp(substr(getvalue("start"),0,-3));
    $baslangicTarihi =  $date->format('Y-m-d');
    $baslangicSaati = $date->format('H:i:s');
    
    $date2 = new DateTime();
    $date2->setTimestamp(substr(getvalue("end"),0,-3));
    $bitisTarihi = $date2->format('Y-m-d');
    $bitisSaati = $date2->format('H:i:s');
    
    switch($uoi){
        case 1:
            //new record
            $sql = "INSERT INTO ".$table_app_firm_event." (id,firm_id,starttimestamp,endtimestamp,`name`,`desc`,start_date,start_time,end_date,end_time,insert_date,insert_by,is_active)".
            " VALUES(null,'".$_SESSION['SYS_USER_FIRM_ID']."','".getvalue("start")."','".getvalue("end")."','".$title."','".$desc."','".$baslangicTarihi."','".$baslangicSaati."','".$bitisTarihi."','".$bitisSaati."',NOW(),'".$_SESSION['SYS_USER_ID']."',1)";
            $insert = new query($sql);
            if($insert->affectedrows()>0){
                echo "<result status='OK'>Etkinlik Kaydı Başarıyla Oluşturuldu.</result>";
                return;
            }else{
                echo "<result status='ERROR'>Etkinlik Kaydedilirken Hata Oluştu.</result>";
                return;
            }
        break;
        
        case 2:
        //update record
        $sql = "UPDATE ".$table_app_firm_event." SET `name`='".$title."', `desc`='".$desc."',start_date='".$baslangicTarihi."',start_time='".$baslangicSaati."',end_date='".$bitisTarihi."',end_time='".$bitisSaati."',starttimestamp='".getvalue("start")."',endtimestamp='".getvalue("end")."' WHERE firm_id='".$_SESSION['SYS_USER_FIRM_ID']."' and id='".$id."' ";
        $update = new query($sql);
        if($update->affectedrows()>0){
            echo "<result status='OK'>Etkinlik Bitiş Zamanı Başarıyla Güncellendi.</result>";
            return;
        }else{
           echo "<result status='ERROR'>Etkinlik Güncellenirken Hata Oluştu!</result>";
            return; 
        }
        break;
        
    }

    break;
    
    case "get_events":
        $firmId = trim($_SESSION['SYS_USER_FIRM_ID']);
        $get = "SELECT id,`name`,`desc`,CONCAT(start_date,' ',start_time) as concatted,CONCAT(end_date,' ',end_time) as concatted2, starttimestamp as start_date, endtimestamp as end_date FROM ".$table_app_firm_event." WHERE firm_id='".$firmId."' ";
        $query = new query($get);
        if($query->numrows()>0){
            echo "<result status='OK'></result>";
            while($row=$query->fetchobject()){
                $d = new DateTime($row->concatted);
                $dd = new DateTime($row->concatted2);
                echo "<event id='".$row->id."' name='".$row->name."' desc='".$row->desc."' start='".$d->getTimestamp()."000'  end='".$dd->getTimestamp()."000'></event>";
            }
        }else{
            echo "<result status='NORECORD'></result>";
            return;
        }
    break;
}
?>